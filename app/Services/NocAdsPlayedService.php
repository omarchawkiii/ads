<?php

namespace App\Services;

use App\Models\CinemaChain;
use App\Models\Compaign;
use App\Models\CompaignPlayout;
use App\Models\CompaignPlayoutTotal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Consomme GET {NOC}/api/adsmart/ads_played pour chaque chaîne de cinéma d'une campagne,
 * stocke le résultat (compaign_playouts / compaign_playout_totals) et le restitue agrégé.
 */
class NocAdsPlayedService
{
    public const MAX_UUIDS = 200;

    /**
     * Interroge le NOC de chaque chaîne et met à jour la base.
     * Une chaîne en erreur n'empêche pas les autres ; son erreur est renvoyée dans 'chains'.
     */
    public function refresh(Compaign $compaign): array
    {
        $compaign->loadMissing(['cinemaChains', 'locations', 'dcpCreatives']);

        $dcps = $compaign->dcpCreatives->unique('id')
            ->filter(fn ($d) => filled($d->uuid))
            ->values();

        $chains = [];

        foreach ($compaign->cinemaChains as $chain) {
            $chains[] = $this->refreshChain($compaign, $chain, $dcps);
        }

        return [
            'chains'  => $chains,
            'summary' => $this->summary($compaign),
        ];
    }

    private function refreshChain(Compaign $compaign, CinemaChain $chain, $dcps): array
    {
        $out = ['cinema_chain' => $chain->name, 'ok' => false, 'message' => null];

        if (empty(trim((string) $chain->ip_address))) {
            $out['message'] = 'No IP/URL configured for this cinema chain.';
            return $out;
        }
        if ($dcps->isEmpty()) {
            $out['message'] = 'No DCP creative with a UUID on this campaign.';
            return $out;
        }

        $url       = rtrim($chain->ip_address, '/') . '/api/adsmart/ads_played';
        $locations = $compaign->locations->where('cinema_chain_id', $chain->id)->pluck('name')->unique()->values()->all();

        // uuid normalisé (sans "urn:uuid:", minuscule) => dcp_creative_id
        $dcpByUuid = [];
        foreach ($dcps as $dcp) {
            $dcpByUuid[self::normalizeUuid($dcp->uuid)] ??= $dcp->id;
        }

        $rows        = [];   // données NOC, toutes tranches confondues
        $syncedUntil = null;
        $anyNull     = false;

        try {
            foreach (array_chunk($dcps->pluck('uuid')->unique()->values()->all(), self::MAX_UUIDS) as $chunk) {
                $query = [
                    'username'  => $chain->username,
                    'password'  => $chain->password,
                    'uuid'      => $chunk,
                    'from_date' => Carbon::parse($compaign->start_date)->toDateString(),
                    'to_date'   => Carbon::parse($compaign->end_date)->toDateString(),
                ];
                if ($locations) {
                    $query['locations'] = $locations;
                }

                $response = Http::connectTimeout(5)->timeout(30)->acceptJson()->get($url, $query);
                $body     = $response->json();

                if ($response->failed() || empty($body['status'])) {
                    $out['message'] = $body['message'] ?? ('HTTP ' . $response->status());
                    Log::warning('NOC ads_played failed', [
                        'campaign_id' => $compaign->id, 'chain' => $chain->name,
                        'http' => $response->status(), 'message' => $out['message'],
                    ]);
                    return $out;
                }

                foreach ($body['data'] ?? [] as $row) {
                    $rows[] = $row;
                }

                if (empty($body['synced_until'])) {
                    $anyNull = true;
                } else {
                    $ts          = Carbon::parse($body['synced_until']);
                    $syncedUntil = $syncedUntil && $syncedUntil->lt($ts) ? $syncedUntil : $ts;
                }
            }
        } catch (\Throwable $e) {
            // Timeout, connexion refusée, JSON invalide… : ne bloque pas les autres chaînes.
            Log::warning('NOC ads_played error', [
                'campaign_id' => $compaign->id, 'chain' => $chain->name, 'error' => $e->getMessage(),
            ]);
            $out['message'] = $e->getMessage();
            return $out;
        }

        $syncedUntil = $anyNull ? null : $syncedUntil;

        $this->store($compaign, $chain, $dcpByUuid, $rows, $syncedUntil);

        $out['ok']           = true;
        $out['message']      = 'Ads played retrieved successfully.';
        $out['synced_until'] = $syncedUntil?->toDateTimeString();
        return $out;
    }

    private function store(Compaign $compaign, CinemaChain $chain, array $dcpByUuid, array $rows, ?Carbon $syncedUntil): void
    {
        $now     = now();
        $totals  = [];
        $days    = [];
        $covered = [];

        foreach ($rows as $row) {
            $dcpId = $dcpByUuid[self::normalizeUuid($row['cpl_uuid'] ?? '')] ?? null;
            if (!$dcpId) {
                continue;
            }
            $covered[$dcpId] = true;

            foreach ($row['by_location'] ?? [] as $loc) {
                $name = (string) ($loc['location'] ?? '');
                $totals[] = [
                    'compaign_id'     => $compaign->id,
                    'dcp_creative_id' => $dcpId,
                    'cinema_chain_id' => $chain->id,
                    'location_name'   => $name,
                    'played'          => (int) ($loc['played'] ?? 0),
                    'incomplete'      => (int) ($loc['incomplete'] ?? 0),
                    'failed'          => (int) ($loc['failed'] ?? 0),
                    'played_seconds'  => (int) ($loc['played_seconds'] ?? 0),
                    'synced_until'    => $syncedUntil,
                    'fetched_at'      => $now,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ];

                foreach ($loc['by_day'] ?? [] as $day) {
                    $key = $dcpId . '|' . $name . '|' . $day['date'];
                    $days[$key] = [
                        'compaign_id'     => $compaign->id,
                        'dcp_creative_id' => $dcpId,
                        'cinema_chain_id' => $chain->id,
                        'location_name'   => $name,
                        'date'            => $day['date'],
                        'played'          => (int) ($day['played'] ?? 0),
                        'incomplete'      => (int) ($day['incomplete'] ?? 0),
                        'failed'          => (int) ($day['failed'] ?? 0),
                        'synced_until'    => $syncedUntil,
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ];
                }
            }
        }

        // Pub sans diffusion : ligne à zéro (location_name '') qui conserve synced_until.
        foreach ($dcpByUuid as $dcpId) {
            if (!isset($covered[$dcpId]) || !collect($totals)->contains('dcp_creative_id', $dcpId)) {
                $totals[] = [
                    'compaign_id'     => $compaign->id,
                    'dcp_creative_id' => $dcpId,
                    'cinema_chain_id' => $chain->id,
                    'location_name'   => '',
                    'played'          => 0, 'incomplete' => 0, 'failed' => 0, 'played_seconds' => 0,
                    'synced_until'    => $syncedUntil,
                    'fetched_at'      => $now,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ];
            }
        }

        DB::transaction(function () use ($compaign, $chain, $totals, $days) {
            // Remplace l'état précédent de cette chaîne (jours ou locations disparus du NOC compris).
            CompaignPlayout::where('compaign_id', $compaign->id)->where('cinema_chain_id', $chain->id)->delete();
            CompaignPlayoutTotal::where('compaign_id', $compaign->id)->where('cinema_chain_id', $chain->id)->delete();

            $update = ['played', 'incomplete', 'failed', 'synced_until', 'updated_at'];
            foreach (array_chunk($days, 500) as $part) {
                CompaignPlayout::upsert(
                    $part,
                    ['compaign_id', 'dcp_creative_id', 'cinema_chain_id', 'location_name', 'date'],
                    $update
                );
            }
            foreach (array_chunk($totals, 500) as $part) {
                CompaignPlayoutTotal::upsert(
                    $part,
                    ['compaign_id', 'dcp_creative_id', 'cinema_chain_id', 'location_name'],
                    array_merge($update, ['played_seconds', 'fetched_at'])
                );
            }
        });
    }

    /**
     * Lecture agrégée depuis la base (aucun appel NOC) : une entrée par pub de la campagne.
     */
    public function summary(Compaign $compaign): array
    {
        $compaign->loadMissing('dcpCreatives');

        $totals     = CompaignPlayoutTotal::where('compaign_id', $compaign->id)->get();
        $days       = CompaignPlayout::where('compaign_id', $compaign->id)->orderBy('date')->get();
        $chainNames = CinemaChain::whereIn('id', $totals->pluck('cinema_chain_id')->unique())->pluck('name', 'id');

        $dcps = [];
        foreach ($compaign->dcpCreatives->unique('id') as $dcp) {
            $mine = $totals->where('dcp_creative_id', $dcp->id);

            $byChain = $mine->groupBy('cinema_chain_id')->map(fn ($g, $chainId) => [
                'cinema_chain'   => $chainNames[$chainId] ?? ('#' . $chainId),
                'played'         => $g->sum('played'),
                'incomplete'     => $g->sum('incomplete'),
                'failed'         => $g->sum('failed'),
                'played_seconds' => $g->sum('played_seconds'),
                'synced_until'   => self::fmt($g->first()->synced_until),
            ])->values()->all();

            $byLocation = $mine->where('location_name', '!==', '')->map(function ($t) use ($days, $chainNames) {
                return [
                    'cinema_chain'   => $chainNames[$t->cinema_chain_id] ?? ('#' . $t->cinema_chain_id),
                    'location'       => $t->location_name,
                    'played'         => $t->played,
                    'incomplete'     => $t->incomplete,
                    'failed'         => $t->failed,
                    'played_seconds' => $t->played_seconds,
                    'by_day'         => $days
                        ->where('dcp_creative_id', $t->dcp_creative_id)
                        ->where('cinema_chain_id', $t->cinema_chain_id)
                        ->where('location_name', $t->location_name)
                        ->map(fn ($d) => [
                            'date'       => $d->date->toDateString(),
                            'played'     => $d->played,
                            'incomplete' => $d->incomplete,
                            'failed'     => $d->failed,
                        ])->values()->all(),
                ];
            })->values()->all();

            $dcps[] = [
                'dcp_creative_id' => $dcp->id,
                'name'            => $dcp->name,
                'uuid'            => $dcp->uuid,
                'fetched'         => $mine->isNotEmpty(),
                'played'          => $mine->sum('played'),
                'incomplete'      => $mine->sum('incomplete'),
                'failed'          => $mine->sum('failed'),
                'played_seconds'  => $mine->sum('played_seconds'),
                'synced_until'    => self::oldest($mine),
                'by_chain'        => $byChain,
                'by_location'     => $byLocation,
            ];
        }

        return [
            'fetched'         => $totals->isNotEmpty(),
            'last_fetched_at' => self::fmt($totals->max('fetched_at')),
            'synced_until'    => self::oldest($totals),
            'dcps'            => $dcps,
        ];
    }

    /** Plus ancienne synced_until ; null si une chaîne n'a encore rien synchronisé ou si rien n'est chargé. */
    private static function oldest($rows): ?string
    {
        if ($rows->isEmpty() || $rows->contains(fn ($r) => $r->synced_until === null)) {
            return null;
        }
        return self::fmt($rows->min('synced_until'));
    }

    private static function fmt($date): ?string
    {
        return $date ? Carbon::parse($date)->toDateTimeString() : null;
    }

    private static function normalizeUuid(?string $uuid): string
    {
        return strtolower(preg_replace('/^urn:uuid:/i', '', trim((string) $uuid)));
    }
}
