<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Compaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompaignController extends Controller
{
    public function index()
    {
        $campaigns = Compaign::orderBy('created_at', 'desc')->get();

        $campaigns = $campaigns->map(function ($campaign) {
            $xmlPath = "campaigns/campaign_{$campaign->id}.xml";

            $campaign->xml_url = Storage::disk('public')->exists($xmlPath)
                ? asset('storage/' . $xmlPath)
                : null;

            return $campaign;
        });

        return response()->json([
            'success' => true,
            'data' => $campaigns
        ]);
    }
}
