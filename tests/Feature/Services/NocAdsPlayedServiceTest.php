<?php

namespace Tests\Feature\Services;

use App\Models\Compaign;
use App\Models\CompaignPlayout;
use App\Models\CompaignPlayoutTotal;
use App\Models\User;
use App\Services\NocAdsPlayedService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Run against a dedicated DB: DB_DATABASE=adsmart_campaign_test
 */
final class NocAdsPlayedServiceTest extends TestCase
{
    use RefreshDatabase;

    private const URL_A = 'http://noc-a.test';
    private const URL_B = 'http://noc-b.test';

    private array $ref;
    private Compaign $campaign;

    protected function setUp(): void
    {
        parent::setUp();

        $now = now();
        $id  = fn (string $t, array $r) => DB::table($t)->insertGetId($r + ['created_at' => $now, 'updated_at' => $now]);

        $chainA = $id('cinema_chains', ['name' => 'Chain A', 'ip_address' => self::URL_A, 'username' => 'ua', 'password' => 'pa']);
        $chainB = $id('cinema_chains', ['name' => 'Chain B', 'ip_address' => self::URL_B, 'username' => 'ub', 'password' => 'pb']);

        $locA1 = $id('locations', ['name' => 'Max Cinema', 'cpm' => 10, 'cinema_chain_id' => $chainA]);
        $locB1 = $id('locations', ['name' => 'Star Hall', 'cpm' => 10, 'cinema_chain_id' => $chainB]);

        $dcp1 = $id('dcp_creatives', ['name' => 'Coca 30s', 'duration' => 30, 'uuid' => 'urn:uuid:AAAA-1']);
        $dcp2 = $id('dcp_creatives', ['name' => 'Pepsi 15s', 'duration' => 15, 'uuid' => 'bbbb-2']);

        $slot = $id('slots', ['name' => 'S1', 'cpm' => 10]);
        $user = User::factory()->create(['role' => 1]);

        $campaignId = $id('compaigns', [
            'name' => 'Camp', 'start_date' => '2026-11-21', 'end_date' => '2026-12-04',
            'budget' => 100, 'status' => 2, 'user_id' => $user->id,
        ]);
        foreach ([$chainA, $chainB] as $c) {
            DB::table('compaign_cinema_chain')->insert(['compaign_id' => $campaignId, 'cinema_chain_id' => $c, 'created_at' => $now, 'updated_at' => $now]);
        }
        foreach ([$locA1, $locB1] as $l) {
            DB::table('compaign_location')->insert(['compaign_id' => $campaignId, 'location_id' => $l]);
        }
        foreach ([$dcp1 => 1, $dcp2 => 2] as $dcp => $pos) {
            DB::table('compaign_slot_dcp')->insert([
                'compaign_id' => $campaignId, 'dcp_creative_id' => $dcp, 'slot_id' => $slot,
                'position' => $pos, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        $this->ref      = compact('chainA', 'chainB', 'dcp1', 'dcp2', 'user', 'slot');
        $this->campaign = Compaign::findOrFail($campaignId);
    }

    private function nocResponse(array $data, ?string $syncedUntil = '2026-11-25 09:00:00'): array
    {
        return ['status' => 1, 'synced_until' => $syncedUntil, 'last_sync_at' => '2026-11-25 10:00:00', 'data' => $data, 'message' => 'ok'];
    }

    private function cpl(string $uuid, int $played, array $locations = []): array
    {
        return [
            'cpl_uuid' => 'urn:uuid:' . $uuid, 'title' => 't', 'played' => $played, 'incomplete' => 0, 'failed' => 0,
            'played_seconds' => $played * 30, 'by_location' => $locations,
        ];
    }

    private function loc(string $name, int $played, array $days, int $seconds = null): array
    {
        return [
            'location' => $name, 'played' => $played, 'incomplete' => 1, 'failed' => 0,
            'played_seconds' => $seconds ?? $played * 30, 'by_screen' => [],
            'by_day' => array_map(fn ($d, $p) => ['date' => $d, 'played' => $p, 'incomplete' => 0, 'failed' => 0], array_keys($days), $days),
        ];
    }

    #[Test]
    public function aggregates_multiple_chains_and_keeps_ad_without_plays(): void
    {
        Http::fake([
            self::URL_A . '/*' => Http::response($this->nocResponse([
                $this->cpl('aaaa-1', 10, [$this->loc('Max Cinema', 10, ['2026-11-21' => 6, '2026-11-22' => 4])]),
                $this->cpl('bbbb-2', 0),
            ], '2026-11-25 09:00:00')),
            self::URL_B . '/*' => Http::response($this->nocResponse([
                $this->cpl('aaaa-1', 5, [$this->loc('Star Hall', 5, ['2026-11-21' => 5])]),
                $this->cpl('bbbb-2', 0),
            ], '2026-11-24 08:00:00')),
        ]);

        $result = app(NocAdsPlayedService::class)->refresh($this->campaign);

        $this->assertSame([true, true], array_column($result['chains'], 'ok'));

        $summary = $result['summary'];
        $coca    = collect($summary['dcps'])->firstWhere('name', 'Coca 30s');
        $pepsi   = collect($summary['dcps'])->firstWhere('name', 'Pepsi 15s');

        $this->assertSame(15, $coca['played']);
        $this->assertSame(2, $coca['incomplete']);
        $this->assertSame(450, $coca['played_seconds']);
        $this->assertCount(2, $coca['by_chain']);
        $this->assertCount(2, $coca['by_location']);
        $this->assertSame(
            ['2026-11-21', '2026-11-22'],
            array_column(collect($coca['by_location'])->firstWhere('location', 'Max Cinema')['by_day'], 'date')
        );

        // pub sans diffusion : présente, à zéro, marquée comme récupérée
        $this->assertSame(0, $pepsi['played']);
        $this->assertTrue($pepsi['fetched']);

        // plus ancienne synced_until
        $this->assertSame('2026-11-24 08:00:00', $summary['synced_until']);
    }

    #[Test]
    public function sends_expected_query_with_chain_credentials_and_location_filter(): void
    {
        Http::fake(fn () => Http::response($this->nocResponse([$this->cpl('aaaa-1', 0), $this->cpl('bbbb-2', 0)])));

        app(NocAdsPlayedService::class)->refresh($this->campaign);

        Http::assertSent(function (Request $r) {
            if (!str_starts_with($r->url(), self::URL_A)) {
                return false;
            }
            parse_str(parse_url($r->url(), PHP_URL_QUERY), $q);
            return str_ends_with(parse_url($r->url(), PHP_URL_PATH), '/api/adsmart/ads_played')
                && $q['username'] === 'ua' && $q['password'] === 'pa'
                && $q['from_date'] === '2026-11-21' && $q['to_date'] === '2026-12-04'
                && $q['uuid'] === ['urn:uuid:AAAA-1', 'bbbb-2']
                && $q['locations'] === ['Max Cinema'];
        });
        Http::assertSent(function (Request $r) {
            if (!str_starts_with($r->url(), self::URL_B)) {
                return false;
            }
            parse_str(parse_url($r->url(), PHP_URL_QUERY), $q);
            return $q['username'] === 'ub' && $q['locations'] === ['Star Hall'];
        });
    }

    #[Test]
    public function a_failing_chain_does_not_block_the_others(): void
    {
        Http::fake([
            self::URL_A . '/*' => Http::response(['status' => 0, 'message' => 'Invalid credentials.'], 401),
            self::URL_B . '/*' => Http::response($this->nocResponse([
                $this->cpl('aaaa-1', 5, [$this->loc('Star Hall', 5, ['2026-11-21' => 5])]),
                $this->cpl('bbbb-2', 0),
            ])),
        ]);

        $result = app(NocAdsPlayedService::class)->refresh($this->campaign);

        $this->assertFalse($result['chains'][0]['ok']);
        $this->assertSame('Invalid credentials.', $result['chains'][0]['message']);
        $this->assertTrue($result['chains'][1]['ok']);
        $this->assertSame(5, collect($result['summary']['dcps'])->firstWhere('name', 'Coca 30s')['played']);
        $this->assertSame(0, CompaignPlayoutTotal::where('cinema_chain_id', $this->ref['chainA'])->count());
    }

    #[Test]
    public function timeout_and_status_zero_are_reported_per_chain(): void
    {
        Http::fake([
            self::URL_A . '/*' => fn () => throw new ConnectionException('cURL error 28: timed out'),
            self::URL_B . '/*' => Http::response(['status' => 0, 'message' => 'No location found.'], 200),
        ]);

        $result = app(NocAdsPlayedService::class)->refresh($this->campaign);

        $this->assertSame([false, false], array_column($result['chains'], 'ok'));
        $this->assertStringContainsString('timed out', $result['chains'][0]['message']);
        $this->assertSame('No location found.', $result['chains'][1]['message']);
        $this->assertFalse($result['summary']['fetched']);
    }

    #[Test]
    public function null_synced_until_means_noc_has_not_synced_anything(): void
    {
        Http::fake([
            self::URL_A . '/*' => Http::response($this->nocResponse([$this->cpl('aaaa-1', 0), $this->cpl('bbbb-2', 0)], null)),
            self::URL_B . '/*' => Http::response($this->nocResponse([$this->cpl('aaaa-1', 0), $this->cpl('bbbb-2', 0)], '2026-11-25 09:00:00')),
        ]);

        $summary = app(NocAdsPlayedService::class)->refresh($this->campaign)['summary'];

        $this->assertTrue($summary['fetched']);
        $this->assertNull($summary['synced_until']);
    }

    #[Test]
    public function refresh_replaces_previous_data_without_duplicates(): void
    {
        $zero  = $this->nocResponse([$this->cpl('aaaa-1', 0), $this->cpl('bbbb-2', 0)]);
        $chain = fn (int $p) => $this->nocResponse([
            $this->cpl('aaaa-1', $p, [$this->loc('Max Cinema', $p, ['2026-11-21' => $p])]),
            $this->cpl('bbbb-2', 0),
        ]);

        Http::fake([
            self::URL_A . '/*' => Http::sequence()->push($chain(3))->push($chain(8)),
            self::URL_B . '/*' => Http::response($zero),
        ]);

        app(NocAdsPlayedService::class)->refresh($this->campaign);
        app(NocAdsPlayedService::class)->refresh($this->campaign);

        $this->assertSame(1, CompaignPlayout::where('cinema_chain_id', $this->ref['chainA'])->count());
        $this->assertSame(8, (int) CompaignPlayout::where('cinema_chain_id', $this->ref['chainA'])->value('played'));
    }

    #[Test]
    public function uuids_are_chunked_by_200(): void
    {
        $now = now();
        for ($i = 0; $i < 205; $i++) {
            $dcp = DB::table('dcp_creatives')->insertGetId(['name' => "d$i", 'duration' => 10, 'uuid' => "extra-$i", 'created_at' => $now, 'updated_at' => $now]);
            DB::table('compaign_slot_dcp')->insert([
                'compaign_id' => $this->campaign->id, 'dcp_creative_id' => $dcp, 'slot_id' => $this->ref['slot'],
                'position' => 10 + $i, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
        Http::fake(fn () => Http::response($this->nocResponse([])));

        app(NocAdsPlayedService::class)->refresh($this->campaign->fresh());

        $sizes = [];
        Http::assertSent(function (Request $r) use (&$sizes) {
            if (str_starts_with($r->url(), self::URL_A)) {
                parse_str(parse_url($r->url(), PHP_URL_QUERY), $q);
                $sizes[] = count($q['uuid']);
            }
            return true;
        });
        $this->assertSame([200, 7], $sizes); // 207 uuid distincts
    }

    #[Test]
    public function advertiser_cannot_read_playouts_of_another_advertisers_campaign(): void
    {
        $other = User::factory()->create(['role' => 2]);
        $this->actingAs($other)
            ->getJson("/advertiser/compaigns/{$this->campaign->id}/playouts")
            ->assertForbidden();

        $owner = User::factory()->create(['role' => 2]);
        $this->campaign->update(['user_id' => $owner->id]);
        $this->actingAs($owner)
            ->getJson("/advertiser/compaigns/{$this->campaign->id}/playouts")
            ->assertOk()->assertJsonPath('fetched', false);
    }

    #[Test]
    public function admin_refresh_endpoint_returns_summary_and_chain_status(): void
    {
        Http::fake([
            self::URL_A . '/*' => Http::response($this->nocResponse([$this->cpl('aaaa-1', 2, [$this->loc('Max Cinema', 2, ['2026-11-21' => 2])]), $this->cpl('bbbb-2', 0)])),
            self::URL_B . '/*' => Http::response(['status' => 0, 'message' => 'boom'], 500),
        ]);

        $this->actingAs($this->ref['user'])
            ->putJson("/compaigns/billing/{$this->campaign->id}")
            ->assertOk()
            ->assertJsonPath('status', 1)
            ->assertJsonPath('chains.1.ok', false)
            ->assertJsonPath('summary.dcps.0.played', 2);
    }
}
