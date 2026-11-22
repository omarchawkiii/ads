<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\CinemaChain;
use App\Models\Config;
use App\Models\Invoice;
use App\Models\User;
use App\Services\CinemaChainRevenue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/** Run against a dedicated DB: DB_DATABASE=adsmart_campaign_test (APP_URL=http://localhost) */
final class CinemaChainPortalTest extends TestCase
{
    use RefreshDatabase;

    private int $chainA;
    private int $chainB;
    private int $chainC;
    private User $chainUser;     // chaînes A (% par défaut = 50) et B (% propre = 20)
    private User $otherChainUser; // chaîne C seulement
    private User $advertiser;
    private Invoice $summer;
    private Invoice $quiet;

    private function id(string $t, array $r): int
    {
        $now = now();
        return DB::table($t)->insertGetId($r + ['created_at' => $now, 'updated_at' => $now]);
    }

    private function line(Invoice $invoice, int $chain, string $chainName, string $location, int $played, float $amount): void
    {
        DB::table('invoice_lines')->insert([
            'invoice_id' => $invoice->id, 'dcp_name' => 'Ad', 'slot_name' => 'Slot A',
            'cinema_chain' => $chainName, 'cinema_chain_id' => $chain, 'location_name' => $location,
            'played' => $played, 'location_price' => 1, 'slot_price' => 1, 'hall_price' => 0, 'amount' => $amount,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        Config::query()->delete();
        Config::create(['use_noc' => 0, 'tax' => 6, 'default_revenue_share_percent' => 50, 'min_outlet_week_price' => 0]);

        $this->chainA = $this->id('cinema_chains', ['name' => 'Chain A', 'revenue_share_percent' => null]);
        $this->chainB = $this->id('cinema_chains', ['name' => 'Chain B', 'revenue_share_percent' => 20]);
        $this->chainC = $this->id('cinema_chains', ['name' => 'Chain C']);

        $this->chainUser      = User::factory()->create(['role' => 5]);
        $this->otherChainUser = User::factory()->create(['role' => 5]);
        $this->advertiser     = User::factory()->create(['role' => 2, 'name' => 'Secretadvertiser', 'email' => 'secret@advertiser.test']);
        $this->chainUser->cinemaChains()->sync([$this->chainA, $this->chainB]);
        $this->otherChainUser->cinemaChains()->sync([$this->chainC]);

        // Campagne « Summer Promo » sur A, B et C ; facture TTC 1908 (HT 1800), payée à 50 %.
        $summerId = $this->id('compaigns', ['name' => 'Summer Promo', 'start_date' => '2026-10-01', 'end_date' => '2026-10-10', 'budget' => 1, 'status' => 2, 'user_id' => $this->advertiser->id]);
        foreach ([$this->chainA, $this->chainB, $this->chainC] as $c) {
            DB::table('compaign_cinema_chain')->insert(['compaign_id' => $summerId, 'cinema_chain_id' => $c, 'created_at' => now(), 'updated_at' => now()]);
        }
        $this->summer = Invoice::create([
            'compaign_id' => $summerId, 'number' => 'INV-2026-00001', 'date' => '2026-10-12', 'due_date' => '2026-11-11',
            'status' => 'partially_paid', 'discount' => 0, 'tax' => 6, 'total_ht' => 1800, 'total_ttc' => 1908,
        ]);
        $this->summer->payments()->create(['amount' => 954]);
        $this->line($this->summer, $this->chainA, 'Chain A', 'KLCC', 100, 1000);
        $this->line($this->summer, $this->chainB, 'Chain B', 'Sunway', 50, 500);
        $this->line($this->summer, $this->chainC, 'Chain C', 'Penang', 30, 300);

        // Campagne « Quiet » sur A : aucune diffusion, facture à 0.
        $quietId = $this->id('compaigns', ['name' => 'Quiet', 'start_date' => '2026-09-01', 'end_date' => '2026-09-05', 'budget' => 1, 'status' => 2, 'user_id' => $this->advertiser->id]);
        DB::table('compaign_cinema_chain')->insert(['compaign_id' => $quietId, 'cinema_chain_id' => $this->chainA, 'created_at' => now(), 'updated_at' => now()]);
        $this->quiet = Invoice::create([
            'compaign_id' => $quietId, 'number' => 'INV-2026-00002', 'date' => '2026-09-10', 'due_date' => '2026-10-10',
            'status' => 'paid', 'discount' => 0, 'tax' => 6, 'total_ht' => 0, 'total_ttc' => 0,
        ]);
    }

    /* ---------------- calcul ---------------- */

    #[Test]
    public function revenue_is_ht_lines_of_own_chains_with_chain_or_default_share(): void
    {
        $r = app(CinemaChainRevenue::class)->report($this->chainUser);

        // A : 1000 × 50 % (défaut) = 500 ; B : 500 × 20 % = 100 ; C exclue.
        $this->assertSame(1500.0, $r['totals']['gross']);
        $this->assertSame(600.0, $r['totals']['share']);
        $this->assertSame(150, $r['totals']['plays']);
        $this->assertSame(2, $r['totals']['invoices']); // Summer + facture à 0

        $byChain = collect($r['by_chain'])->keyBy('name');
        $this->assertSame(500.0, $byChain['Chain A']['share']);
        $this->assertSame(100.0, $byChain['Chain B']['share']);
        $this->assertArrayNotHasKey('Chain C', $byChain->all());
    }

    #[Test]
    public function collected_is_proportional_to_payments_received(): void
    {
        $r = app(CinemaChainRevenue::class)->report($this->chainUser);

        // 954 payés sur 1908 TTC = 50 % : encaissé 300, reste 300.
        $this->assertSame(300.0, $r['totals']['collected']);
        $this->assertSame(300.0, $r['totals']['pending']);

        $summer = collect($r['invoices'])->firstWhere('number', 'INV-2026-00001');
        $this->assertSame(600.0, $summer['share']);
        $this->assertSame(300.0, $summer['collected']);
        $this->assertSame(300.0, $summer['pending']);
    }

    #[Test]
    public function zero_invoices_are_listed_with_zero_revenue(): void
    {
        $r = app(CinemaChainRevenue::class)->report($this->chainUser);

        $quiet = collect($r['invoices'])->firstWhere('number', 'INV-2026-00002');
        $this->assertNotNull($quiet);
        $this->assertSame('Quiet', $quiet['campaign']);
        $this->assertSame(0.0, $quiet['gross']);
        $this->assertSame(0.0, $quiet['share']);
    }

    #[Test]
    public function an_overpaid_invoice_never_collects_more_than_the_share(): void
    {
        $this->summer->payments()->create(['amount' => 5000]);

        $r = app(CinemaChainRevenue::class)->report($this->chainUser);

        $this->assertSame(600.0, $r['totals']['collected']);
        $this->assertSame(0.0, $r['totals']['pending']);
    }

    #[Test]
    public function discount_is_ignored_lines_are_taken_as_is(): void
    {
        $this->summer->update(['discount' => 900, 'discount_type' => 'fixed', 'discount_value' => 900, 'total_ttc' => 954]);

        $r = app(CinemaChainRevenue::class)->report($this->chainUser);

        $this->assertSame(1500.0, $r['totals']['gross']);
        $this->assertSame(600.0, $r['totals']['share']);
    }

    #[Test]
    public function date_and_chain_filters_apply_and_foreign_chain_gives_nothing(): void
    {
        $svc = app(CinemaChainRevenue::class);

        $octOnly = $svc->report($this->chainUser, '2026-10-01', '2026-10-31');
        $this->assertSame(1, $octOnly['totals']['invoices']);

        $onlyB = $svc->report($this->chainUser, null, null, $this->chainB);
        $this->assertSame(500.0, $onlyB['totals']['gross']);
        $this->assertSame(100.0, $onlyB['totals']['share']);

        // Chaîne C n'appartient pas à $chainUser : aucune donnée.
        $foreign = $svc->report($this->chainUser, null, null, $this->chainC);
        $this->assertSame([], $foreign['invoices']);
        $this->assertSame(0.0, $foreign['totals']['gross']);
    }

    #[Test]
    public function another_chain_user_only_sees_their_own_chain(): void
    {
        $r = app(CinemaChainRevenue::class)->report($this->otherChainUser);

        $this->assertSame(300.0, $r['totals']['gross']);
        $this->assertSame(150.0, $r['totals']['share']); // défaut 50 %
        $this->assertSame(['Penang'], array_column($r['by_location'], 'location'));
    }

    #[Test]
    public function user_without_chain_gets_an_empty_report(): void
    {
        $nobody = User::factory()->create(['role' => 5]);

        $r = app(CinemaChainRevenue::class)->report($nobody);

        $this->assertSame([], $r['invoices']);
        $this->assertSame([], $r['chains']);
        $this->actingAs($nobody)->get('/cinema-chain')->assertOk()->assertSee('No cinema chain is assigned');
    }

    /* ---------------- accès et affichage ---------------- */

    #[Test]
    public function cinema_chain_user_is_redirected_to_their_dashboard_and_sees_revenue(): void
    {
        $this->actingAs($this->chainUser)->get('/')->assertRedirect(route('cinema_chain.dashboard'));

        $this->actingAs($this->chainUser)->get('/cinema-chain')
            ->assertOk()->assertSee('Revenue dashboard')->assertSee('Chain A: 50%')->assertSee('Chain B: 20%');

        $this->actingAs($this->chainUser)->get('/cinema-chain/revenue')
            ->assertOk()->assertSee('Summer Promo')->assertSee('Quiet')->assertSee('INV-2026-00002');
    }

    #[Test]
    public function only_campaign_name_is_exposed_never_advertiser_or_other_chains(): void
    {
        $this->actingAs($this->chainUser)->get('/cinema-chain/revenue')
            ->assertOk()
            ->assertSee('Summer Promo')
            ->assertDontSee('Secretadvertiser')
            ->assertDontSee('secret@advertiser.test')
            ->assertDontSee('Penang')      // lieu de la chaîne C
            ->assertDontSee('Chain C');
    }

    #[Test]
    public function other_roles_and_guests_cannot_enter(): void
    {
        $this->get('/cinema-chain')->assertRedirect('/login');

        $this->actingAs($this->advertiser)->get('/cinema-chain')->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 1]))->get('/cinema-chain/revenue')->assertForbidden();

        // et un utilisateur chaîne n'accède ni à l'admin ni à l'espace annonceur
        $this->actingAs($this->chainUser)->get('/advertiser')->assertForbidden();
        $this->actingAs($this->chainUser)->get('/invoices')->assertForbidden();
        $this->actingAs($this->chainUser)->getJson("/invoices/{$this->summer->id}/show")->assertForbidden();
    }

    #[Test]
    public function revenue_page_filters_by_period(): void
    {
        $this->actingAs($this->chainUser)->get('/cinema-chain/revenue?from=2026-10-01&to=2026-10-31')
            ->assertOk()->assertSee('Summer Promo')->assertDontSee('INV-2026-00002');

        $this->actingAs($this->chainUser)->get('/cinema-chain/revenue?from=not-a-date')->assertSessionHasErrors('from');
    }

    #[Test]
    public function csv_export_contains_own_rows_only(): void
    {
        $csv = $this->actingAs($this->chainUser)->get('/cinema-chain/revenue/export')
            ->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8')->streamedContent();

        $this->assertStringContainsString('INV-2026-00001,"Summer Promo",2026-10-12,partially_paid,150,1500,600,300,300', $csv);
        $this->assertStringContainsString('INV-2026-00002', $csv);
        $this->assertStringNotContainsString('Secretadvertiser', $csv);
    }

    /* ---------------- profil et mot de passe ---------------- */

    #[Test]
    public function cinema_chain_user_sees_and_updates_their_profile(): void
    {
        $this->actingAs($this->chainUser)->get('/cinema-chain/profile')
            ->assertOk()->assertSee('My Profile')->assertSee('Chain A')->assertSee('Chain B')->assertDontSee('Chain C');

        $this->actingAs($this->chainUser)->putJson('/cinema-chain/profile', [
            'name' => 'Newname', 'last_name' => 'Last', 'username' => 'newuser', 'email' => 'new@chain.test',
        ])->assertOk();

        $this->assertSame('new@chain.test', $this->chainUser->fresh()->email);

        // e-mail déjà pris par un autre compte
        $this->actingAs($this->chainUser)->putJson('/cinema-chain/profile', [
            'name' => 'Newname', 'email' => $this->otherChainUser->email,
        ])->assertStatus(422)->assertJsonValidationErrors('email');
    }

    #[Test]
    public function password_change_requires_current_password_and_rules(): void
    {
        $user = User::factory()->create(['role' => 5, 'password' => \Illuminate\Support\Facades\Hash::make('oldpassword1')]);
        $put  = fn (array $p) => $this->actingAs($user)->putJson('/cinema-chain/profile/password', $p);

        $put(['current_password' => 'wrong-one', 'password' => 'newpassword1', 'password_confirmation' => 'newpassword1'])
            ->assertStatus(422)->assertJsonValidationErrors('current_password');
        $put(['current_password' => 'oldpassword1', 'password' => 'short', 'password_confirmation' => 'short'])
            ->assertStatus(422)->assertJsonValidationErrors('password');
        $put(['current_password' => 'oldpassword1', 'password' => 'newpassword1', 'password_confirmation' => 'different1'])
            ->assertStatus(422)->assertJsonValidationErrors('password');
        $put(['current_password' => 'oldpassword1', 'password' => 'oldpassword1', 'password_confirmation' => 'oldpassword1'])
            ->assertStatus(422)->assertJsonValidationErrors('password');
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('oldpassword1', $user->fresh()->password));

        $put(['current_password' => 'oldpassword1', 'password' => 'newpassword1', 'password_confirmation' => 'newpassword1'])->assertOk();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpassword1', $user->fresh()->password));
    }

    #[Test]
    public function password_endpoint_is_reserved_to_cinema_chain_users(): void
    {
        $payload = ['current_password' => 'password', 'password' => 'newpassword1', 'password_confirmation' => 'newpassword1'];

        $this->putJson('/cinema-chain/profile/password', $payload)->assertUnauthorized();
        $this->actingAs($this->advertiser)->putJson('/cinema-chain/profile/password', $payload)->assertForbidden();
    }

    /* ---------------- figer le % de partage et la taxe ---------------- */

    #[Test]
    public function generated_invoice_freezes_chain_share_and_tax(): void
    {
        Config::first()->update(['tax' => 6, 'default_revenue_share_percent' => 50, 'default_location_price' => 2, 'default_slot_price' => 1]);
        DB::table('cinema_chains')->where('id', $this->chainB)->update(['revenue_share_percent' => 20]);

        $slot = $this->id('slots', ['name' => 'S', 'cpm' => 0]);
        $dcp  = $this->id('dcp_creatives', ['name' => 'Ad', 'duration' => 10, 'uuid' => 'u-1']);
        $cid  = $this->id('compaigns', ['name' => 'Frozen', 'start_date' => '2026-10-01', 'end_date' => '2026-10-02', 'budget' => 1, 'status' => 2, 'user_id' => $this->advertiser->id]);
        $this->id('compaign_slot_dcp', ['compaign_id' => $cid, 'dcp_creative_id' => $dcp, 'slot_id' => $slot, 'position' => 1]);
        foreach ([$this->chainA => 'KLCC2', $this->chainB => 'Sunway2'] as $chain => $loc) {
            $this->id('locations', ['name' => $loc, 'cpm' => 0, 'cinema_chain_id' => $chain]);
            $this->id('compaign_playout_totals', ['compaign_id' => $cid, 'dcp_creative_id' => $dcp, 'cinema_chain_id' => $chain, 'location_name' => $loc, 'played' => 10, 'synced_until' => '2026-10-03 00:00:00', 'fetched_at' => now()]);
        }

        $invoice = app(\App\Services\InvoiceGenerator::class)->generate(\App\Models\Compaign::findOrFail($cid));

        $lines = $invoice->lines->keyBy('cinema_chain_id');
        $this->assertEquals(50, $lines[$this->chainA]->share_percent); // défaut
        $this->assertEquals(20, $lines[$this->chainB]->share_percent); // propre à la chaîne
        $this->assertEquals(6, $invoice->tax);

        // Plus tard : l'admin change les % et la taxe.
        DB::table('cinema_chains')->where('id', $this->chainB)->update(['revenue_share_percent' => 90]);
        Config::first()->update(['tax' => 10, 'default_revenue_share_percent' => 5]);

        $fresh = $invoice->fresh('lines');
        $this->assertEquals(20, $fresh->lines->firstWhere('cinema_chain_id', $this->chainB)->share_percent);
        $this->assertEquals(50, $fresh->lines->firstWhere('cinema_chain_id', $this->chainA)->share_percent);
        $this->assertEquals(6, $fresh->tax);
        $this->assertEquals(round($fresh->total_ht * 1.06, 2), (float) $fresh->total_ttc);
    }

    #[Test]
    public function frozen_percent_survives_later_changes_in_the_report(): void
    {
        // Ligne facturée à 50 % : figée, même si la chaîne passe ensuite à 90 % et le défaut à 5 %.
        DB::table('invoice_lines')->where('invoice_id', $this->summer->id)->where('cinema_chain_id', $this->chainA)->update(['share_percent' => 50]);
        DB::table('invoice_lines')->where('invoice_id', $this->summer->id)->where('cinema_chain_id', $this->chainB)->update(['share_percent' => 20]);
        DB::table('cinema_chains')->where('id', $this->chainA)->update(['revenue_share_percent' => 90]);
        DB::table('cinema_chains')->where('id', $this->chainB)->update(['revenue_share_percent' => 90]);
        Config::first()->update(['default_revenue_share_percent' => 5]);

        $r = app(CinemaChainRevenue::class)->report($this->chainUser);

        $this->assertSame(600.0, $r['totals']['share']); // 1000 × 50 % + 500 × 20 %, inchangé
        $summer = collect($r['invoices'])->firstWhere('number', 'INV-2026-00001');
        $this->assertSame(40.0, $summer['share_percent']); // 600 / 1500

        // Les chaînes affichent le % actuel (pour les prochaines factures).
        $this->assertSame([90.0, 90.0], array_column($r['chains'], 'percent'));
    }

    #[Test]
    public function discount_keeps_using_the_tax_frozen_on_the_invoice(): void
    {
        Config::first()->update(['tax' => 20]); // la taxe de la config change après la facture (6 %)
        $admin = User::factory()->create(['role' => 1]);

        // 10 % de 1800 = 180 ; net 1620 ; taxe 6 % (figée) => 1717.20
        $this->actingAs($admin)->putJson("/invoices/{$this->summer->id}/discount", ['type' => 'percent', 'value' => 10, 'reason' => 'x'])
            ->assertOk()->assertJsonPath('total', 1717.2);
    }

    /* ---------------- administration ---------------- */

    #[Test]
    public function admin_creates_cinema_chain_user_with_required_chains(): void
    {
        $admin = User::factory()->create(['role' => 1]);
        $base  = ['name' => 'Ali', 'last_name' => 'Chain', 'username' => 'alichain', 'email' => 'ali@chain.test', 'password' => 'secret123', 'role' => 5];

        $this->actingAs($admin)->postJson('/users', $base)->assertStatus(422)->assertJsonValidationErrors('cinema_chain_ids');
        $this->actingAs($admin)->postJson('/users', $base + ['cinema_chain_ids' => [999999]])->assertStatus(422);

        $this->actingAs($admin)->postJson('/users', $base + ['cinema_chain_ids' => [$this->chainA, $this->chainB]])->assertCreated();

        $created = User::where('email', 'ali@chain.test')->first();
        $this->assertSame(5, (int) $created->role);
        $this->assertEqualsCanonicalizing([$this->chainA, $this->chainB], $created->cinemaChains()->pluck('cinema_chains.id')->all());
    }

    #[Test]
    public function other_roles_do_not_need_chains_at_creation(): void
    {
        $admin = User::factory()->create(['role' => 1]);

        $this->actingAs($admin)->postJson('/users', [
            'name' => 'Ad', 'last_name' => 'V', 'username' => 'adv1', 'email' => 'adv1@test.test', 'password' => 'secret123', 'role' => 2, 'advertiser_type' => 'direct',
        ])->assertCreated();
    }

    #[Test]
    public function admin_sets_chain_share_and_default_share(): void
    {
        $admin = User::factory()->create(['role' => 1]);

        $this->actingAs($admin)->putJson("/cinema-chains/{$this->chainA}", ['name' => 'Chain A', 'revenue_share_percent' => 35.5])->assertOk();
        $this->assertEquals(35.5, CinemaChain::find($this->chainA)->revenue_share_percent);

        // La part propre prend le pas sur le défaut dans le rapport.
        $r = app(CinemaChainRevenue::class)->report($this->chainUser);
        $this->assertSame(355.0, collect($r['by_chain'])->firstWhere('name', 'Chain A')['share']);

        // Vide = retour au défaut.
        $this->actingAs($admin)->putJson("/cinema-chains/{$this->chainA}", ['name' => 'Chain A', 'revenue_share_percent' => null])->assertOk();
        $this->assertNull(CinemaChain::find($this->chainA)->revenue_share_percent);

        $this->actingAs($admin)->postJson('/configs/prices', [
            'default_location_price' => 1, 'default_slot_price' => 1, 'default_hall_type_price' => 1, 'default_revenue_share_percent' => 70,
        ])->assertOk();
        $this->assertEquals(70, Config::first()->default_revenue_share_percent);

        $this->actingAs($admin)->postJson('/configs/prices', [
            'default_location_price' => 1, 'default_slot_price' => 1, 'default_hall_type_price' => 1, 'default_revenue_share_percent' => 120,
        ])->assertStatus(422)->assertJsonValidationErrors('default_revenue_share_percent');
    }
}
