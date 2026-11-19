<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/** Run against a dedicated DB: DB_DATABASE=adsmart_campaign_test (APP_URL=http://localhost) */
final class InvoiceDetailsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $owner;
    private Invoice $invoice;

    private function id(string $t, array $r): int
    {
        $now = now();
        return DB::table($t)->insertGetId($r + ['created_at' => $now, 'updated_at' => $now]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 1]);
        $this->owner = User::factory()->create(['role' => 2, 'name' => 'Aisha', 'last_name' => 'Rahman', 'email' => 'aisha@brand.test']);

        $chain    = $this->id('cinema_chains', ['name' => 'Chain A']);
        $location = $this->id('locations', ['name' => 'KLCC', 'cpm' => 0, 'cinema_chain_id' => $chain]);
        $slot     = $this->id('slots', ['name' => 'Trailers', 'cpm' => 0]);
        $hall     = $this->id('hall_types', ['name' => 'IMAX']);
        $customer = $this->id('customers', ['name' => 'Brand Sdn Bhd', 'brand_name' => 'Brandy', 'city' => 'KL', 'email' => 'billing@brand.test', 'user_id' => $this->owner->id]);
        $dcp      = $this->id('dcp_creatives', ['name' => 'Coca 30s', 'duration' => 30, 'uuid' => 'aaaa-1', 'customer_id' => $customer]);

        $cid = $this->id('compaigns', [
            'name' => 'Raya', 'start_date' => '2026-10-01', 'end_date' => '2026-10-10',
            'budget' => 500, 'status' => 2, 'user_id' => $this->owner->id,
        ]);
        DB::table('compaign_cinema_chain')->insert(['compaign_id' => $cid, 'cinema_chain_id' => $chain, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('compaign_location')->insert(['compaign_id' => $cid, 'location_id' => $location]);
        DB::table('compaign_hall_type')->insert(['compaign_id' => $cid, 'hall_type_id' => $hall]);
        $this->id('compaign_slot_dcp', ['compaign_id' => $cid, 'dcp_creative_id' => $dcp, 'slot_id' => $slot, 'position' => 1]);

        $this->id('compaign_playout_totals', [
            'compaign_id' => $cid, 'dcp_creative_id' => $dcp, 'cinema_chain_id' => $chain, 'location_name' => 'KLCC',
            'played' => 10, 'incomplete' => 1, 'failed' => 0, 'played_seconds' => 300,
            'synced_until' => '2026-10-11 02:00:00', 'fetched_at' => now(),
        ]);
        $this->id('compaign_playouts', [
            'compaign_id' => $cid, 'dcp_creative_id' => $dcp, 'cinema_chain_id' => $chain, 'location_name' => 'KLCC',
            'date' => '2026-10-02', 'played' => 10, 'synced_until' => '2026-10-11 02:00:00',
        ]);

        $this->invoice = Invoice::create([
            'number' => 'INV-2026-00001', 'compaign_id' => $cid, 'date' => '2026-10-12', 'due_date' => '2026-11-11',
            'status' => 'partially_paid', 'discount' => 0, 'tax' => 6, 'total_ht' => 100, 'total_ttc' => 106,
        ]);
        $this->invoice->lines()->create([
            'dcp_name' => 'Coca 30s', 'slot_name' => 'Trailers', 'cinema_chain' => 'Chain A', 'location_name' => 'KLCC',
            'played' => 10, 'location_price' => 5, 'slot_price' => 3, 'hall_price' => 2, 'amount' => 100,
        ]);
        $this->invoice->payments()->create(['amount' => 40]);
    }

    #[Test]
    public function admin_gets_the_full_real_detail(): void
    {
        $this->actingAs($this->admin)->getJson("/invoices/{$this->invoice->id}/show")
            ->assertOk()
            ->assertJsonPath('advertiser.name', 'Aisha Rahman')
            ->assertJsonPath('advertiser.email', 'aisha@brand.test')
            ->assertJsonPath('customers.0.name', 'Brand Sdn Bhd')
            ->assertJsonPath('campaign.name', 'Raya')
            ->assertJsonPath('campaign.days', 10)
            ->assertJsonPath('campaign.cinema_chains.0', 'Chain A')
            ->assertJsonPath('campaign.locations.0.name', 'KLCC')
            ->assertJsonPath('campaign.slots', [])   // aucun slot lié via compaign_slot : seul compaign_slot_dcp existe
            ->assertJsonPath('campaign.hall_types.0', 'IMAX')
            ->assertJsonPath('proof_of_play.dcps.0.played', 10)
            ->assertJsonPath('proof_of_play.dcps.0.by_location.0.by_day.0.date', '2026-10-02')
            ->assertJsonPath('invoice.lines.0.amount', '100.00')
            ->assertJsonPath('totals.tax_amount', 6)
            ->assertJsonPath('totals.paid', 40)
            ->assertJsonPath('totals.remaining', 66)
            ->assertJsonPath('totals.overdue', true);
    }

    #[Test]
    public function advertiser_sees_own_invoice_but_not_others(): void
    {
        $this->actingAs($this->owner)->getJson("/advertiser/invoices/{$this->invoice->id}/show")
            ->assertOk()->assertJsonPath('campaign.name', 'Raya');

        $other = User::factory()->create(['role' => 2]);
        $this->actingAs($other)->getJson("/advertiser/invoices/{$this->invoice->id}/show")->assertForbidden();
    }

    #[Test]
    public function pdf_renders_with_real_data(): void
    {
        $res = $this->actingAs($this->admin)->get("/invoices/{$this->invoice->id}/download")->assertOk();

        $this->assertStringContainsString('application/pdf', $res->headers->get('content-type'));
        $this->assertStringStartsWith('%PDF', $res->getContent());
    }
}
