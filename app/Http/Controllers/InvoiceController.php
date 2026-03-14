<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use PDF; // alias fourni par barryvdh/laravel-dompdf
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.invoices.index');
    }

    public function show(Request $request)
    {
        $invoice = Invoice::with([
            'compaign',
            'compaign.user',
            'payments',
        ])->findOrFail($request->id);

        return Response()->json(compact('invoice'));
    }

    public function storePayment(Request $request, $id)
    {
        $invoice = Invoice::with('payments')->findOrFail($id);

        $totalPaid = $invoice->payments->sum('amount');
        $remaining = round($invoice->total_ttc - $totalPaid, 2);

        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $remaining],
            'note'   => ['nullable', 'string', 'max:1000'],
        ]);

        $proofPath = null;
        $file = $request->file('proof');
        if ($file && $file->isValid() && $file->getSize() > 0) {
            if (!in_array($file->getClientMimeType(), ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'])) {
                return response()->json(['errors' => ['proof' => ['Only PDF and image files are allowed.']]], 422);
            }
            if ($file->getSize() > 5 * 1024 * 1024) {
                return response()->json(['errors' => ['proof' => ['File must not exceed 5 MB.']]], 422);
            }

            $dir = storage_path('app/public/payment_proofs');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $ext      = $file->getClientOriginalExtension() ?: ($file->guessExtension() ?? 'bin');
            $filename = Str::random(40) . '.' . $ext;
            $file->move($dir, $filename);
            $proofPath = 'payment_proofs/' . $filename;
        }

        Payment::create([
            'invoice_id' => $invoice->id,
            'amount'     => $request->amount,
            'note'       => $request->note,
            'proof'      => $proofPath,
        ]);

        $newTotalPaid = round($totalPaid + $request->amount, 2);
        if ($newTotalPaid >= $invoice->total_ttc) {
            $invoice->update(['status' => 'paid']);
        } else {
            $invoice->update(['status' => 'partially_paid']);
        }

        return response()->json([
            'message'    => 'Payment recorded successfully.',
            'paid'       => $newTotalPaid,
            'remaining'  => round($invoice->total_ttc - $newTotalPaid, 2),
            'status'     => $invoice->fresh()->status,
        ]);
    }

    public function showProof(Payment $payment)
    {
        $path = storage_path('app/public/' . $payment->proof);

        if (!$payment->proof || !file_exists($path)) {
            abort(404, 'Proof file not found.');
        }

        $mime = mime_content_type($path);
        return response()->file($path, ['Content-Type' => $mime]);
    }

    public function get()
    {
        $invoices = Invoice::with('compaign.user')->orderBy('date', 'asc')->get();
        return Response()->json(compact('invoices'));
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

    public function download($id)
    {
        // Charger l'invoice avec relations nécessaires
        $invoice = Invoice::with([
            'compaign',
            'compaign.user',
            // ajoute d'autres relations si besoin
        ])->findOrFail($id);

        // Préparer les données pour la vue PDF
        $data = [
            'invoice' => $invoice,
            // tu peux transmettre d'autres variables utiles (subtotal/tax/total)
            // si ton InvoiceController@show calculait subtotal/tax/total, reproduis la logique ici
        ];

        // Nom de fichier (ex: INV-2401.pdf). Adapte selon ton accessor invoice_no / number
        $number = $invoice->number_full ?? $invoice->invoice_no ?? $invoice->number ?? 'invoice-' . $invoice->id;
        $safeNumber = Str::slug($number, '_'); // sûr pour nom fichier
        $filename = "invoice_{$safeNumber}.pdf";

        // Charger la view Blade pour le PDF (voir exemple plus bas)
        $html = view('admin.invoices.pdf', $data)->render();

        // Générer le PDF
        $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');

        // Retourner en téléchargement
        return $pdf->download($filename);

        // Option alternative: stream() pour affichage inline
        // return $pdf->stream($filename);
    }
}
