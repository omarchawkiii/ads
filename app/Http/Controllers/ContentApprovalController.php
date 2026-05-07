<?php

namespace App\Http\Controllers;

use App\Models\DcpCreative;
use Illuminate\Http\Request;

class ContentApprovalController extends Controller
{
    public function index()
    {
        return view('content_approval.dcp_creatives.index');
    }

    public function get()
    {
        $dcps = DcpCreative::with(['customer', 'compaignCategory', 'langue', 'gender'])
            ->latest()
            ->get()
            ->map(function ($dcp) {
                return [
                    'id'               => $dcp->id,
                    'name'             => $dcp->name,
                    'duration'         => $dcp->duration,
                    'edit_rate'        => $dcp->edit_rate,
                    'status'           => $dcp->status,
                    'approval_note'    => $dcp->approval_note,
                    'customer'         => $dcp->customer?->name ?? '—',
                    'category'         => $dcp->compaignCategory?->name ?? '—',
                    'langue'           => $dcp->langue?->name ?? '—',
                    'created_at'       => $dcp->created_at?->format('Y-m-d'),
                ];
            });

        return response()->json(['dcps' => $dcps]);
    }

    public function show(int $id)
    {
        $dcp = DcpCreative::with(['customer', 'compaignCategory', 'compaignObjective', 'langue', 'gender', 'targetTypes', 'interests'])
            ->findOrFail($id);

        $previewUrl = null;
        if ($dcp->preview_status === 'ready' && $dcp->preview_path) {
            $previewUrl = \Storage::disk('public')->url($dcp->preview_path);
        }

        return response()->json([
            'dcp'         => $dcp,
            'preview_url' => $previewUrl,
        ]);
    }

    public function previewStatus(int $id)
    {
        $dcp = DcpCreative::findOrFail($id);

        $previewUrl = null;
        if ($dcp->preview_status === 'ready' && $dcp->preview_path) {
            $previewUrl = \Storage::disk('public')->url($dcp->preview_path);
        }

        return response()->json([
            'preview_status' => $dcp->preview_status,
            'preview_url'    => $previewUrl,
        ]);
    }

    public function retryPreview(int $id)
    {
        $dcp = \App\Models\DcpCreative::findOrFail($id);
        $dcp->update(['preview_status' => 'pending', 'preview_path' => null]);
        \App\Jobs\GenerateDcpPreview::dispatch($dcp);

        return response()->json(['message' => 'Preview generation restarted.']);
    }

    public function approve(Request $request, int $id)
    {
        $dcp = DcpCreative::findOrFail($id);
        $dcp->update([
            'status'        => 'approved',
            'approval_note' => $request->input('approval_note'),
        ]);

        return response()->json(['message' => 'DCP approved successfully.']);
    }

    public function reject(Request $request, int $id)
    {
        $request->validate(['approval_note' => 'required|string|max:1000']);

        $dcp = DcpCreative::findOrFail($id);
        $dcp->update([
            'status'        => 'rejected',
            'approval_note' => $request->input('approval_note'),
        ]);

        return response()->json(['message' => 'DCP rejected successfully.']);
    }
}
