<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/** Run against a dedicated DB: DB_DATABASE=adsmart_campaign_test (APP_URL=http://localhost) */
final class InvoicePaymentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Invoice $invoice;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin   = User::factory()->create(['role' => 1]);
        $this->invoice = Invoice::create([
            'number' => 'INV-2026-00001', 'date' => now()->subDays(40)->toDateString(),
            'due_date' => now()->subDays(10)->toDateString(), 'status' => 'unpaid',
            'discount' => 0, 'tax' => 6, 'total_ht' => 100, 'total_ttc' => 106,
        ]);
    }

    private function pay(float $amount, array $extra = [])
    {
        return $this->actingAs($this->admin)
            ->post("/invoices/{$this->invoice->id}/payments", ["amount" => $amount] + $extra, ["Accept" => "application/json"]);
    }

    #[Test]
    public function partial_then_full_payment_updates_status(): void
    {
        $this->pay(50)->assertOk()->assertJsonPath('status', 'partially_paid')->assertJsonPath('remaining', 56);
        $this->pay(56)->assertOk()->assertJsonPath('status', 'paid')->assertJsonPath('remaining', 0);
        $this->assertSame('paid', $this->invoice->fresh()->status);
    }

    #[Test]
    public function overpayment_and_payment_on_paid_invoice_are_rejected(): void
    {
        $this->pay(200)->assertStatus(422);
        $this->pay(106)->assertOk();
        $this->pay(1)->assertStatus(422)->assertJsonValidationErrors('amount');
        $this->assertSame(1, $this->invoice->payments()->count());
    }

    #[Test]
    public function proof_type_is_checked_on_content_not_on_client_mime(): void
    {
        // Faux PDF : contenu HTML envoyé avec une extension / un type client .pdf
        // (UploadedFile::fake() déduit le type du nom : on utilise un vrai fichier temporaire.)
        $tmp = tempnam(sys_get_temp_dir(), 'proof');
        file_put_contents($tmp, '<html><script>alert(1)</script></html>');
        $fake = new UploadedFile($tmp, 'proof.pdf', 'application/pdf', null, true);
        $this->pay(10, ['proof' => $fake])->assertStatus(422)->assertJsonValidationErrors('proof');
        $this->assertSame(0, $this->invoice->payments()->count());

        $png = UploadedFile::fake()->image('proof.png');
        $this->pay(10, ['proof' => $png])->assertOk();
        $this->assertStringStartsWith('private/payment_proofs/', $this->invoice->payments()->first()->proof);
        $this->assertStringEndsWith('.png', $this->invoice->payments()->first()->proof);
    }

    #[Test]
    public function list_returns_paid_remaining_overdue_and_summary(): void
    {
        $this->pay(6)->assertOk();

        $res = $this->actingAs($this->admin)->getJson('/invoices/list')->assertOk();

        $res->assertJsonPath('invoices.0.paid', 6)
            ->assertJsonPath('invoices.0.remaining', 100)
            ->assertJsonPath('invoices.0.overdue', true)
            ->assertJsonPath('summary.billed', 106)
            ->assertJsonPath('summary.outstanding', 100)
            ->assertJsonPath('summary.overdue_count', 1);
    }
}
