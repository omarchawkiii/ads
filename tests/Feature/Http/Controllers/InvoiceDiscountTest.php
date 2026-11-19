<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/** Run against a dedicated DB: DB_DATABASE=adsmart_campaign_test (APP_URL=http://localhost) */
final class InvoiceDiscountTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Invoice $invoice;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin   = User::factory()->create(['role' => 1]);
        $this->invoice = Invoice::create([
            'number' => 'INV-2026-00001', 'date' => '2026-10-12', 'due_date' => '2026-11-11',
            'status' => 'unpaid', 'discount' => 0, 'tax' => 6, 'total_ht' => 1000, 'total_ttc' => 1060,
        ]);
    }

    private function discount(array $payload, ?User $as = null)
    {
        return $this->actingAs($as ?? $this->admin)->putJson("/invoices/{$this->invoice->id}/discount", $payload);
    }

    #[Test]
    public function percent_discount_reduces_net_and_recomputes_tax(): void
    {
        // 10 % de 1000 = 100 ; net 900 ; taxe 6 % => 954
        $this->discount(['type' => 'percent', 'value' => 10, 'reason' => 'Loyalty'])
            ->assertOk()->assertJsonPath('discount', 100)->assertJsonPath('total', 954);

        $i = $this->invoice->fresh();
        $this->assertEquals(1000, $i->total_ht);          // sous-total brut inchangé
        $this->assertEquals(100, $i->discount);
        $this->assertEquals(954, $i->total_ttc);
        $this->assertSame('percent', $i->discount_type);
        $this->assertSame('Loyalty', $i->discount_reason);
        $this->assertSame($this->admin->id, $i->discount_by);
    }

    #[Test]
    public function fixed_discount_can_be_replaced_then_removed(): void
    {
        $this->discount(['type' => 'fixed', 'value' => 200, 'reason' => 'Gesture'])->assertOk()->assertJsonPath('total', 848);
        $this->discount(['type' => 'fixed', 'value' => 50, 'reason' => 'Gesture'])->assertOk()->assertJsonPath('total', 1007);

        $this->discount(['type' => 'none'])->assertOk()->assertJsonPath('total', 1060);
        $i = $this->invoice->fresh();
        $this->assertEquals(0, $i->discount);
        $this->assertNull($i->discount_type);
        $this->assertNull($i->discount_reason);
    }

    #[Test]
    public function reason_is_required_and_discount_must_stay_below_subtotal(): void
    {
        $this->discount(['type' => 'percent', 'value' => 10])->assertStatus(422)->assertJsonValidationErrors('reason');
        $this->discount(['type' => 'percent', 'value' => 100, 'reason' => 'x'])->assertStatus(422)->assertJsonValidationErrors('value');
        $this->discount(['type' => 'fixed', 'value' => 1000, 'reason' => 'x'])->assertStatus(422)->assertJsonValidationErrors('value');
        $this->discount(['type' => 'fixed', 'value' => -5, 'reason' => 'x'])->assertStatus(422);
        $this->assertEquals(1060, $this->invoice->fresh()->total_ttc);
    }

    #[Test]
    public function discount_cannot_go_below_what_is_already_paid_and_status_follows(): void
    {
        $this->invoice->payments()->create(['amount' => 600]);
        $this->invoice->update(['status' => 'partially_paid']);

        // 50 % => net 500 => 530 < 600 déjà payés : refusé
        $this->discount(['type' => 'percent', 'value' => 50, 'reason' => 'x'])->assertStatus(422)->assertJsonValidationErrors('value');

        // 40 % => net 600 => 636 ; reste 36, statut inchangé
        $this->discount(['type' => 'percent', 'value' => 40, 'reason' => 'x'])
            ->assertOk()->assertJsonPath('total', 636)->assertJsonPath('remaining', 36)->assertJsonPath('status', 'partially_paid');
    }

    #[Test]
    public function discount_equal_to_paid_total_marks_invoice_paid(): void
    {
        $this->invoice->payments()->create(['amount' => 954]);
        $this->invoice->update(['status' => 'partially_paid']);

        $this->discount(['type' => 'percent', 'value' => 10, 'reason' => 'x'])
            ->assertOk()->assertJsonPath('remaining', 0)->assertJsonPath('status', 'paid');
    }

    #[Test]
    public function only_admin_can_apply_a_discount_and_details_expose_it(): void
    {
        $advertiser = User::factory()->create(['role' => 2]);
        $this->discount(['type' => 'percent', 'value' => 10, 'reason' => 'x'], $advertiser)->assertForbidden();

        $this->discount(['type' => 'percent', 'value' => 10, 'reason' => 'Loyalty'])->assertOk();

        $this->actingAs($this->admin)->getJson("/invoices/{$this->invoice->id}/show")
            ->assertOk()
            ->assertJsonPath('totals.discount', 100)
            ->assertJsonPath('totals.discount_type', 'percent')
            ->assertJsonPath('totals.discount_value', 10)
            ->assertJsonPath('totals.net', 900)
            ->assertJsonPath('totals.tax_amount', 54)
            ->assertJsonPath('totals.total', 954);

        $this->actingAs($this->admin)->get("/invoices/{$this->invoice->id}/download")->assertOk();
    }
}
