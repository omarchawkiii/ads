<?php

namespace App\Services;

use App\Models\Compaign;
use App\Models\CompaignPlayoutTotal;
use App\Models\Config;
use App\Models\DcpCreative;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Location;
use App\Models\Slot;
use Illuminate\Support\Facades\DB;

class InvoiceGenerator
{
    /**
     * Lignes de facturation d'une campagne, à partir des diffusions confirmées stockées
     * (compaign_playout_totals) :
     *
     *   prix de base  = prix de la location + prix du slot de la pub + prix des types de salle ciblés
     *   prix unitaire = prix de base × position × ciblage × durée × saison × volume   (cumul plafonné)
     *   montant       = played × prix unitaire
     *
     * Multiplicateurs (voir PricingRules) : position = slot ; ciblage, saison et volume = campagne ;
     * durée = pub. Tous sont figés sur la ligne de facture. Ensuite, pour chaque cinéma ayant diffusé,
     * une ligne « minimum » complète son total jusqu'au minimum par cinéma et par semaine.
     *
     * Un prix NULL sur la location ou le slot est remplacé par le prix par défaut de la config.
     * Types de salle : la somme des prix des types de salle ciblés par la campagne s'ajoute à chaque
     * diffusion (le NOC ne dit pas dans quelle salle a eu lieu une diffusion). Aucun type ciblé = 0.
     * Si une pub est dans plusieurs slots de la campagne, le premier (par position) est retenu.
     *
     * @return array<int, array<string, mixed>>
     */
    public function lines(Compaign $compaign): array
    {
        $config       = Config::first();
        $defaultLoc   = (float) ($config->default_location_price ?? 0);
        $defaultSlot  = (float) ($config->default_slot_price ?? 0);
        $defaultHall  = (float) ($config->default_hall_type_price ?? 0);

        $totals = CompaignPlayoutTotal::where('compaign_id', $compaign->id)
            ->where('location_name', '!=', '')
            ->where('played', '>', 0)
            ->get();

        if ($totals->isEmpty()) {
            return [];
        }

        // pub => slot (premier par position)
        $slotByDcp = DB::table('compaign_slot_dcp')
            ->where('compaign_id', $compaign->id)
            ->orderBy('position')
            ->get(['dcp_creative_id', 'slot_id'])
            ->unique('dcp_creative_id')
            ->pluck('slot_id', 'dcp_creative_id');

        $slots = Slot::withTrashed()->whereIn('id', $slotByDcp->values())->get()->keyBy('id');
        $dcps  = DcpCreative::withTrashed()->whereIn('id', $totals->pluck('dcp_creative_id')->unique())->get()->keyBy('id');
        $chains = \App\Models\CinemaChain::whereIn('id', $totals->pluck('cinema_chain_id')->unique())->get()->keyBy('id');
        $defaultShare = (float) ($config->default_revenue_share_percent ?? 60);

        $rules = new PricingRules($config);
        [$targetMult, $targetLevel] = $rules->targeting($compaign);
        $seasonMult = $rules->season($compaign->start_date, $compaign->end_date);
        $volumeMult = $rules->volume($rules->days($compaign));

        $halls     = $compaign->hallTypes()->get();
        $hallPrice = round($halls->sum(fn ($h) => (float) ($h->price ?? $defaultHall)), 2);
        $hallNames = $halls->pluck('name')->implode(', ') ?: null;

        $lines = [];
        foreach ($totals as $t) {
            $slot     = $slots[$slotByDcp[$t->dcp_creative_id] ?? null] ?? null;
            $location = Location::where('name', $t->location_name)
                ->where('cinema_chain_id', $t->cinema_chain_id)
                ->first();

            $locPrice  = round((float) ($location?->price ?? $defaultLoc), 2);
            $slotPrice = round((float) ($slot?->price ?? $defaultSlot), 2);

            $seconds = (int) ($dcps[$t->dcp_creative_id]->duration ?? 0) ?: (int) $compaign->ad_duration;
            $posMult = $rules->position($slot);
            $lenMult = $rules->length($seconds);
            [$rawMult, $mult] = $rules->combine($posMult, $targetMult, $lenMult, $seasonMult, $volumeMult);

            // Prix unitaire arrondi au centime : le client peut refaire le calcul (plays × prix unitaire).
            $unitPrice = round(($locPrice + $slotPrice + $hallPrice) * $mult, 2);

            $lines[] = [
                'dcp_name'       => $dcps[$t->dcp_creative_id]->name ?? ('#' . $t->dcp_creative_id),
                'slot_name'      => $slot?->name,
                'cinema_chain'   => $chains[$t->cinema_chain_id]->name ?? null,
                'cinema_chain_id' => $t->cinema_chain_id,
                // % de partage de la chaîne figé à la génération : changer le % plus tard ne touche pas cette facture.
                'share_percent'  => (float) ($chains[$t->cinema_chain_id]->revenue_share_percent ?? $defaultShare),
                'location_name'  => $t->location_name,
                'played'         => (int) $t->played,
                'location_price' => $locPrice,
                'slot_price'     => $slotPrice,
                'hall_price'     => $hallPrice,
                'hall_types'     => $hallNames,
                'line_type'      => 'plays',
                'unit_price'     => $unitPrice,
                'multiplier'     => $mult,
                'multiplier_raw' => $rawMult,
                'multiplier_position'  => $posMult,
                'multiplier_targeting' => $targetMult,
                'multiplier_length'    => $lenMult,
                'multiplier_season'    => $seasonMult,
                'multiplier_volume'    => $volumeMult,
                'targeting_level'      => $targetLevel,
                'amount'         => round($t->played * $unitPrice, 2),
            ];
        }

        return array_merge($lines, $this->minimumLines($compaign, $rules, $lines));
    }

    /**
     * Minimum par cinéma et par semaine : pour chaque cinéma ayant au moins une diffusion confirmée,
     * si le total de ses lignes est inférieur à (minimum × semaines de la campagne), une ligne de
     * complément égale à l'écart est ajoutée. Minimum à 0 = désactivé.
     *
     * @param  array<int, array<string, mixed>>  $lines
     * @return array<int, array<string, mixed>>
     */
    private function minimumLines(Compaign $compaign, PricingRules $rules, array $lines): array
    {
        $minimum = $rules->minimumPerOutletWeek();
        if ($minimum <= 0 || !$lines) {
            return [];
        }

        $weeks    = $rules->weeks($compaign);
        $required = round($minimum * $weeks, 2);

        $byOutlet = [];
        foreach ($lines as $l) {
            $key = $l['cinema_chain_id'] . '|' . $l['location_name'];
            $byOutlet[$key] ??= ['total' => 0.0, 'line' => $l];
            $byOutlet[$key]['total'] += $l['amount'];
        }

        $extra = [];
        foreach ($byOutlet as $o) {
            $shortfall = round($required - $o['total'], 2);
            if ($shortfall <= 0) {
                continue;
            }
            $l = $o['line'];
            $extra[] = [
                'dcp_name'       => sprintf('Minimum charge (RM %s x %d %s)', number_format($minimum, 2), $weeks, $weeks > 1 ? 'weeks' : 'week'),
                'slot_name'      => null,
                'cinema_chain'   => $l['cinema_chain'],
                'cinema_chain_id' => $l['cinema_chain_id'],
                'share_percent'  => $l['share_percent'],
                'location_name'  => $l['location_name'],
                'played'         => 0,
                'location_price' => 0,
                'slot_price'     => 0,
                'hall_price'     => 0,
                'hall_types'     => null,
                'line_type'      => 'minimum',
                'unit_price'     => null,
                'multiplier'     => 1,
                'multiplier_raw' => 1,
                'multiplier_position'  => 1,
                'multiplier_targeting' => 1,
                'multiplier_length'    => 1,
                'multiplier_season'    => 1,
                'multiplier_volume'    => 1,
                'targeting_level'      => null,
                'amount'         => $shortfall,
            ];
        }

        return $extra;
    }

    /**
     * Crée la facture d'une campagne. Idempotent : si la campagne a déjà une facture, elle est
     * renvoyée sans modification. La facture est créée même sans diffusion confirmée (montant 0,
     * sans ligne, statut « paid » puisqu'il n'y a rien à payer).
     */
    public function generate(Compaign $compaign): Invoice
    {
        return DB::transaction(function () use ($compaign) {
            if ($existing = Invoice::where('compaign_id', $compaign->id)->lockForUpdate()->first()) {
                return $existing;
            }

            $lines   = $this->lines($compaign);
            $totalHt = round(array_sum(array_column($lines, 'amount')), 2);

            $tax      = (float) (Config::first()->tax ?? 8);
            $totalTtc = round($totalHt * (1 + $tax / 100), 2);

            // Numéro séquentiel par année ; le verrou évite deux numéros identiques.
            $year    = now()->format('Y');
            $lastSeq = Invoice::whereYear('date', $year)->lockForUpdate()
                ->selectRaw("MAX(CAST(SUBSTRING_INDEX(number, '-', -1) AS UNSIGNED)) as max_seq")
                ->value('max_seq') ?? 0;

            $invoice = Invoice::create([
                'compaign_id' => $compaign->id,
                'number'      => 'INV-' . $year . '-' . str_pad($lastSeq + 1, 5, '0', STR_PAD_LEFT),
                'date'        => now()->toDateString(),
                'due_date'    => now()->addDays(30)->toDateString(),
                'status'      => $totalTtc > 0 ? 'unpaid' : 'paid',
                'discount'    => 0,
                'tax'         => $tax,
                'total_ht'    => $totalHt,
                'total_ttc'   => $totalTtc,
            ]);

            foreach ($lines as $line) {
                InvoiceLine::create($line + ['invoice_id' => $invoice->id]);
            }

            return $invoice;
        });
    }
}
