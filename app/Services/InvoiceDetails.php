<?php

namespace App\Services;

use App\Models\Invoice;

/**
 * Toutes les informations réelles d'une facture (annonceur, client, campagne, lignes de
 * facturation, preuve de diffusion, paiements, totaux). Source unique pour la modale admin,
 * la modale annonceur et le PDF.
 */
class InvoiceDetails
{
    public function __construct(private NocAdsPlayedService $noc)
    {
    }

    public function build(Invoice $invoice): array
    {
        $invoice->loadMissing([
            'lines',
            'payments',
            'compaign.user',
            'compaign.compaignObjective',
            'compaign.compaignCategory',
            'compaign.langue',
            'compaign.cinemaChains',
            'compaign.locations.cinemaChain',
            'compaign.slots',
            'compaign.hallTypes',
            'compaign.masterMovies',
            'compaign.dcpCreatives.customer',
        ]);

        $c = $invoice->compaign;

        $totalHt  = round((float) $invoice->total_ht, 2);
        $totalTtc = round((float) $invoice->total_ttc, 2);
        $paid     = round((float) $invoice->payments->sum('amount'), 2);
        $remaining = round($totalTtc - $paid, 2);

        $details = [
            'invoice' => $invoice, // modèle complet (payments, lines) : compatibilité avec la modale de paiement
            'totals'  => [
                'subtotal'   => $totalHt,
                'discount'   => round((float) $invoice->discount, 2),
                'discount_type'   => $invoice->discount_type,
                'discount_value'  => $invoice->discount_value !== null ? (float) $invoice->discount_value : null,
                'discount_reason' => $invoice->discount_reason,
                'discount_at'     => $invoice->discount_at ? (string) $invoice->discount_at : null,
                'net'        => round($totalHt - (float) $invoice->discount, 2),
                'tax_rate'   => (float) $invoice->tax,
                'tax_amount' => round($totalTtc - ($totalHt - (float) $invoice->discount), 2),
                'total'      => $totalTtc,
                'paid'       => $paid,
                'remaining'  => $remaining,
                'overdue'    => $remaining > 0 && $invoice->due_date && (string) $invoice->due_date < now()->toDateString(),
            ],
            'advertiser' => null,
            'customers'  => [],
            'campaign'   => null,
            'proof_of_play' => null,
        ];

        if (!$c) {
            return $details;
        }

        $user = $c->user;
        $details['advertiser'] = $user ? [
            'name'  => trim($user->name . ' ' . $user->last_name),
            'email' => $user->email,
            'type'  => $user->advertiser_type,
        ] : null;

        // Clients facturés : ceux des pubs (DCP) de la campagne.
        $details['customers'] = $c->dcpCreatives->pluck('customer')->filter()->unique('id')->map(fn ($cu) => [
            'name'    => $cu->name,
            'brand'   => $cu->brand_name,
            'address' => collect([$cu->address, $cu->address_2, trim($cu->postcode . ' ' . $cu->city), $cu->state, $cu->country])->filter()->implode(', '),
            'email'   => $cu->email,
            'phone'   => $cu->phone,
            'pic'     => $cu->pic_name,
        ])->values()->all();

        $details['campaign'] = [
            'id'         => $c->id,
            'name'       => $c->name,
            'start_date' => (string) $c->start_date,
            'end_date'   => (string) $c->end_date,
            'days'       => $c->start_date && $c->end_date
                ? \Carbon\Carbon::parse($c->start_date)->diffInDays(\Carbon\Carbon::parse($c->end_date)) + 1
                : null,
            'ad_duration' => $c->ad_duration,
            'objective'  => $c->compaignObjective?->name,
            'category'   => $c->compaignCategory?->name,
            'language'   => $c->langue?->name,
            'cinema_chains' => $c->cinemaChains->pluck('name')->values()->all(),
            'locations'  => $c->locations->map(fn ($l) => [
                'name'  => $l->name,
                'chain' => $l->cinemaChain?->name,
            ])->values()->all(),
            'slots'      => $c->slots->pluck('name')->values()->all(),
            'hall_types' => $c->hallTypes->pluck('name')->values()->all(),
            'movies'     => $c->masterMovies->pluck('title')->values()->all(),
            'ads'        => $c->dcpCreatives->unique('id')->map(fn ($d) => [
                'name' => $d->name, 'duration' => $d->duration,
            ])->values()->all(),
        ];

        // Preuve de diffusion (données NOC stockées, aucun appel réseau).
        $details['proof_of_play'] = $this->noc->summary($c);

        return $details;
    }
}
