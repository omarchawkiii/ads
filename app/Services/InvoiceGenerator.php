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
     *   montant = played × (prix de la location + prix du slot de la pub + prix des types de salle ciblés)
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
        $chains = \App\Models\CinemaChain::whereIn('id', $totals->pluck('cinema_chain_id')->unique())->pluck('name', 'id');

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

            $lines[] = [
                'dcp_name'       => $dcps[$t->dcp_creative_id]->name ?? ('#' . $t->dcp_creative_id),
                'slot_name'      => $slot?->name,
                'cinema_chain'   => $chains[$t->cinema_chain_id] ?? null,
                'location_name'  => $t->location_name,
                'played'         => (int) $t->played,
                'location_price' => $locPrice,
                'slot_price'     => $slotPrice,
                'hall_price'     => $hallPrice,
                'hall_types'     => $hallNames,
                'amount'         => round($t->played * ($locPrice + $slotPrice + $hallPrice), 2),
            ];
        }

        return $lines;
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

            $tax      = (float) (Config::first()->tax ?? 6);
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
