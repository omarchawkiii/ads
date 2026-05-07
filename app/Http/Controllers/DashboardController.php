<?php

namespace App\Http\Controllers;

use App\Models\Compaign;
use App\Models\DcpCreative;
use App\Models\Invoice;
use App\Models\Slot;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function admin()
    {
        $today = Carbon::today();

        // Campaign counts
        $totalCampaigns    = Compaign::count();
        $pendingCampaigns  = Compaign::where('status', 1)->count();
        $approvedCampaigns = Compaign::where('status', 2)->count();
        $draftCampaigns    = Compaign::where('status', 3)->count();
        $rejectedCampaigns = Compaign::where('status', 4)->count();

        // Active campaigns (approved + currently running)
        $activeCampaigns = Compaign::where('status', 2)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->count();

        // Users
        $totalAdvertisers = User::where('role', 2)->count();

        // Financials
        $totalBudget  = Compaign::sum('budget');
        $totalRevenue = Invoice::sum('total_ttc');
        $paidRevenue  = Invoice::where('status', 'paid')->sum('total_ttc');

        // Assets
        $totalDcps  = DcpCreative::count();
        $totalSlots = Slot::count();

        // Recent campaigns (last 5)
        $recentCampaigns = Compaign::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Campaigns per month (last 6 months)
        $monthlyCampaigns = Compaign::selectRaw("DATE_FORMAT(created_at, '%b') as month, COUNT(*) as total")
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->pluck('total', 'month');

        return view('admin.welcome', compact(
            'totalCampaigns', 'pendingCampaigns', 'approvedCampaigns',
            'draftCampaigns', 'rejectedCampaigns', 'activeCampaigns',
            'totalAdvertisers', 'totalBudget', 'totalRevenue', 'paidRevenue',
            'totalDcps', 'totalSlots', 'recentCampaigns', 'monthlyCampaigns'
        ));
    }

    public function contentApproval()
    {
        $pendingDcps  = DcpCreative::where('status', 'pending')->count();
        $approvedDcps = DcpCreative::where('status', 'approved')->count();
        $rejectedDcps = DcpCreative::where('status', 'rejected')->count();
        $totalDcps    = DcpCreative::count();

        $recentDcps = DcpCreative::with('customer')
            ->latest()
            ->take(5)
            ->get();

        return view('content_approval.welcome', compact(
            'pendingDcps', 'approvedDcps', 'rejectedDcps', 'totalDcps', 'recentDcps'
        ));
    }

    public function advertiser()
    {
        $userId = Auth::id();
        $today  = Carbon::today();

        // Campaign counts (own)
        $totalCampaigns    = Compaign::where('user_id', $userId)->count();
        $pendingCampaigns  = Compaign::where('user_id', $userId)->where('status', 1)->count();
        $approvedCampaigns = Compaign::where('user_id', $userId)->where('status', 2)->count();
        $draftCampaigns    = Compaign::where('user_id', $userId)->where('status', 3)->count();
        $rejectedCampaigns = Compaign::where('user_id', $userId)->where('status', 4)->count();

        // Active campaigns (own, running today)
        $activeCampaigns = Compaign::where('user_id', $userId)
            ->where('status', 2)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->count();

        // Financials (own)
        $totalBudget  = Compaign::where('user_id', $userId)->sum('budget');
        $totalInvoiced = Invoice::whereHas('compaign', fn ($q) => $q->where('user_id', $userId))->sum('total_ttc');
        $paidInvoiced  = Invoice::whereHas('compaign', fn ($q) => $q->where('user_id', $userId))->where('status', 'paid')->sum('total_ttc');

        // DCP creatives (own)
        $totalDcps = DcpCreative::where('user_id', $userId)->count();

        // Recent campaigns (own, last 5)
        $recentCampaigns = Compaign::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // Campaigns per month (own, last 6 months)
        $monthlyCampaigns = Compaign::where('user_id', $userId)
            ->selectRaw("DATE_FORMAT(created_at, '%b') as month, COUNT(*) as total")
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->pluck('total', 'month');

        return view('advertiser.welcome', compact(
            'totalCampaigns', 'pendingCampaigns', 'approvedCampaigns',
            'draftCampaigns', 'rejectedCampaigns', 'activeCampaigns',
            'totalBudget', 'totalInvoiced', 'paidInvoiced',
            'totalDcps', 'recentCampaigns', 'monthlyCampaigns'
        ));
    }
}
