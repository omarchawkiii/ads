<?php

namespace App\Http\Controllers;

use App\Services\CinemaChainRevenue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/** Espace des utilisateurs « Cinema Chain » (rôle 5) : revenu publicitaire de leurs chaînes. */
class CinemaChainPortalController extends Controller
{
    public function dashboard(CinemaChainRevenue $revenue)
    {
        $report = $revenue->report(Auth::user());

        return view('cinema_chain.welcome', [
            'report'    => $report,
            'thisMonth' => collect($report['by_month'])->firstWhere('month', now()->format('Y-m')),
            'months'    => array_slice($report['by_month'], -6),
            'recent'    => array_slice($report['invoices'], 0, 5),
        ]);
    }

    public function revenue(Request $request, CinemaChainRevenue $revenue)
    {
        $filters = $this->filters($request);
        $report  = $revenue->report(Auth::user(), $filters['from'], $filters['to'], $filters['chain']);

        return view('cinema_chain.revenue', compact('report', 'filters'));
    }

    public function export(Request $request, CinemaChainRevenue $revenue)
    {
        $filters = $this->filters($request);
        $report  = $revenue->report(Auth::user(), $filters['from'], $filters['to'], $filters['chain']);

        return response()->streamDownload(function () use ($report) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Invoice', 'Campaign', 'Date', 'Status', 'Plays', 'Revenue HT (RM)', 'Chain share (RM)', 'Collected (RM)', 'Pending (RM)']);
            foreach ($report['invoices'] as $r) {
                fputcsv($out, [$r['number'], $r['campaign'], $r['date'], $r['status'], $r['plays'], $r['gross'], $r['share'], $r['collected'], $r['pending']]);
            }
            fclose($out);
        }, 'cinema-chain-revenue-' . now()->format('Ymd') . '.csv', ['Content-Type' => 'text/csv']);
    }

    /** Changement de mot de passe par l'utilisateur lui-même (ancien mot de passe exigé). */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'max:255', 'confirmed', 'different:current_password'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'The current password is incorrect.', 'errors' => ['current_password' => ['The current password is incorrect.']]], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['message' => 'Password updated successfully.']);
    }

    private function filters(Request $request): array
    {
        $v = $request->validate([
            'from'  => ['nullable', 'date'],
            'to'    => ['nullable', 'date'],
            'chain' => ['nullable', 'integer'],
        ]);

        return [
            'from'  => $v['from'] ?? null,
            'to'    => $v['to'] ?? null,
            'chain' => isset($v['chain']) ? (int) $v['chain'] : null,
        ];
    }
}
