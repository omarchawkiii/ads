<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
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
        ])->findOrFail($request->id);

        return Response()->json(compact('invoice'));
    }

    public function get()
    {
        $invoices = Invoice::with('compaign.user')->orderBy('date', 'asc')->get();
        return Response()->json(compact('invoices'));
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
