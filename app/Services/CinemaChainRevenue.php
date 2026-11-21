<?php

namespace App\Services;

use App\Models\CinemaChain;
use App\Models\Config;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;

/**
 * Revenu publicitaire des chaînes de cinéma d'un utilisateur (rôle « Cinema Chain »).
 *
 *  - Revenu = montant HT des lignes de facture de ses chaînes (remise et taxe ignorées : lignes telles quelles).
 *  - Part de la chaîne = revenu × % de partage (propre à la chaîne, sinon défaut de la config).
 *  - Facturé = dès que la facture existe ; encaissé = part × (payé / total TTC), plafonné à 100 %.
 *  - Les factures à 0 (campagne sans diffusion) sont listées.
 *  - Aucune donnée d'annonceur ni de client : seulement le nom de la campagne.
 */
class CinemaChainRevenue
{
    /** Chaînes de l'utilisateur, indexées par id, avec leur % de partage effectif. */
    public function chains(User $user): array
    {
        $default = (float) (Config::first()?->default_revenue_share_percent ?? 100);

        return $user->cinemaChains()->orderBy('name')->get()
            ->mapWithKeys(fn (CinemaChain $c) => [$c->id => [
                'id'      => $c->id,
                'name'    => $c->name,
                'percent' => (float) ($c->revenue_share_percent ?? $default),
                'custom'  => $c->revenue_share_percent !== null,
            ]])->all();
    }

    public function report(User $user, ?string $from = null, ?string $to = null, ?int $chainId = null): array
    {
        $chains = $this->chains($user);
        $ids    = array_keys($chains);

        // Filtre sur une chaîne qui n'appartient pas à l'utilisateur : rien (jamais les données d'autrui).
        if ($chainId !== null) {
            $ids = in_array($chainId, $ids, true) ? [$chainId] : [];
        }

        $report = [
            'chains'      => array_values($chains),
            'totals'      => ['invoices' => 0, 'plays' => 0, 'gross' => 0.0, 'share' => 0.0, 'collected' => 0.0, 'pending' => 0.0],
            'by_month'    => [],
            'by_location' => [],
            'by_chain'    => [],
            'invoices'    => [],
        ];

        if (!$ids) {
            return $report;
        }

        $invoices = Invoice::query()
            ->where(function ($q) use ($ids) {
                $q->whereHas('lines', fn ($l) => $l->whereIn('cinema_chain_id', $ids))
                  ->orWhereHas('compaign.cinemaChains', fn ($c) => $c->whereIn('cinema_chains.id', $ids));
            })
            ->when($from, fn ($q) => $q->whereDate('date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('date', '<=', $to))
            ->with([
                'lines'    => fn ($l) => $l->whereIn('cinema_chain_id', $ids),
                'payments',
                'compaign:id,name',
            ])
            ->orderByDesc('date')->orderByDesc('id')
            ->get();

        $by_chain = $by_location = $by_month = [];
        $totals   = ['invoices' => 0, 'plays' => 0, 'gross' => 0.0, 'share' => 0.0, 'collected' => 0.0];
        $rows     = [];

        foreach ($invoices as $invoice) {
            $gross = $share = 0.0;
            $plays = 0;

            foreach ($invoice->lines as $line) {
                $chain  = $chains[$line->cinema_chain_id];
                $amount = (float) $line->amount;
                // % figé sur la ligne ; les lignes antérieures à la migration sans valeur suivent le % actuel.
                $pct    = $line->share_percent !== null ? (float) $line->share_percent : $chain['percent'];
                $part   = $amount * $pct / 100;
                $gross += $amount;
                $share += $part;
                $plays += (int) $line->played;

                $by_chain[$chain['id']] ??= ['name' => $chain['name'], 'plays' => 0, 'gross' => 0.0, 'share' => 0.0];
                $by_chain[$chain['id']]['plays'] += (int) $line->played;
                $by_chain[$chain['id']]['gross'] += $amount;
                $by_chain[$chain['id']]['share'] += $part;

                $key = $chain['id'] . '|' . $line->location_name;
                $by_location[$key] ??= ['location' => $line->location_name, 'chain' => $chain['name'], 'plays' => 0, 'gross' => 0.0, 'share' => 0.0];
                $by_location[$key]['plays'] += (int) $line->played;
                $by_location[$key]['gross'] += $amount;
                $by_location[$key]['share'] += $part;
            }

            $paid      = (float) $invoice->payments->sum('amount');
            $totalTtc  = (float) $invoice->total_ttc;
            $ratio     = $totalTtc > 0 ? min(1.0, $paid / $totalTtc) : 0.0;
            $collected = $share * $ratio;
            $month     = Carbon::parse($invoice->date)->format('Y-m');

            $by_month[$month] ??= ['month' => $month, 'gross' => 0.0, 'share' => 0.0, 'collected' => 0.0];
            $by_month[$month]['gross']     += $gross;
            $by_month[$month]['share']     += $share;
            $by_month[$month]['collected'] += $collected;

            $rows[] = [
                'number'    => $invoice->number,
                'campaign'  => $invoice->compaign?->name ?? '–',
                'date'      => Carbon::parse($invoice->date)->toDateString(),
                'status'    => $invoice->status,
                'plays'     => $plays,
                'gross'     => round($gross, 2),
                'share'     => round($share, 2),
                'share_percent' => $gross > 0 ? round($share / $gross * 100, 2) : null,
                'collected' => round($collected, 2),
                'pending'   => round($share - $collected, 2),
            ];

            $totals['invoices']++;
            $totals['plays']     += $plays;
            $totals['gross']     += $gross;
            $totals['share']     += $share;
            $totals['collected'] += $collected;
        }

        $totals['pending'] = $totals['share'] - $totals['collected'];
        foreach (['gross', 'share', 'collected', 'pending'] as $k) {
            $totals[$k] = round($totals[$k], 2);
        }

        ksort($by_month);
        $round = fn (array $set, array $keys) => array_values(array_map(function ($r) use ($keys) {
            foreach ($keys as $k) {
                $r[$k] = round($r[$k], 2);
            }
            return $r;
        }, $set));

        $by_location = $round($by_location, ['gross', 'share']);
        usort($by_location, fn ($a, $b) => $b['share'] <=> $a['share']);

        return array_merge($report, [
            'totals'      => $totals,
            'by_month'    => $round($by_month, ['gross', 'share', 'collected']),
            'by_chain'    => $round($by_chain, ['gross', 'share']),
            'by_location' => $by_location,
            'invoices'    => $rows,
        ]);
    }
}
