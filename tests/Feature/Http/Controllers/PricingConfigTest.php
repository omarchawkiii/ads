<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Config;
use App\Models\Invoice;
use App\Models\PricingSeason;
use App\Models\Slot;
use App\Models\User;
use App\Services\CinemaChainRevenue;
use App\Services\InvoiceGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/** Run against a dedicated DB: DB_DATABASE=adsmart_campaign_test (APP_URL=http://localhost) */
final class PricingConfigTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private function id(string $t, array $r): int
    {
        $now = now();
        return DB::table($t)->insertGetId($r + ['created_at' => $now, 'updated_at' => $now]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 1]);
        Config::query()->delete();
        Config::create(['use_noc' => 0, 'tax' => 8, 'default_location_price' => 5, 'default_slot_price' => 2, 'min_outlet_week_price' => 0]);
    }

    private function payload(array $over = []): array
    {
        return $over + [
            'position_first_multiplier' => 1, 'position_middle_multiplier' => 1.2, 'position_premium_multiplier' => 1.4,
            'target_region_multiplier' => 1.1, 'target_movie_multiplier' => 1.25, 'target_audience_multiplier' => 1.5,
            'length_15_multiplier' => 0.5, 'length_30_multiplier' => 1, 'length_45_multiplier' => 1.3, 'length_60_multiplier' => 1.7,
            'volume_min_weeks' => 6, 'volume_multiplier' => 0.85, 'max_multiplier' => 2.2, 'min_outlet_week_price' => 800,
        ];
    }

    // ───────────── multiplicateurs : réglage admin ─────────────

    #[Test]
    public function admin_saves_the_multipliers(): void
    {
        $this->actingAs($this->admin)->postJson('/configs/multipliers', $this->payload())->assertOk();

        $c = Config::first();
        $this->assertEquals(1.4, $c->position_premium_multiplier);
        $this->assertEquals(0.5, $c->length_15_multiplier);
        $this->assertEquals(6, $c->volume_min_weeks);
        $this->assertEquals(2.2, $c->max_multiplier);
        $this->assertEquals(800, $c->min_outlet_week_price);
    }

    #[Test]
    public function multiplier_validation_rejects_bad_values(): void
    {
        $this->actingAs($this->admin);

        $this->postJson('/configs/multipliers', $this->payload(['position_middle_multiplier' => 0]))->assertStatus(422)->assertJsonValidationErrors('position_middle_multiplier');
        $this->postJson('/configs/multipliers', $this->payload(['length_15_multiplier' => -1]))->assertStatus(422)->assertJsonValidationErrors('length_15_multiplier');
        $this->postJson('/configs/multipliers', $this->payload(['volume_min_weeks' => 0]))->assertStatus(422)->assertJsonValidationErrors('volume_min_weeks');
        $this->postJson('/configs/multipliers', $this->payload(['max_multiplier' => 'abc']))->assertStatus(422)->assertJsonValidationErrors('max_multiplier');
        $this->postJson('/configs/multipliers', $this->payload(['min_outlet_week_price' => -5]))->assertStatus(422)->assertJsonValidationErrors('min_outlet_week_price');

        $missing = $this->payload();
        unset($missing['target_movie_multiplier']);
        $this->postJson('/configs/multipliers', $missing)->assertStatus(422)->assertJsonValidationErrors('target_movie_multiplier');
    }

    #[Test]
    public function only_admins_can_change_pricing_rules(): void
    {
        $adv = User::factory()->create(['role' => 2]);
        $season = PricingSeason::create(['name' => 'S', 'start_date' => '2026-01-01', 'end_date' => '2026-01-10', 'multiplier' => 1.2]);

        $this->actingAs($adv);
        $this->postJson('/configs/multipliers', $this->payload())->assertForbidden();
        $this->postJson('/configs/seasons', ['name' => 'x', 'start_date' => '2026-01-01', 'end_date' => '2026-01-02', 'multiplier' => 1.2])->assertForbidden();
        $this->deleteJson("/configs/seasons/{$season->id}")->assertForbidden();

        $this->assertEquals(1.15, (float) Config::first()->fresh()->position_middle_multiplier); // inchangé (défaut)
        $this->assertSame(1, PricingSeason::count());
    }

    #[Test]
    public function guests_are_redirected(): void
    {
        auth()->logout();
        $this->postJson('/configs/multipliers', $this->payload())->assertUnauthorized();
    }

    #[Test]
    public function config_page_shows_the_pricing_rules_tab_with_saved_values_and_seasons(): void
    {
        PricingSeason::create(['name' => 'Chinese New Year', 'start_date' => '2027-02-01', 'end_date' => '2027-02-10', 'multiplier' => 1.5]);
        Config::first()->update(['position_premium_multiplier' => 1.45]);

        $this->actingAs($this->admin)->get('/configs')->assertOk()
            ->assertSee('Pricing Rules')
            ->assertSee('value="1.45"', false)
            ->assertSee('Chinese New Year');
    }

    // ───────────── saisons ─────────────

    #[Test]
    public function admin_manages_seasons(): void
    {
        $this->actingAs($this->admin);

        $id = $this->postJson('/configs/seasons', ['name' => 'Raya', 'start_date' => '2027-03-01', 'end_date' => '2027-03-10', 'multiplier' => 1.4])
            ->assertCreated()->json('season.id');
        $this->assertDatabaseHas('pricing_seasons', ['id' => $id, 'name' => 'Raya', 'multiplier' => 1.40]);

        $this->putJson("/configs/seasons/{$id}", ['name' => 'Hari Raya', 'start_date' => '2027-03-02', 'end_date' => '2027-03-12', 'multiplier' => 1.3])->assertOk();
        $this->assertDatabaseHas('pricing_seasons', ['id' => $id, 'name' => 'Hari Raya', 'multiplier' => 1.30]);

        $this->deleteJson("/configs/seasons/{$id}")->assertOk();
        $this->assertDatabaseMissing('pricing_seasons', ['id' => $id]);
    }

    #[Test]
    public function season_validation(): void
    {
        $this->actingAs($this->admin);
        $ok = ['name' => 'X', 'start_date' => '2027-03-01', 'end_date' => '2027-03-10', 'multiplier' => 1.4];

        $this->postJson('/configs/seasons', ['end_date' => '2027-03-01'] + $ok)->assertStatus(201); // fin = début : accepté
        $this->postJson('/configs/seasons', ['end_date' => '2027-02-28'] + $ok)->assertStatus(422)->assertJsonValidationErrors('end_date');
        $this->postJson('/configs/seasons', ['name' => ''] + $ok)->assertStatus(422)->assertJsonValidationErrors('name');
        $this->postJson('/configs/seasons', ['multiplier' => 0] + $ok)->assertStatus(422)->assertJsonValidationErrors('multiplier');
        $this->postJson('/configs/seasons', ['start_date' => 'nope'] + $ok)->assertStatus(422)->assertJsonValidationErrors('start_date');
        $this->putJson('/configs/seasons/9999', $ok)->assertNotFound();
        $this->deleteJson('/configs/seasons/9999')->assertNotFound();
    }

    // ───────────── slots : position ─────────────

    #[Test]
    public function slot_creation_stores_the_position_tier(): void
    {
        $this->actingAs($this->admin)->postJson('/slots', [
            'template_name' => 'T1',
            'slots' => [
                ['type' => 'ads', 'name' => 'Premium', 'max_duration' => 60, 'max_ad_slot' => 2, 'position_tier' => 'premium', 'ad_slot_types' => [0]],
                ['type' => 'ads', 'name' => 'Default', 'max_duration' => 60, 'max_ad_slot' => 2, 'ad_slot_types' => [0]],
                ['type' => 'segment', 'segment_select' => 'Segment 1'],
            ],
        ])->assertCreated();

        $this->assertSame('premium', Slot::where('name', 'Premium')->value('position_tier'));
        $this->assertSame('first', Slot::where('name', 'Default')->value('position_tier')); // défaut : 1er segment
        $this->assertNull(Slot::where('name', 'Segment 1')->value('position_tier'));
    }

    #[Test]
    public function slot_update_changes_the_position_tier_and_rejects_unknown_values(): void
    {
        $this->actingAs($this->admin)->postJson('/slots', [
            'template_name' => 'T2',
            'slots' => [['type' => 'ads', 'name' => 'S', 'max_duration' => 60, 'max_ad_slot' => 1, 'ad_slot_types' => [0]]],
        ])->assertCreated();
        $slot = Slot::where('name', 'S')->first();

        $this->putJson("/slots/{$slot->template_slot_id}", [
            'template_name' => 'T2',
            'slots' => [['id' => $slot->id, 'type' => 'ads', 'name' => 'S', 'max_duration' => 60, 'max_ad_slot' => 1, 'position_tier' => 'middle', 'ad_slot_types' => [0]]],
        ])->assertOk();
        $this->assertSame('middle', $slot->fresh()->position_tier);

        $this->putJson("/slots/{$slot->template_slot_id}", [
            'template_name' => 'T2',
            'slots' => [['id' => $slot->id, 'type' => 'ads', 'name' => 'S', 'max_duration' => 60, 'max_ad_slot' => 1, 'position_tier' => 'last', 'ad_slot_types' => [0]]],
        ])->assertStatus(422);
        $this->assertSame('middle', $slot->fresh()->position_tier);
    }

    // ───────────── facture : affichage ─────────────

    /** Campagne avec 1 diffusion (100 x 7 x 1.3 = 910) + minimum 1 000 (=> +90) ; retourne la facture. */
    private function invoiceWithMultipliersAndMinimum(): Invoice
    {
        Config::first()->update(['min_outlet_week_price' => 1000]);
        $owner = User::factory()->create(['role' => 2]);
        $chain = $this->id('cinema_chains', ['name' => 'Chain A', 'ip_address' => 'http://noc.test', 'username' => 'u', 'password' => 'p']);
        $this->id('locations', ['name' => 'KLCC', 'cpm' => 0, 'cinema_chain_id' => $chain]);
        $slot = $this->id('slots', ['name' => 'Premium slot', 'cpm' => 0, 'position_tier' => 'premium']);
        $dcp  = $this->id('dcp_creatives', ['name' => 'Coca 30s', 'duration' => 30, 'uuid' => 'aaaa-1']);
        $cid  = $this->id('compaigns', ['name' => 'Raya', 'start_date' => '2026-10-01', 'end_date' => '2026-10-07', 'budget' => 500, 'status' => 2, 'user_id' => $owner->id]);
        $this->id('compaign_slot_dcp', ['compaign_id' => $cid, 'dcp_creative_id' => $dcp, 'slot_id' => $slot, 'position' => 1]);
        $this->id('compaign_playout_totals', [
            'compaign_id' => $cid, 'dcp_creative_id' => $dcp, 'cinema_chain_id' => $chain, 'location_name' => 'KLCC',
            'played' => 100, 'synced_until' => '2026-10-08 02:00:00', 'fetched_at' => now(),
        ]);

        return app(InvoiceGenerator::class)->generate(\App\Models\Compaign::findOrFail($cid));
    }

    #[Test]
    public function invoice_details_json_exposes_the_frozen_multipliers(): void
    {
        $invoice = $this->invoiceWithMultipliersAndMinimum();

        $lines = collect($this->actingAs($this->admin)->getJson("/invoices/{$invoice->id}/show")->assertOk()->json('invoice.lines'));

        $plays = $lines->firstWhere('line_type', 'plays');
        $this->assertEquals(1.3, $plays['multiplier']);
        $this->assertEquals(1.3, $plays['multiplier_position']);
        $this->assertEquals(9.1, $plays['unit_price']);
        $this->assertSame('all', $plays['targeting_level']);
        $this->assertEquals(910, $plays['amount']);
        $this->assertEquals(90, $lines->firstWhere('line_type', 'minimum')['amount']);
    }

    #[Test]
    public function invoice_pdf_renders_with_multipliers_and_minimum_line(): void
    {
        $invoice = $this->invoiceWithMultipliersAndMinimum();

        $res = $this->actingAs($this->admin)->get("/invoices/{$invoice->id}/download")->assertOk();

        $this->assertStringContainsString('application/pdf', $res->headers->get('content-type'));
    }

    #[Test]
    public function invoice_pdf_view_shows_the_multiplier_breakdown(): void
    {
        $invoice = $this->invoiceWithMultipliersAndMinimum();
        $d = app(\App\Services\InvoiceDetails::class)->build($invoice);

        $html = view('admin.invoices.pdf', ['invoice' => $invoice->fresh(), 'd' => $d])->render();

        $this->assertStringContainsString('x1.3', $html);
        $this->assertStringContainsString('position x1.3', $html);
        $this->assertStringContainsString('Minimum charge', $html);
        $this->assertStringContainsString('9.10', $html);
        $this->assertStringContainsString('910.00', $html);
    }

    #[Test]
    public function legacy_lines_without_multipliers_still_render(): void
    {
        $invoice = Invoice::create([
            'compaign_id' => null, 'number' => 'INV-2026-09999', 'date' => '2026-01-01', 'due_date' => '2026-01-31',
            'status' => 'unpaid', 'discount' => 0, 'tax' => 6, 'total_ht' => 100, 'total_ttc' => 106,
        ]);
        // Ligne créée avec l'ancien schéma : prix unitaire inconnu, multiplicateurs par défaut.
        $invoice->lines()->create([
            'dcp_name' => 'Old', 'location_name' => 'KLCC', 'played' => 10, 'location_price' => 5, 'slot_price' => 3,
            'hall_price' => 2, 'amount' => 100,
        ]);

        $line = $invoice->lines()->first()->fresh();
        $this->assertSame('plays', $line->line_type);
        $this->assertEquals(1, $line->multiplier);

        $html = view('admin.invoices.pdf', ['invoice' => $invoice->fresh(), 'd' => app(\App\Services\InvoiceDetails::class)->build($invoice)])->render();
        $this->assertStringContainsString('10.00', $html); // prix par diffusion = 5 + 3 + 2
    }

    // ───────────── revenu des chaînes ─────────────

    #[Test]
    public function chain_revenue_includes_the_minimum_line(): void
    {
        $invoice = $this->invoiceWithMultipliersAndMinimum(); // 910 + 90 = 1 000 HT
        $chain = DB::table('cinema_chains')->value('id');
        DB::table('cinema_chains')->where('id', $chain)->update(['revenue_share_percent' => 60]);
        DB::table('invoice_lines')->where('invoice_id', $invoice->id)->update(['share_percent' => 60]);
        $user = User::factory()->create(['role' => 5]);
        $user->cinemaChains()->attach($chain);

        $report = app(CinemaChainRevenue::class)->report($user);

        $this->assertEquals(1000.0, $report['totals']['gross']);
        $this->assertEquals(600.0, $report['totals']['share']); // 60 % du HT
        $this->assertEquals(100, $report['totals']['plays']);   // la ligne minimum n'ajoute aucune diffusion
    }

    // ───────────── migration : alignement sur le deck ─────────────

    #[Test]
    public function migration_aligns_old_defaults_only_and_keeps_custom_settings(): void
    {
        $migration = require base_path('database/migrations/2026_11_22_100000_add_pricing_multipliers.php');
        $migration->down();

        // Config qui porte encore les anciens défauts (6 %, 100 %, prix 0) puis une config personnalisée.
        DB::table('configs')->delete();
        DB::table('configs')->insert(['use_noc' => 0, 'tax' => 6, 'default_revenue_share_percent' => 100, 'default_location_price' => 0, 'default_slot_price' => 0, 'default_hall_type_price' => 0, 'created_at' => now(), 'updated_at' => now()]);
        $migration->up();
        $old = DB::table('configs')->first();
        $this->assertEquals(8, $old->tax);
        $this->assertEquals(60, $old->default_revenue_share_percent);
        $this->assertEquals([5, 2, 0], [(float) $old->default_location_price, (float) $old->default_slot_price, (float) $old->default_hall_type_price]);
        $this->assertEquals(2.5, $old->max_multiplier);

        $migration->down();
        DB::table('configs')->delete();
        DB::table('configs')->insert(['use_noc' => 0, 'tax' => 10, 'default_revenue_share_percent' => 70, 'default_location_price' => 3, 'default_slot_price' => 1, 'default_hall_type_price' => 0, 'created_at' => now(), 'updated_at' => now()]);
        $migration->up();
        $custom = DB::table('configs')->first();
        $this->assertEquals([10, 70, 3, 1], [(float) $custom->tax, (float) $custom->default_revenue_share_percent, (float) $custom->default_location_price, (float) $custom->default_slot_price]);

        DB::table('configs')->delete(); // le DDL a validé la transaction du test : nettoyage manuel
    }
}
