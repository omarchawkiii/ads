<?php

namespace App\Http\Controllers;

use App\Models\CinemaChain;
use App\Models\Compaign;
use App\Models\CompaignCategory;
use App\Models\CompaignObjective;
use App\Models\Config;
use App\Models\DcpCreative;
use App\Models\Gender;
use App\Models\HallType;
use App\Models\Interest;
use App\Models\Langue;
use App\Models\Location;
use App\Models\MasterMovie;
use App\Models\Movie;
use App\Models\MovieGenre;
use App\Models\Slot;
use App\Models\TargetType;
use App\Models\Invoice;
use App\Models\TemplateSlot;
use App\Services\CampaignXmlGenerator;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Mail\Mailables\Content;

class CompaignController extends Controller
{
    public function index()
    {
        $compaign_categories = CompaignCategory::orderBy('name', 'asc')->get() ;
        $compaign_objectives = CompaignObjective::orderBy('name', 'asc')->get() ;
        $langues = Langue::orderBy('name', 'asc')->get() ;
        $locations = Location::orderBy('name', 'asc')->get() ;
        $hall_types = HallType::orderBy('name', 'asc')->get() ;
        $movies = Movie::orderBy('name', 'asc')->get() ;
        $movie_genres = MovieGenre::orderBy('name', 'asc')->get() ;
        $genders = Gender::orderBy('name', 'asc')->get() ;
        $target_types = TargetType::orderBy('name', 'asc')->get() ;
        $interests = Interest::orderBy('name', 'asc')->get() ;
        $slots = Slot::orderBy('name', 'asc')->get() ;
        $dcp_creatives = DcpCreative::orderBy('name', 'asc')->get() ;

        return view('admin.compaigns.index', compact('compaign_categories','compaign_objectives','langues','locations','hall_types','movies','movie_genres','genders','target_types','interests','slots','dcp_creatives'));
    }



    public function show($id)
    {
        $compaign = Compaign::with([
            'compaignObjective:id,name',
            'compaignCategory:id,name',
            'langue:id,name',
            'masterMovies:id,title',
            'gender:id,name',
            'templateSlot:id,name',
            'slots:id,name,max_duration',
            'dcpCreatives:id,name,duration',
            'locations:id,name',
            'hallTypes:id,name',
            'movieGenres:id,name',
            'interests:id,name',
            'targetTypes:id,name',
        ])->findOrFail($id);

        return response()->json($compaign);
    }

    public function get()
    {
        $compaigns = Compaign::with('user')->orderBy('created_at', 'desc')->get();
        return Response()->json(compact('compaigns'));
    }




    public function store(Request $request)
    {
        $v = $request->validate([
            'compaign_name'        => 'required|string|max:255',
            'template_slot_id'     => 'required|integer|exists:template_slots,id',
            'start_date'           => 'required|date',
            'end_date'             => 'required|date|after_or_equal:start_date',
            'compaign_category_id' => 'nullable|integer|exists:compaign_categories,id',
            'cinema_chain_id'      => 'nullable|array',
            'cinema_chain_id.*'    => 'integer|exists:cinema_chains,id',
            'location_id'          => 'array',
            'location_id.*'        => 'integer|exists:locations,id',
            'movie_genre_id'       => 'array',
            'movie_genre_id.*'     => 'integer|exists:movie_genres,id',
            'master_movie_id'      => 'nullable|array',
            'master_movie_id.*'    => 'integer|exists:master_movies,id',
            'hall_type_id'         => 'array',
            'hall_type_id.*'       => 'integer|exists:hall_types,id',
            'slots'                    => 'required|array|min:1',
            'slots.*.slot_id'          => 'required|integer|exists:slots,id',
            'slots.*.dcps'             => 'required|array|min:1',
            'slots.*.dcps.*.position'  => 'required|integer|min:1',
            'slots.*.dcps.*.dcp_id'    => 'required|integer|exists:dcp_creatives,id',
            'budget'      => 'required|integer|min:1',
            'langue'      => 'required|integer|exists:langues,id',
            'gender'      => 'required|integer|exists:genders,id',
            'target_type'   => 'array',
            'target_type.*' => 'integer|exists:target_types,id',
            'interest'      => 'array',
            'interest.*'    => 'integer|exists:interests,id',
        ]);

        $compaign = DB::transaction(function () use ($v, $request) {

            // 1️⃣ Création de la campagne
            $compaign = Compaign::create([
                'name'                  => $v['compaign_name'],
                'template_slot_id'      => $v['template_slot_id'],
                'langue_id'             => $v['langue'],
                'gender_id'             => $v['gender'],
                'budget'                => $v['budget'],
                'compaign_objective_id' => 1,
                'ad_duration'           => 30,
                'start_date'            => $v['start_date'],
                'end_date'              => $v['end_date'],
                'compaign_category_id'  => 1,
                'user_id'               => Auth::id(),
                'status'                => 1,
            ]);

            $ids = fn ($key) => array_values(array_filter(Arr::wrap($request->input($key))));

            // 2️⃣ Relations
            $compaign->masterMovies()->sync($ids('master_movie_id'));

            if ($request->has('cinema_chain_id')) $compaign->cinemaChains()->sync($ids('cinema_chain_id'));
            if ($request->has('location_id'))     $compaign->locations()->sync($ids('location_id'));
            if ($request->has('movie_genre_id'))  $compaign->movieGenres()->sync($ids('movie_genre_id'));
            if ($request->has('hall_type_id'))    $compaign->hallTypes()->sync($ids('hall_type_id'));
            if ($request->has('target_type'))     $compaign->targetTypes()->sync($ids('target_type'));
            if ($request->has('interest'))        $compaign->interests()->sync($ids('interest'));

            // 3️⃣ Slots
            $compaign->slots()->sync(
                collect($v['slots'])->pluck('slot_id')->unique()->toArray()
            );

            // 4️⃣ DCPs
            $compaign->dcpCreatives()->detach();
            foreach ($v['slots'] as $slotData) {
                foreach ($slotData['dcps'] as $dcp) {
                    $compaign->dcpCreatives()->attach($dcp['dcp_id'], [
                        'slot_id'    => $slotData['slot_id'],
                        'position'   => $dcp['position'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // 5️⃣ Génération XML
            CampaignXmlGenerator::generate($compaign);

            return $compaign;
        });

        // 6️⃣ Push vers NOC si demandé
        $noc = [];
        if ($request->boolean('send_to_noc')) {
            $noc = $this->pushCampaignToNoc($compaign);
            $allSent  = count($noc) > 0 && collect($noc)->every(fn($r) => $r['sent']);
            $failNote = collect($noc)->where('sent', false)->map(fn($r) => $r['cinema_chain'].': '.($r['reason'] ?? 'failed'))->implode(' | ');
            $compaign->update([
                'noc_sent' => $allSent,
                'noc_note' => $allSent ? null : ($failNote ?: null),
            ]);
        }

        return response()->json([
            'message'     => 'Compaign created successfully.',
            'id'          => $compaign->id,
            'noc_results' => $noc,
        ], 201);
    }
    public function sendToNoc($id)
    {
        $compaign = Compaign::findOrFail($id);

        if ($compaign->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $noc      = $this->pushCampaignToNoc($compaign);
        $allSent  = count($noc) > 0 && collect($noc)->every(fn($r) => $r['sent']);
        $failNote = collect($noc)->where('sent', false)->map(fn($r) => $r['cinema_chain'].': '.($r['reason'] ?? 'failed'))->implode(' | ');

        $compaign->update([
            'noc_sent' => $allSent,
            'noc_note' => $allSent ? null : ($failNote ?: null),
        ]);

        return response()->json([
            'message'     => $allSent ? 'Campaign sent to NOC successfully.' : 'Campaign sent with errors.',
            'noc_results' => $noc,
        ]);
    }

    private function pushCampaignToNoc(Compaign $compaign): array
    {
        $chains  = $compaign->cinemaChains()->get();
        $results = [];


        foreach ($chains as $chain) {
            if (empty($chain->ip_address)) {
                $results[] = [
                    'cinema_chain' => $chain->name,
                    'sent'         => false,
                    'reason'       => 'No IP/URL configured for this cinema chain.',
                ];
                continue;
            }

            try {
                $url    = rtrim($chain->ip_address, '/') . '/api/adsmart/receive_campaign';
                $client = new Client();

                $xmlPath    = "campaigns/campaign_{$compaign->id}.xml";
                $xmlContent = \Illuminate\Support\Facades\Storage::disk('public')->exists($xmlPath)
                    ? \Illuminate\Support\Facades\Storage::disk('public')->get($xmlPath)
                    : null;
                $response = $client->request('POST', $url, [
                    'connect_timeout' => 5,
                    'query' => [
                        'username' => $chain->username,
                        'password' => $chain->password,
                    ],
                    'json' => [
                        'id'          => $compaign->id,
                        'name'        => $compaign->name,
                        'xml_content' => $xmlContent,
                    ],
                ]);

                $contents = json_decode($response->getBody(), true);

                $results[] = [
                    'cinema_chain' => $chain->name,
                    'sent'         => !empty($contents['status']),
                    'reason'       => $contents['message'] ?? null,
                    'missing_cpls' => $contents['planner']['missing_cpls'] ?? [],
                ];

            } catch (RequestException $e) {
                if ($e->hasResponse()) {
                    $body   = (string) $e->getResponse()->getBody();
                    $parsed = json_decode($body, true);
                    $detail = ($parsed && isset($parsed['message'])) ? $parsed['message'] : $body;
                } else {
                    $detail = $e->getMessage();
                }
                $results[] = [
                    'cinema_chain' => $chain->name,
                    'sent'         => false,
                    'reason'       => $detail,
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'cinema_chain' => $chain->name,
                    'sent'         => false,
                    'reason'       => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    public function edit($id)
    {
        /* =====================================================
        | 0️⃣ Charger la campagne
        ===================================================== */
        $compaign = Compaign::with([
            'masterMovies:id',
            'locations:id',
            'movieGenres:id',
            'hallTypes:id',
            'cinemaChains:id',
            'slots:id,template_slot_id,name,max_duration,max_ad_slot',
            'dcpCreatives:id,duration',
        ])->findOrFail($id);

        $locationIds = $compaign->locations->pluck('id')->toArray();
        $slotIds     = $compaign->slots->pluck('id')->toArray();
        $cinemaChainIds = $compaign->cinemaChains->pluck('id')->toArray();
        $locationsOfCinemaChaings = Location::whereIn('cinema_chain_id', $cinemaChainIds)->get();

        /* =====================================================
        | 1️⃣ DCP disponibles pour l’utilisateur
        ===================================================== */
        $dcp_creatives = DcpCreative::where('status', 1)
            ->where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        /* =====================================================
        | 2️⃣ Template + slots
        ===================================================== */
        $templateSlots = TemplateSlot::with('slots')
            ->where('id', $compaign->template_slot_id)
            ->get();

        /* =====================================================
        | 3️⃣ Récupérer toutes les positions AVEC DURÉE
        ===================================================== */
        $positionsQuery = DB::table('compaign_slot_dcp as csd')
            ->join('compaigns as c', 'c.id', '=', 'csd.compaign_id')
            ->join('dcp_creatives as d', 'd.id', '=', 'csd.dcp_creative_id')
            ->join('compaign_location as cl', 'cl.compaign_id', '=', 'c.id')
            ->whereIn('csd.slot_id', $slotIds)
            ->whereIn('cl.location_id', $locationIds)
            ->where(function ($q) use ($compaign) {
                $q->whereBetween('c.start_date', [$compaign->start_date, $compaign->end_date])
                ->orWhereBetween('c.end_date', [$compaign->start_date, $compaign->end_date])
                ->orWhere(function ($q2) use ($compaign) {
                    $q2->where('c.start_date', '<=', $compaign->start_date)
                        ->where('c.end_date', '>=', $compaign->end_date);
                });
            })
            ->select(
                'csd.slot_id',
                'csd.position',
                'csd.dcp_creative_id',
                'd.duration',
                'c.id as compaign_id',
                'c.created_at'
            )
            ->orderBy('csd.position')
            ->get();
                //dd($positionsQuery);
        /* =====================================================
        | 4️⃣ Regrouper les positions PAR SLOT
        ===================================================== */
        $positionsBySlot = [];

        foreach ($positionsQuery as $row) {
            $positionsBySlot[$row->slot_id][] = [
                'type'        => $row->compaign_id == $compaign->id ? 'current' : 'reserved',
                'compaign_id' => $row->compaign_id,
                'dcp_id'      => $row->dcp_creative_id,
                'duration'    => (int) $row->duration,
                'position'    => (int) $row->position,
                'created_at'  => $row->created_at,
            ];
        }

        //dd($positionsBySlot);

        /* =====================================================
        | 5️⃣ Construire le payload FINAL des slots
        ===================================================== */
        $slotsPayload = [];

        foreach ($templateSlots as $tpl) {
            foreach ($tpl->slots as $slot) {

                $positions = $positionsBySlot[$slot->id] ?? [];

                /* 🔥 POSITIONS EFFECTIVES (current + reserved seulement) */
                $effectivePositions = array_filter(
                    $positions,
                    fn ($p) => in_array($p['type'], ['current', 'reserved'])
                );

                /* 🔥 CALCULS CORRECTS PAR SLOT */
                $usedDuration = array_sum(
                    array_map(
                        fn ($p) => $p['duration'] ?? 0,
                        $effectivePositions
                    )
                );
                //dd($usedDuration, $effectivePositions, $positions);

                $assignedDcp = count($effectivePositions);

                $remainingDuration = max(0, $slot->max_duration - $usedDuration);
                $remainingDcp      = max(0, $slot->max_ad_slot - $assignedDcp);

                $slotsPayload[] = [
                    'slot_id'            => $slot->id,
                    'name'               => $slot->name,
                    'max_duration'       => (int) $slot->max_duration,
                    'max_ad_slot'        => (int) $slot->max_ad_slot,
                    'positions'          => array_values($positions),
                    'used_duration'      => $usedDuration,
                    'assigned_dcp'       => $assignedDcp,
                    'remaining_duration' => $remainingDuration,
                    'remaining_dcp'      => $remainingDcp,
                ];
            }
        }

        /* =====================================================
        | 6️⃣ Retour vers la vue
        ===================================================== */
        return view('advertiser.compaigns.edit_builder', [
            'compaign'            => $compaign,
            'slotsData'           => $slotsPayload,
            'dcp_creatives'       => $dcp_creatives,
            'slot_templates'      => TemplateSlot::orderBy('name')->get(),
            'isEdit'              => true,
            'selectedLocations'   => $locationIds,
            'locationsOfCinemaChaings' =>$locationsOfCinemaChaings,

            // Selects
            'compaign_categories' => CompaignCategory::orderBy('name')->get(),
            'compaign_objectives' => CompaignObjective::orderBy('name')->get(),
            'langues'             => Langue::orderBy('name')->get(),
            'locations'           => Location::orderBy('name')->get(),
            'hall_types'          => HallType::orderBy('name')->get(),
            'master_movies'       => MasterMovie::with('genres')->orderBy('title')->get(),
            'movie_genres'        => MovieGenre::orderBy('name')->get(),
            'genders'             => Gender::orderBy('name')->get(),
            'target_types'        => TargetType::orderBy('name')->get(),
            'interests'           => Interest::orderBy('name')->get(),
            'slots'               => Slot::orderBy('name')->get(),
            'cinema_chains'       => Auth()->user()->cinemaChains()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {

        $v = $request->validate([

            'compaign_name'       => 'required|string|max:255',
            'template_slot_id'    => 'required|integer|exists:template_slots,id',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',

            'compaign_category_id' => 'nullable|integer|exists:compaign_categories,id',

            'cinema_chain_id'      => 'nullable|array',
            'cinema_chain_id.*'    => 'integer|exists:cinema_chains,id',

            'location_id'          => 'array',
            'location_id.*'        => 'integer|exists:locations,id',

            'movie_genre_id'       => 'array',
            'movie_genre_id.*'     => 'integer|exists:movie_genres,id',

            'master_movie_id'      => 'nullable|array',
            'master_movie_id.*'    => 'integer|exists:master_movies,id',

            'hall_type_id'         => 'array',
            'hall_type_id.*'       => 'integer|exists:hall_types,id',

            // 🔥 slots + dcps + position
            'slots'                       => 'required|array|min:1',
            'slots.*.slot_id'             => 'required|integer|exists:slots,id',
            'slots.*.dcps'                => 'required|array|min:1',
            'slots.*.dcps.*.dcp_id'       => 'required|integer|exists:dcp_creatives,id',
            'slots.*.dcps.*.position'     => 'required|integer|min:1',

            'budget'          => 'required|integer|min:1',
            'langue'          => 'required|integer|exists:langues,id',
            'gender'          => 'required|integer|exists:genders,id',

            'target_type'     => 'array',
            'target_type.*'   => 'integer|exists:target_types,id',

            'interest'        => 'array',
            'interest.*'      => 'integer|exists:interests,id',
        ]);

        $compaign = Compaign::findOrFail($id);

        if ($compaign->start_date <= now()->toDateString()) {
            return response()->json([
                'message' => 'This campaign has already started and cannot be edited.'
            ], 403);
        }

        return DB::transaction(function () use ($v, $request, $id) {

            /* -------------------------------------------------
            | 1️⃣ récupérer la campagne
            -------------------------------------------------*/
            $compaign = Compaign::findOrFail($id);

            /* -------------------------------------------------
            | 2️⃣ update champs simples
            -------------------------------------------------*/
            $compaign->update([
                'name'                  => $v['compaign_name'],
                'template_slot_id'      => $v['template_slot_id'],
                'start_date'            => $v['start_date'],
                'end_date'              => $v['end_date'],
                'langue_id'             => $v['langue'],
                'gender_id'             => $v['gender'],
                'budget'                => $v['budget'],
                'compaign_category_id'  =>  1  ?? null,
                'status'                => 1, // reset to Pending after edit
            ]);

            // helper
            $ids = fn ($key) => array_values(array_filter(Arr::wrap($request->input($key))));

            /* -------------------------------------------------
            | 3️⃣ relations many-to-many
            -------------------------------------------------*/
            $compaign->masterMovies()->sync($ids('master_movie_id'));

            $request->has('cinema_chain_id')
                ? $compaign->cinemaChains()->sync($ids('cinema_chain_id'))
                : $compaign->cinemaChains()->detach();

            $request->has('location_id')
                ? $compaign->locations()->sync($ids('location_id'))
                : $compaign->locations()->detach();

            $request->has('movie_genre_id')
                ? $compaign->movieGenres()->sync($ids('movie_genre_id'))
                : $compaign->movieGenres()->detach();

            $request->has('hall_type_id')
                ? $compaign->hallTypes()->sync($ids('hall_type_id'))
                : $compaign->hallTypes()->detach();

            $request->has('target_type')
                ? $compaign->targetTypes()->sync($ids('target_type'))
                : $compaign->targetTypes()->detach();

            $request->has('interest')
                ? $compaign->interests()->sync($ids('interest'))
                : $compaign->interests()->detach();

            /* -------------------------------------------------
            | 4️⃣ slots
            -------------------------------------------------*/
            $slotIds = collect($v['slots'])->pluck('slot_id')->unique()->toArray();
            $compaign->slots()->sync($slotIds);

            /* -------------------------------------------------
            | 5️⃣ DCPs → detach + attach (COMME STORE)
            -------------------------------------------------*/
            $compaign->dcpCreatives()->detach();

            foreach ($v['slots'] as $slotData) {
                foreach ($slotData['dcps'] as $dcp) {
                    $compaign->dcpCreatives()->attach($dcp['dcp_id'], [
                        'slot_id'   => $slotData['slot_id'],
                        'position'  => $dcp['position'],
                        'created_at'=> now(),
                        'updated_at'=> now(),
                    ]);
                }
            }

            /* -------------------------------------------------
            | 6️⃣ regen XML
            -------------------------------------------------*/
            CampaignXmlGenerator::generate($compaign);
           // $response = $this->sendCampaignToNOC($compaign->id);
            ///$response = $this->pushCampaignToNoc($compaign);

            // 6️⃣ Push vers NOC si demandé
            $noc = [];
            if ($request->boolean('send_to_noc')) {
                $noc = $this->pushCampaignToNoc($compaign);
                $allSent  = count($noc) > 0 && collect($noc)->every(fn($r) => $r['sent']);
                $failNote = collect($noc)->where('sent', false)->map(fn($r) => $r['cinema_chain'].': '.($r['reason'] ?? 'failed'))->implode(' | ');
                $compaign->update([
                    'noc_sent' => $allSent,
                    'noc_note' => $allSent ? null : ($failNote ?: null),
                ]);
            }

            return response()->json([
                'message'     => 'Compaign updated successfully.',
                'id'          => $compaign->id,
                'noc_results' => $noc,
            ], 201);
        });
    }


    public function destroy(Compaign $compaign)
    {
        try
        {
            $compaign->delete();
            return response()->json([
                'message' => 'Compaign deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function my_compaigns(Request $request)
    {
        $query = Compaign::where('user_id', Auth()->user()->id)->orderBy('created_at', 'desc');

        if ($request->filled('customer_id')) {
            $query->whereHas('dcpCreatives', fn($q) => $q->where('customer_id', $request->customer_id));
        }

        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $compaigns = $query->get();
        return Response()->json(compact('compaigns'));
    }

    public function advertiser_index()
    {
        $compaign_categories = CompaignCategory::orderBy('name', 'asc')->get() ;
        $compaign_objectives = CompaignObjective::orderBy('name', 'asc')->get() ;
        $langues = Langue::orderBy('name', 'asc')->get() ;
        $locations = Location::orderBy('name', 'asc')->get() ;
        $hall_types = HallType::orderBy('name', 'asc')->get() ;
        $movies = Movie::orderBy('name', 'asc')->get() ;
        $movie_genres = MovieGenre::orderBy('name', 'asc')->get() ;
        $genders = Gender::orderBy('name', 'asc')->get() ;
        $target_types = TargetType::orderBy('name', 'asc')->get() ;
        $interests = Interest::orderBy('name', 'asc')->get() ;
        $slots = Slot::orderBy('name', 'asc')->get() ;
        $dcp_creatives = DcpCreative::orderBy('name', 'asc')->get() ;
        $cinema_chains  = Auth()->user()->cinemaChains()->orderBy('name', 'asc')->get() ;
        $customers = \App\Models\Customer::where('user_id', Auth()->user()->id)->orderBy('name')->get();
        $isDirect  = Auth()->user()->advertiser_type === 'direct';

        return view('advertiser.compaigns.index', compact('compaign_categories','compaign_objectives','langues','locations','hall_types','movies','movie_genres','genders','target_types','interests','slots','dcp_creatives','cinema_chains','customers','isDirect'));
    }

    public function approuve(Compaign $compaign)
    {
        try
        {
            $compaign->update(['status' => 2]);

            $tax      = (float) (Config::first()->tax ?? 6);
            $totalHt  = $compaign->budget;
            $totalTtc = round($totalHt * (1 + $tax / 100), 2);

            $existing = Invoice::where('compaign_id', $compaign->id)->first();

            if ($existing) {
                // Re-approval: keep the same invoice number, update financial details only
                $existing->update([
                    'date'      => now()->toDateString(),
                    'due_date'  => now()->addDays(30)->toDateString(),
                    'tax'       => $tax,
                    'total_ht'  => $totalHt,
                    'total_ttc' => $totalTtc,
                ]);
            } else {
                // First approval: generate a new sequential number for the year
                $year    = now()->format('Y');
                $lastSeq = Invoice::whereYear('date', $year)
                               ->selectRaw("MAX(CAST(SUBSTRING_INDEX(number, '-', -1) AS UNSIGNED)) as max_seq")
                               ->value('max_seq') ?? 0;
                $number  = 'INV-' . $year . '-' . str_pad($lastSeq + 1, 5, '0', STR_PAD_LEFT);

                Invoice::create([
                    'compaign_id' => $compaign->id,
                    'number'      => $number,
                    'date'        => now()->toDateString(),
                    'due_date'    => now()->addDays(30)->toDateString(),
                    'status'      => 'unpaid',
                    'discount'    => 0,
                    'tax'         => $tax,
                    'total_ht'    => $totalHt,
                    'total_ttc'   => $totalTtc,
                ]);
            }

            return response()->json([
                'message' => 'Compaign approuved successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }
    public function reject(Compaign $compaign)
    {
        try
        {
            $compaign->update(['status' => 4]);
            return response()->json([
                'message' => 'Compaign approuved successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function billing(Compaign $compaign)
    {
        $config = Config::first() ;
        $url = rtrim($config->link, '/') . '/api/adsmart/proof_of_play';
        $dcpCreatives = $compaign->dcpCreatives;

        $uuids = $dcpCreatives->pluck('uuid')->toArray();
        try {

            $client = new Client();
            $response = $client->request('GET', $url,[
                'connect_timeout' => 5,
                'query' => [
                    'username' => $config->user,
                    'password' =>$config->password,
                    'uuid' =>  $uuids,
                    'from_date' => $compaign->start_date->toDateString(),
                    'to_date' =>  $compaign->end_date->toDateString()
                ],
            ]);

            $contents = json_decode($response->getBody(), true);

            if($contents)
            {
                if( $contents['status'])
                {

                    //dd($contents['data']);
                    foreach($contents['data'] as $data)
                    {

                    }
                    return response()->json([
                        'status' =>1,
                        'message' => 'Content Retrieved Successfully.',
                    ], 200);
                }
                else
                {
                    return response()->json([
                        'status' =>0,
                        'message' => $contents['message'],
                    ], 200);

                }

            }
            else
            {
                return response()->json([
                    'status' =>0,
                    'message' => "No Data",
                ], 200);
            }

        }
        catch (RequestException $e) {
            // Log de l'erreur ou traitement spécifique
            return response()->json([
                'status' =>0,
                'message' => $e->getMessage(),
            ], 500);

        }
        catch (\Exception $e) {
            // Capture d'autres exceptions générales
            return response()->json([
                'status' =>0,
                'message' => $e->getMessage(),
            ], 500);

        }




    }

    public function advertiser_destory_company(Compaign $compaign)
    {
        try
        {
            if($compaign->user_id == Auth::user()->id)
            {
                $compaign->delete();
                return response()->json([
                    'message' => 'Compaign deleted successfully.',
                ]);
            }
            else
            {
                return response()->json([
                    'message' => 'operation refused.',
                ], 500);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function getAvailableSlotsMonth(Request $request)
    {
        $startDate = now()->format('Y-m-d'); // today
        $endDate = now()->addMonth()->format('Y-m-d'); // one month from today

        $slots = Slot::all();
        $result = [];

        foreach ($slots as $slot) {
            // Calculate total used duration for this slot in the next month
            $usedDuration = Compaign::where('slot_id', $slot->id)
                ->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
                })
                ->with('dcpCreatives', 'movie') // eager load
                ->get()
                ->sum(function($compaign) {
                    return $compaign->dcpCreatives->sum('duration');
                });

            $remainingDuration = max($slot->max_duration - $usedDuration, 0);

            // get targets info
            $targets = Compaign::where('slot_id', $slot->id)
                ->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
                })
                ->with('movie', 'movie.genre')
                ->get()
                ->map(function($c) {
                    return [
                        'movie' => $c->movie->name ?? '-',
                        'genre' => $c->movie->genre->name ?? '-',
                    ];
                });

            $result[] = [
                'slot' => $slot->name,
                'max_duration' => $slot->max_duration,
                'used_duration' => $usedDuration,
                'remaining_duration' => $remainingDuration,
                'targets' => $targets,
            ];
        }

        return response()->json([
            'available_slots' => $result
        ]);
    }

    public function advertiser_builder_index()
    {
        $compaign_categories = CompaignCategory::orderBy('name', 'asc')->get() ;
        $compaign_objectives = CompaignObjective::orderBy('name', 'asc')->get() ;
        $langues = Langue::orderBy('name', 'asc')->get() ;
        $locations = Location::orderBy('name', 'asc')->get() ;
        $hall_types = HallType::orderBy('name', 'asc')->get() ;
        $master_movies = MasterMovie::with('genres')->orderBy('title', 'asc')->get() ;
        $movie_genres = MovieGenre::orderBy('name', 'asc')->get() ;
        $genders = Gender::orderBy('name', 'asc')->get() ;
        $target_types = TargetType::orderBy('name', 'asc')->get() ;
        $interests = Interest::orderBy('name', 'asc')->get() ;
        $slots = Slot::orderBy('name', 'asc')->get() ;
        $dcp_creatives = DcpCreative::where('user_id',Auth()->user()->id)->orderBy('name', 'asc')->get() ;
        $cinema_chains  = Auth()->user()->cinemaChains()->orderBy('name', 'asc')->get() ;
        $slot_templates = TemplateSlot::orderBy('name', 'asc')->get() ;

        return view('advertiser.compaigns.index_builder', compact('compaign_categories','compaign_objectives','langues','locations','hall_types','master_movies','movie_genres','genders','target_types','interests','slots','dcp_creatives','cinema_chains','slot_templates'));
    }

    public function planningSlotsPage()
    {
        $cinema_chains  = CinemaChain::all();
        $slot_templates = TemplateSlot::all();

        return view('admin.compaigns.calendar', compact('cinema_chains', 'slot_templates' ));
    }
    public function planningSlotsList(Request $request)
    {
        $data = Compaign::with(['slots', 'locations'])
            ->select('id', 'name', 'start_date', 'end_date')
            ->get()
            ->flatMap(function ($compaign) {
                return $compaign->slots->map(function ($slot) use ($compaign) {
                    return [
                        'compaign'   => $compaign->name,
                        'slot'       => $slot->name,
                        'start_date' => $compaign->start_date,
                        'end_date'   => $compaign->end_date,
                    ];
                });
            })
            ->values();

        return response()->json([
            'data' => $data
        ]);
    }

    public function planningSlotDcps(Compaign $compaign)
    {
        $compaign->load([
            'slots:id,name',
            'dcpCreatives:id,name,duration',
            'movieGenres:id'
        ]);

        // 🎬 genres choisis dans la campagne
        $genreIds = $compaign->movieGenres->pluck('id')->toArray();


        $movies = Movie::whereIn('movie_genre_id', $genreIds)
          /*  ->whereNotNull('play_at')
            ->whereBetween('play_at', [
                Carbon::parse($compaign->start_date)->startOfDay(),
                Carbon::parse($compaign->end_date)->endOfDay(),
            ])*/
            //->orderBy('play_at')
            ->get(['id', /*'play_at',*/ 'movie_genre_id']);

        // dates de passage
        //$playDates = $movies->pluck('play_at')->values();

        // grouper les DCP par slot
        $grouped = [];

        foreach ($compaign->dcpCreatives as $dcp) {
            $slotId = $dcp->pivot->slot_id;

            if (!isset($grouped[$slotId])) {
                $slot = $compaign->slots->firstWhere('id', $slotId);
                $grouped[$slotId] = [
                    'slot_id'   => $slotId,
                    'slot_name' => $slot?->name ?? 'N/A',
                    'dcps'      => []
                ];
            }

            $grouped[$slotId]['dcps'][] = [
                'id'        => $dcp->id,
                'name'      => $dcp->name ?? ('DCP #' . $dcp->id),
                'duration'  => $dcp->duration,
               // 'play_at'   => $playDates, // 👈 toutes les dates possibles
            ];
        }

        return response()->json([
            'slots' => array_values($grouped)
        ]);
    }

    public function getReservedSlots(Request $request)
    {
        $v = $request->validate([
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'template_slot_id' => 'required|exists:template_slots,id',
            'location_id'      => 'nullable|array',
            'location_id.*'    => 'integer|exists:locations,id',
            'movie_genre_id'   => 'nullable|array',
            'movie_genre_id.*' => 'integer|exists:movie_genres,id',
        ]);

        $start = Carbon::parse($v['start_date'])->startOfDay();
        $end   = Carbon::parse($v['end_date'])->endOfDay();

        $query = DB::table('compaign_slot_dcp as csd')
            ->join('slots as s', 's.id', '=', 'csd.slot_id')
            ->join('template_slots as ts', 'ts.id', '=', 's.template_slot_id')
            ->join('dcp_creatives as d', 'd.id', '=', 'csd.dcp_creative_id')
            ->join('compaigns as c', 'c.id', '=', 'csd.compaign_id')
            ->join('compaign_location as cl', 'cl.compaign_id', '=', 'c.id')
            ->join('locations as l', 'l.id', '=', 'cl.location_id')
            ->join('movies as m', 'm.id', '=', 'c.movie_id')
            ->join('movie_genres as mg', 'mg.id', '=', 'm.movie_genre_id')
            ->where('s.template_slot_id', $v['template_slot_id'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('c.start_date', [$start, $end])
                ->orWhereBetween('c.end_date', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('c.start_date', '<=', $start)
                        ->where('c.end_date', '>=', $end);
                });
            });

        if (!empty($v['location_id'])) {
            $query->whereIn('cl.location_id', $v['location_id']);
        }

        if (!empty($v['movie_genre_id'])) {
            $query->whereIn('m.movie_genre_id', $v['movie_genre_id']);
        }

        $rows = $query->select(
                's.name as slot_name',
                'd.name as dcp_name',
                'd.duration',
                'c.name as compaign_name',
                'm.title as movie_title',
                'mg.name as genre_name',
                'm.play_date',
                'l.name as location_name'
            )
         //   ->orderBy('m.play_date')
            ->get();

        return response()->json(['data' => $rows]);
    }

    public function getAllReservedSlots()
    {
        $rows = DB::table('compaign_slot_dcp as csd')
            ->join('slots as s', 's.id', '=', 'csd.slot_id')
            ->join('dcp_creatives as d', 'd.id', '=', 'csd.dcp_creative_id')
            ->join('compaigns as c', 'c.id', '=', 'csd.compaign_id')
            ->join('compaign_location as cl', 'cl.compaign_id', '=', 'c.id')
            ->join('locations as l', 'l.id', '=', 'cl.location_id')
            ->join('movies as m', 'm.id', '=', 'c.movie_id')
            ->join('movie_genres as mg', 'mg.id', '=', 'm.movie_genre_id')
            ->select(
                's.name as slot_name',
                'd.name as dcp_name',
                'd.duration',
                'c.name as compaign_name',
                'm.name as movie_title',
                'mg.name as genre_name',
              //  'm.play_at',
                'l.name as location_name'
            )
            ->orderBy('m.play_at', 'desc')
            ->get();

        return response()->json(['data' => $rows]);
    }


    /*public function sendCampaignToNOC($campaignId)
    {
        try {

            $client = new Client();
            $config = Config::first() ;
            $url = rtrim($config->link, '/') . '/api/adsmart/create_template';

            $campaign = Compaign::findOrFail($campaignId);

            // Payload à envoyer
            $payload = [
                'campaign_id'   => $campaign->id,
                'campaign_name' => $campaign->name,
                'start_date'    => $campaign->start_date,
                'end_date'      => $campaign->end_date,
                'budget'        => $campaign->budget,
            ];


            $response = $client->request('POST', $url,[
                'connect_timeout' => 5,
                'query' => [
                    'content' =>$payload,
                    'username' => $config->user,
                    'password' =>$config->password,
                ],
            ]);



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
    }*/

}
