<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Compaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class CompaignController extends Controller
{
    public function index()
    {
        $campaigns = Compaign::where('start_date', '<', now()->toDateString())
            ->orderBy('created_at', 'desc')
            ->get();

        $campaigns = $campaigns->map(function ($campaign) {
            $campaign->xml_url = url("storage/app/public/campaigns/campaign_{$campaign->id}.xml");
            return $campaign;

        });

        return response()->json([
            'success' => true,
            'data' => $campaigns
        ]);
    }


    public function sendCampaignToNOC($campaignId)
    {
        try {

            $campaign = Compaign::with(['planners', 'screens'])
                ->findOrFail($campaignId);

            // URL API NOC
            $nocUrl = config('services.noc.url') . '/create_template';

            // Token NOC
            $nocToken = $this->getNOCToken(); // méthode d’authentification

            // Payload à envoyer
            $payload = [
                'campaign_id'   => $campaign->id,
                'campaign_name' => $campaign->name,
                'start_date'    => $campaign->start_date,
                'end_date'      => $campaign->end_date,
                'budget'        => $campaign->budget,
                'screens'       => $campaign->screens->map(function ($screen) {
                    return [
                        'screen_id' => $screen->id,
                        'location'  => $screen->location,
                    ];
                }),
                'planners'      => $campaign->planners
            ];

            // Appel API NOC
            $response = Http::withToken($nocToken)
                ->timeout(30)
                ->post($nocUrl, $payload);

            if (!$response->successful()) {


                return response()->json([
                    'status' => 'error',
                    'message' => 'Erreur lors de la création du template NOC'
                ], 500);
            }

            $responseData = $response->json();

            // Sauvegarder l’ID template NOC
            $campaign->noc_template_id = $responseData['template_id'] ?? null;
            $campaign->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Template créé sur NOC avec succès',
                'noc_response' => $responseData
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }



}
