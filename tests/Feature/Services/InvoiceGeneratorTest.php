<?php

namespace Tests\Feature\Services;

use App\Models\Compaign;
use App\Models\Config;
use App\Models\User;
use App\Services\InvoiceGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/** Run against a dedicated DB: DB_DATABASE=adsmart_campaign_test (APP_URL=http://localhost) */
final class InvoiceGeneratorTest extends TestCase
{
    use RefreshDatabase;

    private int $chain;
    private int $slotId;
    private int $dcpId;
    private Compaign $campaign;

    private function id(string $t, array $r): int
    {
        $now = now();
        return DB::table($t)->insertGetId($r + ['created_at' => $now, 'updated_at' => $now]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        Config::query()->delete();
        Config::create(['use_noc' => 0, 'tax' => 6, 'default_location_price' => 2.00, 'default_slot_price' => 1.00, 'default_hall_type_price' => 0.50]);

        $this->chain  = $this->id('cinema_chains', ['name' => 'Chain A', 'ip_address' => 'http://noc.test', 'username' => 'u', 'password' => 'p']);
        $this->slotId = $this->id('slots', ['name' => 'Trailers', 'cpm' => 0, 'price' => 3.00]);
        $this->dcpId  = $this->id('dcp_creatives', ['name' => 'Coca 30s', 'duration' => 30, 'uuid' => 'aaaa-1']);

        $cid = $this->id('compaigns', [
            'name' => 'C', 'start_date' => '2026-10-01', 'end_date' => '2026-10-10',
            'budget' => 500, 'status' => 2, 'user_id' => User::factory()->create(['role' => 2])->id,
        ]);
        $this->id('compaign_slot_dcp', ['compaign_id' => $cid, 'dcp_creative_id' => $this->dcpId, 'slot_id' => $this->slotId, 'position' => 1]);

        $this->campaign = Compaign::findOrFail($cid);
    }

    private function played(string $location, int $played, ?int $chain = null): void
    {
        $this->id('compaign_playout_totals', [
            'compaign_id' => $this->campaign->id, 'dcp_creative_id' => $this->dcpId,
            'cinema_chain_id' => $chain ?? $this->chain, 'location_name' => $location,
            'played' => $played, 'synced_until' => '2026-10-11 10:00:00', 'fetched_at' => now(),
        ]);
    }

    #[Test]
    public function amount_is_plays_times_location_price_plus_slot_price(): void
    {
        // KLCC : prix propre 5 ; Sunway : pas de prix => défaut 2. Slot : prix propre 3.
        $this->id('locations', ['name' => 'KLCC', 'cpm' => 0, 'price' => 5.00, 'cinema_chain_id' => $this->chain]);
        $this->id('locations', ['name' => 'Sunway', 'cpm' => 0, 'cinema_chain_id' => $this->chain]);
        $this->played('KLCC', 100);   // 100 × (5 + 3) = 800
        $this->played('Sunway', 40);  //  40 × (2 + 3) = 200

        $lines = collect(app(InvoiceGenerator::class)->lines($this->campaign))->keyBy('location_name');

        $this->assertSame(800.0, $lines['KLCC']['amount']);
        $this->assertSame(200.0, $lines['Sunway']['amount']);
        $this->assertSame('Trailers', $lines['KLCC']['slot_name']);
    }

    #[Test]
    public function slot_without_price_uses_config_default(): void
    {
        DB::table('slots')->where('id', $this->slotId)->update(['price' => null]); // défaut 1
        $this->id('locations', ['name' => 'KLCC', 'cpm' => 0, 'price' => 5.00, 'cinema_chain_id' => $this->chain]);
        $this->played('KLCC', 10); // 10 × (5 + 1) = 60

        $this->assertSame(60.0, app(InvoiceGenerator::class)->lines($this->campaign)[0]['amount']);
    }

    #[Test]
    public function targeted_hall_types_add_their_price_with_config_default(): void
    {
        $this->id('locations', ['name' => 'KLCC', 'cpm' => 0, 'price' => 5.00, 'cinema_chain_id' => $this->chain]);
        $imax = $this->id('hall_types', ['name' => 'IMAX', 'price' => 1.50]);
        $std  = $this->id('hall_types', ['name' => 'Standard']); // pas de prix => défaut 0.50
        foreach ([$imax, $std] as $h) {
            DB::table('compaign_hall_type')->insert(['compaign_id' => $this->campaign->id, 'hall_type_id' => $h]);
        }
        $this->played('KLCC', 10); // 10 × (5 + 3 + 1.5 + 0.5) = 100

        $line = app(InvoiceGenerator::class)->lines($this->campaign)[0];

        $this->assertSame(2.0, $line['hall_price']);
        $this->assertSame(100.0, $line['amount']);
        $this->assertSame('IMAX, Standard', $line['hall_types']);
    }

    #[Test]
    public function generate_creates_invoice_with_lines_tax_and_is_idempotent(): void
    {
        $this->id('locations', ['name' => 'KLCC', 'cpm' => 0, 'price' => 5.00, 'cinema_chain_id' => $this->chain]);
        $this->played('KLCC', 100); // 800 HT ; taxe 6 % => 848

        $a = app(InvoiceGenerator::class)->generate($this->campaign);
        $b = app(InvoiceGenerator::class)->generate($this->campaign);

        $this->assertSame($a->id, $b->id);
        $this->assertSame(1, $this->campaign->invoices()->count());
        $this->assertEquals(800.00, $a->total_ht);
        $this->assertEquals(848.00, $a->total_ttc);
        $this->assertSame(1, $a->lines()->count());
        $this->assertMatchesRegularExpression('/^INV-\d{4}-00001$/', $a->number);
    }

    #[Test]
    public function zero_amount_invoice_is_generated_when_nothing_was_played(): void
    {
        $i = app(InvoiceGenerator::class)->generate($this->campaign);
        $this->assertEquals(0, $i->total_ht);
        $this->assertEquals(0, $i->total_ttc);
        $this->assertSame('paid', $i->status);
        $this->assertSame(0, $i->lines()->count());
        $this->assertSame(1, $this->campaign->invoices()->count());
    }

    #[Test]
    public function command_invoices_ended_campaign_even_if_noc_is_unreachable_or_empty(): void
    {
        $this->id('compaign_cinema_chain', ['compaign_id' => $this->campaign->id, 'cinema_chain_id' => $this->chain]);
        Http::fake(['noc.test/*' => Http::response('route not found', 404)]);

        $this->artisan('invoices:generate')->assertSuccessful();

        $invoice = $this->campaign->invoices()->first();
        $this->assertNotNull($invoice);
        $this->assertEquals(0, $invoice->total_ht);
    }

    #[Test]
    public function command_invoices_with_confirmed_plays(): void
    {
        $this->id('locations', ['name' => 'KLCC', 'cpm' => 0, 'price' => 5.00, 'cinema_chain_id' => $this->chain]);
        $this->id('compaign_cinema_chain', ['compaign_id' => $this->campaign->id, 'cinema_chain_id' => $this->chain]);

        Http::fake(['noc.test/*' => Http::response([
            'status' => 1, 'synced_until' => '2026-10-11 02:00:00', 'data' => [[
                'cpl_uuid' => 'urn:uuid:aaaa-1', 'played' => 10, 'by_location' => [[
                    'location' => 'KLCC', 'played' => 10, 'incomplete' => 0, 'failed' => 0, 'played_seconds' => 300,
                    'by_day' => [['date' => '2026-10-02', 'played' => 10, 'incomplete' => 0, 'failed' => 0]],
                ]],
            ]], 'message' => 'ok',
        ])]);

        $this->artisan('invoices:generate')->assertSuccessful();
        $this->assertEquals(80.00, $this->campaign->invoices()->first()->total_ht); // 10 × (5+3)
    }
}
