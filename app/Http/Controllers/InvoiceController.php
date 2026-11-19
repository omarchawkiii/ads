<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\InvoiceDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use PDF; // alias fourni par barryvdh/laravel-dompdf
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /** Types de preuve acceptés (type MIME détecté côté serveur => extension imposée). */
    private const PROOF_TYPES = [
        'application/pdf' => 'pdf',
        'image/jpeg'      => 'jpg',
        'image/png'       => 'png',
    ];

    public function index(Request $request)
    {
        return view('admin.invoices.index');
    }

    /** Détail complet d'une facture (admin). */
    public function show(Request $request, InvoiceDetails $details)
    {
        return response()->json($details->build(Invoice::findOrFail($request->id)));
    }

    /** Détail complet d'une facture, réservé à son annonceur. */
    public function my_invoice_show($id, InvoiceDetails $details)
    {
        $invoice = Invoice::with('compaign')->findOrFail($id);
        abort_unless(optional($invoice->compaign)->user_id === auth()->id(), 403, 'Access denied');

        return response()->json($details->build($invoice));
    }

    public function storePayment(Request $request, $id)
    {
        // Transaction + verrou sur la facture : deux paiements simultanés ne peuvent pas
        // dépasser le total, et le statut est recalculé sur la somme réelle des paiements.
        return DB::transaction(function () use ($request, $id) {
            $invoice = Invoice::lockForUpdate()->findOrFail($id);

            $totalPaid = round((float) $invoice->payments()->sum('amount'), 2);
            $remaining = round($invoice->total_ttc - $totalPaid, 2);

            $request->validate([
                'amount' => ['required', 'numeric', 'min:0.01', 'max:' . max($remaining, 0)],
                'note'   => ['nullable', 'string', 'max:1000'],
            ], [
                'amount.max' => $remaining <= 0
                    ? 'This invoice is already fully paid.'
                    : 'The amount cannot exceed the remaining balance (RM ' . number_format($remaining, 2) . ').',
            ]);

            $proofPath = null;
            $file      = $request->file('proof');
            if ($file && $file->isValid() && $file->getSize() > 0) {
                $ext = self::PROOF_TYPES[$file->getMimeType()] ?? null;
                if (!$ext) {
                    return response()->json(['errors' => ['proof' => ['Only PDF, JPG and PNG files are allowed.']]], 422);
                }
                if ($file->getSize() > 5 * 1024 * 1024) {
                    return response()->json(['errors' => ['proof' => ['File must not exceed 5 MB.']]], 422);
                }

                // Dossier privé : non servi directement par le serveur web (accès via showProof).
                $dir = storage_path('app/private/payment_proofs');
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                $filename = Str::random(40) . '.' . $ext;
                $file->move($dir, $filename);
                $proofPath = 'private/payment_proofs/' . $filename;
            }

            Payment::create([
                'invoice_id' => $invoice->id,
                'amount'     => $request->amount,
                'note'       => $request->note,
                'proof'      => $proofPath,
            ]);

            $newTotalPaid = round($totalPaid + (float) $request->amount, 2);
            $invoice->update(['status' => $newTotalPaid >= (float) $invoice->total_ttc ? 'paid' : 'partially_paid']);

            return response()->json([
                'message'   => 'Payment recorded successfully.',
                'paid'      => $newTotalPaid,
                'remaining' => round($invoice->total_ttc - $newTotalPaid, 2),
                'status'    => $invoice->status,
            ]);
        });
    }

    /**
     * Applique, modifie ou retire une remise sur une facture (admin).
     * La remise s'applique au sous-total HT ; la taxe est recalculée sur le net.
     * Elle ne peut pas ramener le total sous ce qui a déjà été payé.
     */
    public function applyDiscount(Request $request, $id)
    {
        $v = $request->validate([
            'type'   => ['required', 'in:percent,fixed,none'],
            'value'  => ['required_unless:type,none', 'nullable', 'numeric', 'min:0.01', 'max:999999.99'],
            'reason' => ['required_unless:type,none', 'nullable', 'string', 'max:500'],
        ]);

        return DB::transaction(function () use ($v, $id) {
            $invoice = Invoice::lockForUpdate()->findOrFail($id);

            $gross = round((float) $invoice->total_ht, 2);
            $type  = $v['type'];

            if ($type === 'none') {
                $amount = 0.0;
            } elseif ($type === 'percent') {
                $amount = round($gross * (float) $v['value'] / 100, 2);
            } else {
                $amount = round((float) $v['value'], 2);
            }

            if ($amount >= $gross && $type !== 'none') {
                throw ValidationException::withMessages([
                    'value' => 'The discount must be lower than the subtotal (RM ' . number_format($gross, 2) . ').',
                ]);
            }

            $newTotal = round(($gross - $amount) * (1 + (float) $invoice->tax / 100), 2);
            $paid     = round((float) $invoice->payments()->sum('amount'), 2);

            if ($newTotal < $paid) {
                throw ValidationException::withMessages([
                    'value' => 'Discount too high: RM ' . number_format($paid, 2) . ' has already been paid, which would exceed the new total (RM ' . number_format($newTotal, 2) . ').',
                ]);
            }

            $invoice->update([
                'discount'        => $amount,
                'discount_type'   => $type === 'none' ? null : $type,
                'discount_value'  => $type === 'none' ? null : $v['value'],
                'discount_reason' => $type === 'none' ? null : $v['reason'],
                'discount_by'     => $type === 'none' ? null : auth()->id(),
                'discount_at'     => $type === 'none' ? null : now(),
                'total_ttc'       => $newTotal,
                'status'          => $paid <= 0 ? 'unpaid' : ($paid >= $newTotal ? 'paid' : 'partially_paid'),
            ]);

            return response()->json([
                'message'   => $type === 'none' ? 'Discount removed.' : 'Discount applied.',
                'discount'  => $amount,
                'total'     => $newTotal,
                'remaining' => round($newTotal - $paid, 2),
                'status'    => $invoice->status,
            ]);
        });
    }

    public function showProof(Payment $payment)
    {
        abort_unless($payment->proof, 404, 'Proof file not found.');

        // Nouveaux fichiers : storage/app/private/... ; anciens : storage/app/public/...
        $path = storage_path('app/' . $payment->proof);
        if (!file_exists($path)) {
            $path = storage_path('app/public/' . $payment->proof);
        }
        abort_unless(file_exists($path), 404, 'Proof file not found.');

        $mime = mime_content_type($path);
        abort_unless(isset(self::PROOF_TYPES[$mime]), 415, 'Unsupported file type.');

        return response()->file($path, [
            'Content-Type'           => $mime,
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function get()
    {
        $invoices = Invoice::with('compaign.user')
            ->withSum('payments as paid', 'amount')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->each(function ($i) {
                $i->paid      = round((float) $i->paid, 2);
                $i->remaining = round((float) $i->total_ttc - $i->paid, 2);
                $i->overdue   = $i->remaining > 0 && $i->due_date && $i->due_date < now()->toDateString();
            });

        $summary = [
            'billed'        => round($invoices->sum('total_ttc'), 2),
            'paid'          => round($invoices->sum('paid'), 2),
            'outstanding'   => round($invoices->sum('remaining'), 2),
            'overdue_count' => $invoices->where('overdue', true)->count(),
            'overdue_total' => round($invoices->where('overdue', true)->sum('remaining'), 2),
        ];

        return Response()->json(compact('invoices', 'summary'));
    }

    public function advertiser_index()
    {
        return view('advertiser.invoices.index');
    }

    public function my_invoices()
    {
        $invoices = Invoice::with('compaign')
            ->whereHas('compaign', fn ($q) => $q->where('user_id', auth()->id()))
            ->orderBy('date', 'desc')
            ->get();
        return response()->json(compact('invoices'));
    }

    public function download($id, InvoiceDetails $details)
    {
        $invoice = Invoice::with('compaign')->findOrFail($id);

        // Un annonceur ne télécharge que ses propres factures.
        if (auth()->user()->role != 1) {
            abort_unless(optional($invoice->compaign)->user_id === auth()->id(), 403, 'Access denied');
        }

        $number     = $invoice->number ?? 'invoice-' . $invoice->id;
        $safeNumber = Str::slug($number, '_');
        $filename   = "invoice_{$safeNumber}.pdf";

        $html = view('admin.invoices.pdf', ['invoice' => $invoice, 'd' => $details->build($invoice)])->render();

        return PDF::loadHTML($html)->setPaper('a4', 'portrait')->download($filename);
    }
}
