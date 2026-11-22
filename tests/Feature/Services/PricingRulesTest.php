<?php

namespace Tests\Feature\Services;

use App\Models\Compaign;
use App\Models\Config;
use App\Models\Invoice;
use App\Models\User;
use App\Services\InvoiceGenerator;
use App\Services\PricingRules;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Tarification « deck » : prix de base (location + slot + salle) x multiplicateurs, plafond,
 * minimum par cinéma et par semaine.
 * Run against a dedicated DB: DB_DATABASE=adsmart_campaign_test (APP_URL=http://localhost)
 */
final class PricingRulesTest extends TestCase
{
    use RefreshDatabase;

    private int $chain;
    private int $slotId;
    private int $dcpId;
    private int $loc1;
    private int $loc2;
    private Compaign $campaign;

    private function id(string $t, array $r): int
    {
        $now = now();
        return DB::table($t)->insertGetId($r + ['created_at' => $now, 'updated_at' => $now]);
    }

    /** Base RM 7 (5 + 2, prix par défaut), tous les multiplicateurs aux valeurs du deck, minimum désactivé. */
    protected function setUp(): void
    {
        parent::setUp();

        Config::query()->delete();
        Config::create([
            'use_noc' => 0, 'tax' => 8, 'default_location_price' => 5, 'default_slot_price' => 2,
            'default_hall_type_price' => 0, 'default_revenue_share_percent' => 60, 'min_outlet_week_price' => 0,
        ]);

        $this->chain  = $this->id('cinema_chains', ['name' => 'Chain A', 'ip_address' => 'http://noc.test', 'username' => 'u', 'password' => 'p']);
        $this->slotId = $this->id('slots', ['name' => 'Trailers', 'cpm' => 0]); // pas de prix => défaut 2
        $this->dcpId  = $this->id('dcp_creatives', ['name' => 'Coca 30s', 'duration' => 30, 'uuid' => 'aaaa-1']);
        $this->loc1   = $this->id('locations', ['name' => 'KLCC', 'cpm' => 0, 'cinema_chain_id' => $this->chain]);
        $this->loc2   = $this->id('locations', ['name' => 'Sunway', 'cpm' => 0, 'cinema_chain_id' => $this->chain]);

        $this->campaign = $this->makeCampaign('2026-10-01', '2026-10-07'); // 7 jours = 1 semaine
        $this->played('KLCC', 100);
    }

    private function makeCampaign(string $start, string $end): Compaign
    {
        $cid = $this->id('compaigns', [
            'name' => 'C', 'start_date' => $start, 'end_date' => $end,
            'budget' => 500, 'status' => 2, 'user_id' => User::factory()->create(['role' => 2])->id,
        ]);
        $this->id('compaign_slot_dcp', ['compaign_id' => $cid, 'dcp_creative_id' => $this->dcpId, 'slot_id' => $this->slotId, 'position' => 1]);

        return Compaign::findOrFail($cid);
    }

    private function played(string $location, int $played, ?Compaign $c = null): void
    {
        $this->id('compaign_playout_totals', [
            'compaign_id' => ($c ?? $this->campaign)->id, 'dcp_creative_id' => $this->dcpId,
            'cinema_chain_id' => $this->chain, 'location_name' => $location,
            'played' => $played, 'synced_until' => '2026-10-11 10:00:00', 'fetched_at' => now(),
        ]);
    }

    /** Première ligne de diffusion. */
    private function line(?Compaign $c = null): array
    {
        return collect(app(InvoiceGenerator::class)->lines($c ?? $this->campaign))->firstWhere('line_type', 'plays');
    }

    private function cfg(array $v): void
    {
        Config::first()->update($v);
    }

    // ───────────────────────── valeurs par défaut ─────────────────────────

    #[Test]
    public function new_config_row_gets_the_deck_defaults(): void
    {
        $c = Config::create(['use_noc' => 0])->fresh();

        $this->assertEquals([1.00, 1.15, 1.30], [(float) $c->position_first_multiplier, (float) $c->position_middle_multiplier, (float) $c->position_premium_multiplier]);
        $this->assertEquals([1.20, 1.30, 1.40], [(float) $c->target_region_multiplier, (float) $c->target_movie_multiplier, (float) $c->target_audience_multiplier]);
        $this->assertEquals([0.60, 1.00, 1.40, 1.80], [(float) $c->length_15_multiplier, (float) $c->length_30_multiplier, (float) $c->length_45_multiplier, (float) $c->length_60_multiplier]);
        $this->assertEquals([4, 0.90, 2.50, 1000.0], [(int) $c->volume_min_weeks, (float) $c->volume_multiplier, (float) $c->max_multiplier, (float) $c->min_outlet_week_price]);
    }

    #[Test]
    public function rules_fall_back_to_deck_defaults_without_config_row(): void
    {
        Config::query()->delete();
        $rules = new PricingRules(null);

        $this->assertSame(1.15, $rules->setting('position_middle_multiplier'));
        $this->assertSame(2.5, $rules->setting('max_multiplier'));
    }

    // ───────────────────────── prix de base sans multiplicateur ─────────────────────────

    #[Test]
    public function standard_campaign_has_no_multiplier(): void
    {
        $l = $this->line(); // 30 s, 1er segment, tous les sites, 1 semaine, hors saison

        $this->assertSame(1.0, (float) $l['multiplier']);
        $this->assertSame('all', $l['targeting_level']);
        $this->assertSame(7.0, $l['unit_price']);
        $this->assertSame(700.0, $l['amount']); // 100 x 7
    }

    // ───────────────────────── position ─────────────────────────

    #[Test]
    public function position_multiplier_follows_the_slot_tier(): void
    {
        foreach (['first' => 7.00, 'middle' => 8.05, 'premium' => 9.10] as $tier => $unit) {
            DB::table('slots')->where('id', $this->slotId)->update(['position_tier' => $tier]);
            $l = $this->line();
            $this->assertSame($unit, $l['unit_price'], $tier);
            $this->assertSame(round($unit * 100, 2), $l['amount'], $tier);
        }

        DB::table('slots')->where('id', $this->slotId)->update(['position_tier' => null]); // inconnue = 1er segment
        $this->assertSame(7.0, $this->line()['unit_price']);
    }

    #[Test]
    public function position_multipliers_are_configurable(): void
    {
        DB::table('slots')->where('id', $this->slotId)->update(['position_tier' => 'premium']);
        $this->cfg(['position_premium_multiplier' => 1.50]);

        $this->assertSame(10.5, $this->line()['unit_price']);
    }

    // ───────────────────────── ciblage ─────────────────────────

    #[Test]
    public function region_targeting_applies_when_campaign_covers_part_of_the_network(): void
    {
        DB::table('compaign_location')->insert(['compaign_id' => $this->campaign->id, 'location_id' => $this->loc1]);

        $l = $this->line();

        $this->assertSame('region', $l['targeting_level']);
        $this->assertEquals(1.2, $l['multiplier_targeting']);
        $this->assertSame(8.4, $l['unit_price']);
    }

    #[Test]
    public function campaign_covering_every_location_is_not_regional(): void
    {
        foreach ([$this->loc1, $this->loc2] as $loc) {
            DB::table('compaign_location')->insert(['compaign_id' => $this->campaign->id, 'location_id' => $loc]);
        }

        $this->assertSame('all', $this->line()['targeting_level']);
    }

    #[Test]
    public function chain_selection_alone_is_regional_when_the_chain_is_a_subset(): void
    {
        $other = $this->id('cinema_chains', ['name' => 'Chain B', 'ip_address' => 'http://b', 'username' => 'u', 'password' => 'p']);
        $this->id('locations', ['name' => 'Other', 'cpm' => 0, 'cinema_chain_id' => $other]);
        $this->id('compaign_cinema_chain', ['compaign_id' => $this->campaign->id, 'cinema_chain_id' => $this->chain]);

        $this->assertSame('region', $this->line()['targeting_level']);
    }

    #[Test]
    public function specific_movie_targeting_applies(): void
    {
        $movie = $this->id('master_movies', ['title' => 'Big Film']);
        $this->id('compaign_master_movie', ['compaign_id' => $this->campaign->id, 'master_movie_id' => $movie]);

        $l = $this->line();

        $this->assertSame('movie', $l['targeting_level']);
        $this->assertSame(9.1, $l['unit_price']);
    }

    #[Test]
    public function smart_audience_targeting_applies_and_wins_over_movie_and_region(): void
    {
        DB::table('compaign_location')->insert(['compaign_id' => $this->campaign->id, 'location_id' => $this->loc1]);
        $movie = $this->id('master_movies', ['title' => 'Big Film']);
        $this->id('compaign_master_movie', ['compaign_id' => $this->campaign->id, 'master_movie_id' => $movie]);
        $interest = $this->id('interests', ['name' => 'Sport']);
        DB::table('compaign_interest')->insert(['compaign_id' => $this->campaign->id, 'interest_id' => $interest]);

        $l = $this->line();

        $this->assertSame('audience', $l['targeting_level']);
        $this->assertEquals(1.4, $l['multiplier_targeting']); // un seul niveau retenu, pas de cumul
        $this->assertSame(9.8, $l['unit_price']);
    }

    #[Test]
    public function audience_targeting_via_target_types(): void
    {
        $tt = $this->id('target_types', ['name' => 'Youth', 'detail' => 'd', 'cpm' => 1]);
        DB::table('compaign_target_type')->insert(['compaign_id' => $this->campaign->id, 'target_type_id' => $tt]);

        $this->assertSame('audience', $this->line()['targeting_level']);
    }

    // ───────────────────────── durée ─────────────────────────

    #[Test]
    public function ad_length_bands(): void
    {
        $expected = [
            10 => 4.2,   // x0.6
            15 => 4.2,
            16 => 7.0,   // x1.0
            30 => 7.0,
            31 => 9.8,   // x1.4
            45 => 9.8,
            46 => 12.6,  // x1.8
            60 => 12.6,
            90 => 12.6,
        ];
        foreach ($expected as $seconds => $unit) {
            DB::table('dcp_creatives')->where('id', $this->dcpId)->update(['duration' => $seconds]);
            $this->assertSame($unit, $this->line()['unit_price'], "{$seconds}s");
        }
    }

    #[Test]
    public function unknown_duration_falls_back_to_campaign_duration_then_standard(): void
    {
        DB::table('dcp_creatives')->where('id', $this->dcpId)->update(['duration' => 0]);
        DB::table('compaigns')->where('id', $this->campaign->id)->update(['ad_duration' => 15]);
        $this->assertSame(4.2, $this->line($this->campaign->fresh())['unit_price']);

        DB::table('compaigns')->where('id', $this->campaign->id)->update(['ad_duration' => null]);
        $this->assertSame(7.0, $this->line($this->campaign->fresh())['unit_price']);
    }

    #[Test]
    public function each_ad_of_a_campaign_gets_its_own_length_multiplier(): void
    {
        $dcp2 = $this->id('dcp_creatives', ['name' => 'Long 60s', 'duration' => 60, 'uuid' => 'bbbb-2']);
        $this->id('compaign_slot_dcp', ['compaign_id' => $this->campaign->id, 'dcp_creative_id' => $dcp2, 'slot_id' => $this->slotId, 'position' => 2]);
        $this->id('compaign_playout_totals', [
            'compaign_id' => $this->campaign->id, 'dcp_creative_id' => $dcp2, 'cinema_chain_id' => $this->chain,
            'location_name' => 'KLCC', 'played' => 10, 'synced_until' => '2026-10-11 10:00:00', 'fetched_at' => now(),
        ]);

        $lines = collect(app(InvoiceGenerator::class)->lines($this->campaign))->keyBy('dcp_name');

        $this->assertSame(7.0, $lines['Coca 30s']['unit_price']);
        $this->assertSame(12.6, $lines['Long 60s']['unit_price']);
    }

    // ───────────────────────── saison ─────────────────────────

    #[Test]
    public function season_covering_the_whole_campaign_applies_fully(): void
    {
        $this->id('pricing_seasons', ['name' => 'Raya', 'start_date' => '2026-09-25', 'end_date' => '2026-10-20', 'multiplier' => 1.5]);

        $l = $this->line();

        $this->assertEquals(1.5, $l['multiplier_season']);
        $this->assertSame(10.5, $l['unit_price']);
    }

    #[Test]
    public function season_partly_overlapping_is_averaged_over_campaign_days(): void
    {
        // Campagne 01..07 oct. : 3 jours (05, 06, 07) à x1.4, 4 jours à x1.0 => (3 x 1.4 + 4) / 7 = 1.171
        $this->id('pricing_seasons', ['name' => 'Festive', 'start_date' => '2026-10-05', 'end_date' => '2026-10-30', 'multiplier' => 1.4]);

        $this->assertEqualsWithDelta(1.171, (float) $this->line()['multiplier_season'], 0.001);
    }

    #[Test]
    public function overlapping_seasons_take_the_highest_multiplier_per_day(): void
    {
        $this->id('pricing_seasons', ['name' => 'A', 'start_date' => '2026-10-01', 'end_date' => '2026-10-07', 'multiplier' => 1.2]);
        $this->id('pricing_seasons', ['name' => 'B', 'start_date' => '2026-10-01', 'end_date' => '2026-10-07', 'multiplier' => 1.5]);

        $this->assertEquals(1.5, $this->line()['multiplier_season']);
    }

    #[Test]
    public function season_outside_the_campaign_has_no_effect(): void
    {
        $this->id('pricing_seasons', ['name' => 'Later', 'start_date' => '2026-12-01', 'end_date' => '2026-12-31', 'multiplier' => 1.5]);

        $this->assertEquals(1.0, $this->line()['multiplier_season']);
    }

    // ───────────────────────── volume ─────────────────────────

    #[Test]
    public function volume_discount_starts_at_four_weeks(): void
    {
        $short = $this->makeCampaign('2026-10-01', '2026-10-27'); // 27 jours
        $this->played('KLCC', 100, $short);
        $long = $this->makeCampaign('2026-10-01', '2026-10-28');  // 28 jours = 4 semaines
        $this->played('KLCC', 100, $long);

        $this->assertEquals(1.0, $this->line($short)['multiplier_volume']);
        $this->assertSame(7.0, $this->line($short)['unit_price']);
        $this->assertEquals(0.9, $this->line($long)['multiplier_volume']);
        $this->assertSame(6.3, $this->line($long)['unit_price']);
    }

    #[Test]
    public function volume_threshold_and_multiplier_are_configurable(): void
    {
        $this->cfg(['volume_min_weeks' => 1, 'volume_multiplier' => 0.8]);

        $this->assertSame(5.6, $this->line()['unit_price']);
    }

    // ───────────────────────── cumul, plafond, exemples du deck ─────────────────────────

    #[Test]
    public function deck_example_sabah_premium_position(): void
    {
        // Deck : région (x1.2) x position premium (x1.3) sur base RM 7 = RM 10.92 par diffusion.
        DB::table('compaign_location')->insert(['compaign_id' => $this->campaign->id, 'location_id' => $this->loc1]);
        DB::table('slots')->where('id', $this->slotId)->update(['position_tier' => 'premium']);

        $l = $this->line();

        $this->assertEquals(1.56, $l['multiplier']);
        $this->assertSame(10.92, $l['unit_price']);
        $this->assertSame(1092.0, $l['amount']); // 100 x 10.92
    }

    #[Test]
    public function deck_example_one_movie_premium_position(): void
    {
        // Deck : film précis (x1.3) x premium (x1.3) = x1.69 => RM 11.83.
        $movie = $this->id('master_movies', ['title' => 'Big Film']);
        $this->id('compaign_master_movie', ['compaign_id' => $this->campaign->id, 'master_movie_id' => $movie]);
        DB::table('slots')->where('id', $this->slotId)->update(['position_tier' => 'premium']);

        $this->assertSame(11.83, $this->line()['unit_price']);
    }

    #[Test]
    public function combined_multiplier_is_capped_and_the_raw_value_is_kept(): void
    {
        // audience 1.4 x premium 1.3 x 60 s 1.8 = 3.276 => plafonné à 2.5.
        $interest = $this->id('interests', ['name' => 'Sport']);
        DB::table('compaign_interest')->insert(['compaign_id' => $this->campaign->id, 'interest_id' => $interest]);
        DB::table('slots')->where('id', $this->slotId)->update(['position_tier' => 'premium']);
        DB::table('dcp_creatives')->where('id', $this->dcpId)->update(['duration' => 60]);

        $l = $this->line();

        $this->assertEquals(3.276, $l['multiplier_raw']);
        $this->assertEquals(2.5, $l['multiplier']);
        $this->assertSame(17.5, $l['unit_price']); // 7 x 2.5
    }

    #[Test]
    public function cap_is_configurable_and_a_cap_of_zero_disables_it(): void
    {
        $interest = $this->id('interests', ['name' => 'Sport']);
        DB::table('compaign_interest')->insert(['compaign_id' => $this->campaign->id, 'interest_id' => $interest]);
        DB::table('slots')->where('id', $this->slotId)->update(['position_tier' => 'premium']);
        DB::table('dcp_creatives')->where('id', $this->dcpId)->update(['duration' => 60]);

        $this->cfg(['max_multiplier' => 2.00]);
        $this->assertSame(14.0, $this->line()['unit_price']);

        $this->cfg(['max_multiplier' => 0]);
        $this->assertSame(22.93, $this->line()['unit_price']); // 7 x 3.276 = 22.932
    }

    #[Test]
    public function discount_multipliers_below_one_are_not_capped_upwards(): void
    {
        DB::table('dcp_creatives')->where('id', $this->dcpId)->update(['duration' => 15]); // 0.6
        $long = $this->makeCampaign('2026-10-01', '2026-10-28');                          // x0.9
        $this->played('KLCC', 10, $long);

        $l = $this->line($long);

        $this->assertEquals(0.54, $l['multiplier']);
        $this->assertSame(3.78, $l['unit_price']);
    }

    #[Test]
    public function hall_type_prices_are_part_of_the_base_price_before_multipliers(): void
    {
        $imax = $this->id('hall_types', ['name' => 'IMAX', 'price' => 3]);
        DB::table('compaign_hall_type')->insert(['compaign_id' => $this->campaign->id, 'hall_type_id' => $imax]);
        DB::table('slots')->where('id', $this->slotId)->update(['position_tier' => 'premium']);

        $l = $this->line(); // (5 + 2 + 3) x 1.3

        $this->assertSame(13.0, $l['unit_price']);
    }

    #[Test]
    public function amount_is_plays_times_the_rounded_unit_price(): void
    {
        DB::table('slots')->where('id', $this->slotId)->update(['position_tier' => 'middle', 'price' => 2.33]);
        $this->cfg(['position_middle_multiplier' => 1.17]);

        $l = $this->line(); // (5 + 2.33) x 1.17 = 8.5761 => 8.58

        $this->assertSame(8.58, $l['unit_price']);
        $this->assertSame(858.0, $l['amount']);
    }

    // ───────────────────────── minimum par cinéma et par semaine ─────────────────────────

    #[Test]
    public function minimum_tops_up_a_cinema_below_the_weekly_minimum(): void
    {
        $this->cfg(['min_outlet_week_price' => 1000]);

        $lines = app(InvoiceGenerator::class)->lines($this->campaign); // KLCC : 700 < 1000 => +300
        $min   = collect($lines)->firstWhere('line_type', 'minimum');

        $this->assertCount(2, $lines);
        $this->assertSame(300.0, $min['amount']);
        $this->assertSame('KLCC', $min['location_name']);
        $this->assertSame(0, $min['played']);
        $this->assertSame($this->chain, $min['cinema_chain_id']);
        $this->assertSame(60.0, $min['share_percent']);
        $this->assertStringContainsString('1,000.00 x 1 week', $min['dcp_name']);
    }

    #[Test]
    public function no_top_up_when_the_cinema_already_reaches_the_minimum(): void
    {
        $this->cfg(['min_outlet_week_price' => 700]);

        $this->assertCount(1, app(InvoiceGenerator::class)->lines($this->campaign));
    }

    #[Test]
    public function minimum_scales_with_the_number_of_weeks_rounded_up(): void
    {
        $this->cfg(['min_outlet_week_price' => 1000]);
        $c = $this->makeCampaign('2026-10-01', '2026-10-10'); // 10 jours => 2 semaines
        $this->played('KLCC', 100, $c);

        $min = collect(app(InvoiceGenerator::class)->lines($c))->firstWhere('line_type', 'minimum');

        $this->assertSame(1300.0, $min['amount']); // 2 000 - 700
        $this->assertStringContainsString('x 2 weeks', $min['dcp_name']);
    }

    #[Test]
    public function minimum_is_checked_per_cinema_and_only_for_cinemas_that_played(): void
    {
        $this->cfg(['min_outlet_week_price' => 1000]);
        $this->played('Sunway', 200); // 200 x 7 = 1 400 >= 1 000 => pas de complément
        // Un cinéma sans diffusion n'apparaît pas du tout.
        $this->id('locations', ['name' => 'Idle', 'cpm' => 0, 'cinema_chain_id' => $this->chain]);

        $mins = collect(app(InvoiceGenerator::class)->lines($this->campaign))->where('line_type', 'minimum');

        $this->assertCount(1, $mins);
        $this->assertSame('KLCC', $mins->first()['location_name']);
    }

    #[Test]
    public function minimum_counts_all_ads_of_the_same_cinema_together(): void
    {
        $this->cfg(['min_outlet_week_price' => 1000]);
        $dcp2 = $this->id('dcp_creatives', ['name' => 'Second', 'duration' => 30, 'uuid' => 'cccc-3']);
        $this->id('compaign_slot_dcp', ['compaign_id' => $this->campaign->id, 'dcp_creative_id' => $dcp2, 'slot_id' => $this->slotId, 'position' => 2]);
        $this->id('compaign_playout_totals', [
            'compaign_id' => $this->campaign->id, 'dcp_creative_id' => $dcp2, 'cinema_chain_id' => $this->chain,
            'location_name' => 'KLCC', 'played' => 30, 'synced_until' => '2026-10-11 10:00:00', 'fetched_at' => now(),
        ]);

        $min = collect(app(InvoiceGenerator::class)->lines($this->campaign))->firstWhere('line_type', 'minimum');

        $this->assertSame(90.0, $min['amount']); // (100 + 30) x 7 = 910 => +90
    }

    #[Test]
    public function minimum_of_zero_is_disabled_and_no_plays_means_no_minimum(): void
    {
        $this->cfg(['min_outlet_week_price' => 0]);
        $this->assertCount(1, app(InvoiceGenerator::class)->lines($this->campaign));

        $this->cfg(['min_outlet_week_price' => 1000]);
        $empty = $this->makeCampaign('2026-10-01', '2026-10-07');
        $this->assertSame([], app(InvoiceGenerator::class)->lines($empty));
        $this->assertEquals(0, app(InvoiceGenerator::class)->generate($empty)->total_ht); // facture à 0
    }

    // ───────────────────────── facture : totaux et gel ─────────────────────────

    #[Test]
    public function invoice_totals_include_multipliers_minimum_and_the_8_percent_tax(): void
    {
        $this->cfg(['min_outlet_week_price' => 1000]);
        DB::table('slots')->where('id', $this->slotId)->update(['position_tier' => 'premium']);

        $invoice = app(InvoiceGenerator::class)->generate($this->campaign);
        // 100 x 9.10 = 910 ; minimum +90 => 1 000 HT ; SST 8 % => 1 080
        $this->assertEquals(1000.0, $invoice->total_ht);
        $this->assertEquals(8.0, $invoice->tax);
        $this->assertEquals(1080.0, $invoice->total_ttc);
        $this->assertSame(2, $invoice->lines()->count());
        $this->assertSame('minimum', $invoice->lines()->where('line_type', 'minimum')->value('line_type'));
    }

    #[Test]
    public function multipliers_are_frozen_on_the_invoice_lines(): void
    {
        DB::table('slots')->where('id', $this->slotId)->update(['position_tier' => 'premium']);
        $invoice = app(InvoiceGenerator::class)->generate($this->campaign);

        // L'admin change tout après la facture.
        $this->cfg(['position_premium_multiplier' => 2.0, 'max_multiplier' => 1.0, 'min_outlet_week_price' => 5000]);
        DB::table('slots')->where('id', $this->slotId)->update(['position_tier' => 'first']);
        $this->id('pricing_seasons', ['name' => 'X', 'start_date' => '2026-10-01', 'end_date' => '2026-10-07', 'multiplier' => 1.5]);

        $line = Invoice::find($invoice->id)->lines()->first();
        $this->assertEquals(1.3, $line->multiplier_position);
        $this->assertEquals(1.3, $line->multiplier);
        $this->assertEquals(9.10, $line->unit_price);
        $this->assertEquals(910.0, $line->amount);
        $this->assertEquals(910.0, Invoice::find($invoice->id)->total_ht);

        // Idempotent : pas de recalcul.
        $this->assertSame($invoice->id, app(InvoiceGenerator::class)->generate($this->campaign)->id);
        $this->assertEquals(910.0, Invoice::find($invoice->id)->total_ht);
    }

    #[Test]
    public function chain_gets_its_frozen_percent_on_the_minimum_line_too(): void
    {
        $this->cfg(['min_outlet_week_price' => 1000]);
        DB::table('cinema_chains')->where('id', $this->chain)->update(['revenue_share_percent' => 55]);

        $invoice = app(InvoiceGenerator::class)->generate($this->campaign);

        $this->assertEquals([55.0, 55.0], $invoice->lines()->pluck('share_percent')->map(fn ($v) => (float) $v)->all());
        $this->assertEquals([$this->chain, $this->chain], $invoice->lines()->pluck('cinema_chain_id')->map(fn ($v) => (int) $v)->all());
    }

    #[Test]
    public function tax_and_share_fall_back_to_deck_values_when_config_is_empty(): void
    {
        Config::query()->delete(); // aucune ligne de config : prix propres aux entités, le reste vient des valeurs du deck
        DB::table('locations')->where('id', $this->loc1)->update(['price' => 5]);
        DB::table('slots')->where('id', $this->slotId)->update(['price' => 2]);

        $invoice = app(InvoiceGenerator::class)->generate($this->campaign);

        $this->assertEquals(8.0, $invoice->tax);
        $this->assertEquals(60.0, $invoice->lines()->first()->share_percent);
        $this->assertEquals(1000.0, $invoice->total_ht); // 700 + minimum 300 (défaut du deck : RM 1 000 / semaine)
    }
}
