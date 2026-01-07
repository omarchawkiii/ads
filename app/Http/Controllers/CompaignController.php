<?php

namespace App\Http\Controllers;

use App\Models\Brand;
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
use App\Models\Movie;
use App\Models\MovieGenre;
use App\Models\Slot;
use App\Models\TargetType;
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


class CompaignController extends Controller
{
    public function index()
    {
        $compaign_categories = CompaignCategory::orderBy('name', 'asc')->get() ;
        $brands = Brand::orderBy('name', 'asc')->get() ;
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

        return view('admin.compaigns.index', compact('compaign_categories', 'brands','compaign_objectives','langues','locations','hall_types','movies','movie_genres','genders','target_types','interests','slots','dcp_creatives'));
    }



    public function show($id)
    {
        $compaign = Compaign::with([
            'compaignObjective:id,name',
            'compaignCategory:id,name',
            'langue:id,name',
            'movies' => function ($q) {
                $q->withTrashed()
                  ->select('movies.id', 'movies.name');
            },
            'gender:id,name',
            'templateSlot:id,name',
            'slots:id,name,max_duration',
            'dcpCreatives:id,name,duration',
            'brands:id,name',
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
        $compaigns = Compaign::with('user')->orderBy('name', 'asc')->get();
        return Response()->json(compact('compaigns'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([

            'compaign_name'       => 'required|string|max:255',
            'template_slot_id'    => 'required|integer|exists:template_slots,id',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',

            // filtres
            'compaign_category_id' => 'nullable|integer|exists:compaign_categories,id',

            // 🔵 cinema chains (many-to-many)
            'cinema_chain_id'      => 'nullable|array',
            'cinema_chain_id.*'    => 'integer|exists:cinema_chains,id',

            'location_id'          => 'array',
            'location_id.*'        => 'integer|exists:locations,id',

            'movie_genre_id'       => 'array',
            'movie_genre_id.*'     => 'integer|exists:movie_genres,id',

            'movie_id'             => 'required|array|min:1',
            'movie_id.*'           => 'integer|exists:movies,id',

            'hall_type_id'         => 'array',
            'hall_type_id.*'       => 'integer|exists:hall_types,id',

            // slots + dcps
            'slots'                       => 'required|array|min:1',
            'slots.*.slot_id'             => 'required|integer|exists:slots,id',
            'slots.*.dcps'                => 'required|array|min:1',
            'slots.*.dcps.*.position'     => 'required|integer|min:1',
            'slots.*.dcps.*.dcp_id'       => 'required|integer|exists:dcp_creatives,id',

            'budget'          => 'required|integer|min:1',

            'langue'          => 'required|integer|exists:langues,id',
            'gender'          => 'required|integer|exists:genders,id',

            'target_type'     => 'array',
            'target_type.*'   => 'integer|exists:target_types,id',

            'interest'        => 'array',
            'interest.*'      => 'integer|exists:interests,id',
        ]);

        return DB::transaction(function () use ($v, $request) {

            // 1️⃣ Création de la campagne (sans cinema_chain_id)
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

            // helper
            $ids = fn ($key) => array_values(array_filter(Arr::wrap($request->input($key))));

            // 2️⃣ relations simples
            $compaign->movies()->sync($ids('movie_id'));

            if ($request->has('cinema_chain_id')) {
                $compaign->cinemaChains()->sync($ids('cinema_chain_id'));
            }

            if ($request->has('location_id')) {
                $compaign->locations()->sync($ids('location_id'));
            }

            if ($request->has('movie_genre_id')) {
                $compaign->movieGenres()->sync($ids('movie_genre_id'));
            }

            if ($request->has('hall_type_id')) {
                $compaign->hallTypes()->sync($ids('hall_type_id'));
            }

            if ($request->has('target_type')) {
                $compaign->targetTypes()->sync($ids('target_type'));
            }

            if ($request->has('interest')) {
                $compaign->interests()->sync($ids('interest'));
            }

            // 3️⃣ slots
            $slotIds = collect($v['slots'])->pluck('slot_id')->unique()->toArray();
            $compaign->slots()->sync($slotIds);

            // 4️⃣ DCPs avec slot_id (⚠️ duration vient de dcp_creatives)

            $compaign->dcpCreatives()->detach();

            foreach ($v['slots'] as $slotData) {
                foreach ($slotData['dcps'] as $dcp) {
                    $compaign->dcpCreatives()->attach($dcp['dcp_id'], [
                        'slot_id' => $slotData['slot_id'],
                        'position' => $dcp['position'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }


            // 5️⃣ Génération XML
            CampaignXmlGenerator::generate($compaign);

            return response()->json([
                'message' => 'Compaign created successfully.',
                'id'      => $compaign->id,
            ], 201);
        });
    }

    public function store_(Request $request)
    {
        $v = $request->validate([
            'campaign_name' => "test",
            'compaign_name'    => 'required|string|max:255',
            'template_slot_id' => 'required|integer|exists:template_slots,id',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',

            // filtres
            'compaign_category_id' => 'nullable|integer|exists:compaign_categories,id',
            'cinema_chain_id'      => 'nullable|integer|exists:cinema_chains,id',
            'location_id'         => 'array',
            'location_id.*'       => 'integer|exists:locations,id',
            'movie_genre_id'      => 'array',
            'movie_genre_id.*'    => 'integer|exists:movie_genres,id',

            'movie_id'             => 'required|array|min:1',
            'movie_id.*'           => 'integer|exists:movies,id',

            'hall_type_id'    => 'array',
            'hall_type_id.*'  => 'integer|exists:hall_types,id',

            // slots + dcps
            'slots'                     => 'required|array|min:1',
            'slots.*.slot_id'           => 'required|integer|exists:slots,id',
            'slots.*.dcps'              => 'required|array|min:1',
            'slots.*.dcps.*.dcp_id'     => 'required|integer|exists:dcp_creatives,id',
            'slots.*.dcps.*.duration'   => 'required|integer|min:1',

            'budget'            => 'required|integer|min:1',


            'langue'            => 'required',
            'langue.*'          => 'integer|exists:langues,id',

            'gender'            => 'required',
            'gender.*'          => 'integer|exists:genders,id',

            'target_type'        => 'array',
            'target_type.*'      => 'integer|exists:target_types,id',

            'interest'        => 'array',
            'interest.*'      => 'integer|exists:interests,id',



        ]);

        return DB::transaction(function () use ($v, $request) {

            // 1️⃣ create compaign
            $compaign = Compaign::create([
                'name'             => $v['compaign_name'],
                'template_slot_id' => $v['template_slot_id'],
                'langue_id'             => $v['langue'],
                'gender_id'             => $v['gender'],
                'budget'             => $v['budget'],
                'compaign_objective_id' =>1,
                'movie_id'              => 6,
                'slot_id'               => null,
                'ad_duration'           => 30,
                'start_date'       => $v['start_date'],
                'end_date'         => $v['end_date'],
                'compaign_category_id' => 1,
                'cinema_chain_id'      => $v['cinema_chain_id'] ?? null,
                'user_id'          => Auth::id(),
                'status'           => 1,
            ]);




            // helper pour arrays
            $ids = fn ($key) => array_values(array_filter(Arr::wrap($request->input($key))));
            $compaign->movies()->sync($ids('movie_id'));



            if ($request->has('location_id')) {
                $compaign->locations()->sync($ids('location_id'));
            }

            if ($request->has('movie_genre_id')) {
                $compaign->movieGenres()->sync($ids('movie_genre_id'));
            }


            if ($request->has('hall_type_id')) {

                $compaign->hallTypes()->sync($ids('hall_type_id'));
            }

            if ($request->has('target_type')) {

                $compaign->targetTypes()->sync($ids('target_type'));
            }

            if ($request->has('interest')) {

                $compaign->interests()->sync($ids('interest'));
            }


            // 3️⃣ attach slots (compaign_slot)
            $slotIds = collect($v['slots'])->pluck('slot_id')->unique()->toArray();
            $compaign->slots()->sync($slotIds);

           // 4️⃣ attach dcps with slot_id (compaign_slot_dcp)
            $pivotData = [];

            foreach ($v['slots'] as $slotData) {
                $slotId = $slotData['slot_id'];

                foreach ($slotData['dcps'] as $dcp) {
                    $dcpId = $dcp['dcp_id'];

                    $pivotData[$dcpId] = [
                        'slot_id' => $slotId,
                        // 'duration' => $dcp['duration'], // ajoute-le ici si la colonne existe
                    ];
                }
            }

            // sync sur la relation dcpCreatives
            $compaign->dcpCreatives()->sync($pivotData);

            $xmlPath = CampaignXmlGenerator::generate($compaign);


            return response()->json([
                'message' => 'Compaign created successfully.',
                'id'      => $compaign->id,
            ], 201);
        });
    }

    public function edit_($id)
    {
        $compaign = Compaign::with([
            'movies:id',
            'locations:id',
            'movieGenres:id',
            'hallTypes:id',
            'cinemaChains:id',
            'slots:id,template_slot_id,name,max_duration',
            'dcpCreatives:id',
        ])->findOrFail($id);

        //$dcp_creatives = DcpCreative::where('status', 1)->get();
        $dcp_creatives = DcpCreative::where('status', 1)->where('user_id',Auth()->user()->id)->orderBy('name', 'asc')->get() ;

        // 🔹 Charger tous les TemplateSlots avec leurs slots
        $templateSlots = TemplateSlot::with('slots')
            ->where('id', $compaign->template_slot_id)
            ->orderBy('name')
            ->get();

        $slotsPayload = [];

        // 🔹 Calculer le used pour chaque slot selon les règles de getAvailableSlots() pour les autres campagnes
        $slotIds = $compaign->slots->pluck('id');

        $usedBySlotQuery = DB::table('compaign_slot_dcp as csd')
            ->join('compaigns as c', 'c.id', '=', 'csd.compaign_id')
            ->join('dcp_creatives as d', 'd.id', '=', 'csd.dcp_creative_id')
            ->join('compaign_location as cl', 'cl.compaign_id', '=', 'c.id')
            ->whereIn('csd.slot_id', $slotIds)
            ->whereIn('cl.location_id', $compaign->locations->pluck('id')->toArray())
            ->where(function ($q) use ($compaign) {
                $start = $compaign->start_date;
                $end   = $compaign->end_date;
                $q->whereBetween('c.start_date', [$start, $end])
                  ->orWhereBetween('c.end_date', [$start, $end])
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->where('c.start_date', '<=', $start)
                         ->where('c.end_date', '>=', $end);
                  });
            })
            ->where('c.id', '!=', $compaign->id) // ignorer la campagne en édition
            ->select('csd.slot_id', DB::raw('SUM(d.duration) as used'))
            ->groupBy('csd.slot_id');

        $usedBySlot = $usedBySlotQuery->pluck('used', 'csd.slot_id');

        // 🔹 Ajouter les DCP existants dans la campagne
        foreach ($compaign->dcpCreatives as $dcp) {
            $slotId = $dcp->pivot->slot_id;
            $dcp_data= DcpCreative::find($dcp->id);
            if (!isset($slotsPayload[$slotId])) {
                $slot = $compaign->slots->firstWhere('id', $slotId);
                $slotsPayload[$slotId] = [
                    'slot_id' => $slotId,
                    'name' => $slot->name ?? 'Slot ' . $slotId,
                    'max_duration' => $slot->max_duration ?? 0,
                    'dcps' => [],
                ];
            }

            $slotsPayload[$slotId]['dcps'][] = [
                'dcp_id' => $dcp->id,
                'duration' => (int) $dcp_data->duration, // prendre la durée depuis DcpCreative
            ];
        }

        // 🔹 Compléter tous les slots du template avec calcul du remaining
        foreach ($templateSlots as $tpl) {
            foreach ($tpl->slots as $slot) {
                $assignedDuration = array_sum(array_column($slotsPayload[$slot->id]['dcps'] ?? [], 'duration'));
                $used = (int) ($usedBySlot[$slot->id] ?? 0);
                $remaining = max(0, $slot->max_duration - $used); // ne pas compter la campagne actuelle

                $slotsPayload[$slot->id] = [
                    'slot_id' => $slot->id,
                    'name' => $slot->name,
                    'max_duration' => $slot->max_duration,
                    'dcps' => $slotsPayload[$slot->id]['dcps'] ?? [],
                    'used' => $used + $assignedDuration, // info totale pour affichage
                    'remaining' => $remaining,
                ];
            }
        }

        return view('advertiser.compaigns.edit_builder', [
            'compaign' => $compaign,
            'slotsData' => array_values($slotsPayload),
            'dcp_creatives' => $dcp_creatives,
            'slot_templates' => $templateSlots,
            'isEdit' => true,
            'selectedLocations'    => $compaign->locations->pluck('id')->toArray(),

            // 🔹 Données pour les selects
            'compaign_categories' => CompaignCategory::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'compaign_objectives' => CompaignObjective::orderBy('name')->get(),
            'langues' => Langue::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
            'hall_types' => HallType::orderBy('name')->get(),
            'movies' => Movie::orderBy('name')->get(),
            'movie_genres' => MovieGenre::orderBy('name')->get(),
            'slot_templates' => TemplateSlot::orderBy('name', 'asc')->get() ,
            'genders' => Gender::orderBy('name')->get(),
            'target_types' => TargetType::orderBy('name')->get(),
            'interests' => Interest::orderBy('name')->get(),
            'slots' => Slot::orderBy('name')->get(),
            'cinema_chains' => CinemaChain::orderBy('name')->get(),
        ]);
    }

    public function edit__($id)
    {
        $compaign = Compaign::with([
            'movies:id',
            'locations:id',
            'movieGenres:id',
            'hallTypes:id',
            'cinemaChains:id',
            'slots:id,template_slot_id,name,max_duration,max_ad_slot',
            'dcpCreatives:id',
        ])->findOrFail($id);

        $dcp_creatives = DcpCreative::where('status', 1)
            ->where('user_id', Auth()->user()->id)
            ->orderBy('name', 'asc')
            ->get();

        // 🔹 Charger le template slot avec tous les slots
        $templateSlots = TemplateSlot::with('slots')
            ->where('id', $compaign->template_slot_id)
            ->orderBy('name')
            ->get();

        $slotsPayload = [];

        $slotIds = $compaign->slots->pluck('id')->toArray();
        $locationIds = $compaign->locations->pluck('id')->toArray();

        // 🔹 Calculer le used et le nombre de DCP assignés par slot (autres campagnes)
        $usedBySlotQuery = DB::table('compaign_slot_dcp as csd')
            ->join('compaigns as c', 'c.id', '=', 'csd.compaign_id')
            ->join('dcp_creatives as d', 'd.id', '=', 'csd.dcp_creative_id')
            ->join('compaign_location as cl', 'cl.compaign_id', '=', 'c.id')
            ->whereIn('csd.slot_id', $slotIds)
            ->whereIn('cl.location_id', $locationIds)
            ->where(function ($q) use ($compaign) {
                $start = $compaign->start_date;
                $end   = $compaign->end_date;
                $q->whereBetween('c.start_date', [$start, $end])
                ->orWhereBetween('c.end_date', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('c.start_date', '<=', $start)
                        ->where('c.end_date', '>=', $end);
                });
            })
            ->where('c.id', '!=', $compaign->id)
            ->select(
                'csd.slot_id',
                DB::raw('SUM(d.duration) as used_duration'),
                DB::raw('COUNT(csd.dcp_creative_id) as assigned_dcp_count')
            )
            ->groupBy('csd.slot_id');

        $usedBySlot = $usedBySlotQuery->get()->keyBy('slot_id');

        // 🔹 Ajouter les DCP existants dans la campagne
        foreach ($compaign->dcpCreatives as $dcp) {
            $slotId = $dcp->pivot->slot_id;
            $dcpData = DcpCreative::find($dcp->id);

            if (!isset($slotsPayload[$slotId])) {
                $slot = $compaign->slots->firstWhere('id', $slotId);
                $slotsPayload[$slotId] = [
                    'slot_id' => $slotId,
                    'name' => $slot->name ?? 'Slot ' . $slotId,
                    'max_duration' => $slot->max_duration ?? 0,
                    'max_ad_slot' => $slot->max_ad_slot ?? 0,
                    'dcps' => [],
                ];
            }

            $slotsPayload[$slotId]['dcps'][] = [
                'dcp_id' => $dcp->id,
                'duration' => (int) $dcpData->duration,
            ];
        }

        // 🔹 Compléter tous les slots du template avec calcul du remaining et du remaining DCP
        foreach ($templateSlots as $tpl) {
            foreach ($tpl->slots as $slot) {
                $assignedDuration = array_sum(array_column($slotsPayload[$slot->id]['dcps'] ?? [], 'duration'));
                $assignedDcpCount = count($slotsPayload[$slot->id]['dcps'] ?? []);

                $usedData = $usedBySlot[$slot->id] ?? null;
                $usedDurationOther = (int) ($usedData->used_duration ?? 0);
                $assignedDcpOther = (int) ($usedData->assigned_dcp_count ?? 0);

                $remainingDuration = max(0, $slot->max_duration - $usedDurationOther);
                $remainingDcp = max(0, $slot->max_ad_slot - $assignedDcpOther);

                $slotsPayload[$slot->id] = [
                    'slot_id' => $slot->id,
                    'name' => $slot->name,
                    'max_duration' => $slot->max_duration,
                    'max_ad_slot' => $slot->max_ad_slot,
                    'dcps' => $slotsPayload[$slot->id]['dcps'] ?? [],
                    'used_duration' => $usedDurationOther + $assignedDuration,
                    'assigned_dcp' => $assignedDcpOther + $assignedDcpCount,
                    'remaining_duration' => $remainingDuration,
                    'remaining_dcp' => $remainingDcp,
                ];


            }
        }

        return view('advertiser.compaigns.edit_builder', [
            'compaign' => $compaign,
            'slotsData' => array_values($slotsPayload),
            'dcp_creatives' => $dcp_creatives,
            'slot_templates' => $templateSlots,
            'isEdit' => true,
            'selectedLocations' => $locationIds,

            // 🔹 Données pour les selects
            'compaign_categories' => CompaignCategory::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'compaign_objectives' => CompaignObjective::orderBy('name')->get(),
            'langues' => Langue::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
            'hall_types' => HallType::orderBy('name')->get(),
            'movies' => Movie::orderBy('name')->get(),
            'movie_genres' => MovieGenre::orderBy('name')->get(),
            'slot_templates' => TemplateSlot::orderBy('name', 'asc')->get(),
            'genders' => Gender::orderBy('name')->get(),
            'target_types' => TargetType::orderBy('name')->get(),
            'interests' => Interest::orderBy('name')->get(),
            'slots' => Slot::orderBy('name')->get(),
            'cinema_chains' => CinemaChain::orderBy('name')->get(),
        ]);
    }
    public function edit($id)
    {
        /* =====================================================
        | 0️⃣ Charger la campagne
        ===================================================== */
        $compaign = Compaign::with([
            'movies:id',
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
            'brands'              => Brand::orderBy('name')->get(),
            'compaign_objectives' => CompaignObjective::orderBy('name')->get(),
            'langues'             => Langue::orderBy('name')->get(),
            'locations'           => Location::orderBy('name')->get(),
            'hall_types'          => HallType::orderBy('name')->get(),
            'movies'              => Movie::orderBy('name')->get(),
            'movie_genres'        => MovieGenre::orderBy('name')->get(),
            'genders'             => Gender::orderBy('name')->get(),
            'target_types'        => TargetType::orderBy('name')->get(),
            'interests'           => Interest::orderBy('name')->get(),
            'slots'               => Slot::orderBy('name')->get(),
            'cinema_chains'       => CinemaChain::orderBy('name')->get(),
        ]);
    }




    public function update_(Request $request, $id)
    {
        $v = $request->validate([
            'compaign_name'    => 'required|string|max:255',
            'template_slot_id' => 'required|integer|exists:template_slots,id',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',

            // filtres
            'compaign_category_id' => 'nullable|integer|exists:compaign_categories,id',
            'cinema_chain_id'      => 'nullable|integer|exists:cinema_chains,id',

            'location_id'         => 'array',
            'location_id.*'       => 'integer|exists:locations,id',

            'movie_genre_id'      => 'array',
            'movie_genre_id.*'    => 'integer|exists:movie_genres,id',

            'movie_id'            => 'required|array|min:1',
            'movie_id.*'          => 'integer|exists:movies,id',

            'budget'            => 'required|integer|min:1',

            'langue'            => 'required',
            'langue.*'          => 'integer|exists:langues,id',

            'gender'            => 'required',
            'gender.*'          => 'integer|exists:genders,id',

            'hall_type_id'        => 'array',
            'hall_type_id.*'      => 'integer|exists:hall_types,id',

            'target_type'        => 'array',
            'target_type.*'      => 'integer|exists:target_types,id',

            // slots + dcps
            'slots'                     => 'required|array|min:1',
            'slots.*.slot_id'           => 'required|integer|exists:slots,id',
            'slots.*.dcps'              => 'required|array|min:1',
            'slots.*.dcps.*.dcp_id'     => 'required|integer|exists:dcp_creatives,id',
            'slots.*.dcps.*.duration'   => 'required|integer|min:1',

            'interest'        => 'array',
            'interest.*'      => 'integer|exists:interests,id',

        ]);

        return DB::transaction(function () use ($v, $request, $id) {

            // 1️⃣ récupérer la campagne
            $compaign = Compaign::findOrFail($id);

            // 2️⃣ update compaign
            $compaign->update([
                'name'             => $v['compaign_name'],
                'template_slot_id' => $v['template_slot_id'],
                'start_date'       => $v['start_date'],
                'end_date'         => $v['end_date'],
                'cinema_chain_id'  => $v['cinema_chain_id'] ?? null,
                'langue_id'             => $v['langue'],
                'gender_id'             => $v['gender'],
                'budget'             => $v['budget'],
            ]);

            // helper array propre
            $ids = fn ($key) => array_values(array_filter(Arr::wrap($request->input($key))));

            // 3️⃣ sync relations simples
            $compaign->movies()->sync($ids('movie_id'));

            $request->has('location_id')
                ? $compaign->locations()->sync($ids('location_id'))
                : $compaign->locations()->detach();

            $request->has('movie_genre_id')
                ? $compaign->movieGenres()->sync($ids('movie_genre_id'))
                : $compaign->movieGenres()->detach();

            $request->has('hall_type_id')
                ? $compaign->hallTypes()->sync($ids('hall_type_id'))
                : $compaign->hallTypes()->detach();

            $request->has('interest')
                ? $compaign->interests()->sync($ids('interest'))
                : $compaign->interests()->detach();

            $request->has('target_type')
                ? $compaign->targetTypes()->sync($ids('target_type'))
                : $compaign->targetTypes()->detach();

            // 4️⃣ sync slots (compaign_slot)
            $slotIds = collect($v['slots'])->pluck('slot_id')->unique()->toArray();
            $compaign->slots()->sync($slotIds);

            // 5️⃣ sync dcps avec slot_id (compaign_slot_dcp)
            $pivotData = [];

            foreach ($v['slots'] as $slotData) {
                $slotId = $slotData['slot_id'];

                foreach ($slotData['dcps'] as $dcp) {
                    $pivotData[$dcp['dcp_id']] = [
                        'slot_id' => $slotId,
                    ];
                }
            }

            $compaign->dcpCreatives()->sync($pivotData);
            $xmlPath = CampaignXmlGenerator::generate($compaign);

            return response()->json([
                'message' => 'Compaign updated successfully.',
                'id'      => $compaign->id,
            ]);
        });
    }

    public function update__(Request $request, $id)
    {
        $v = $request->validate([

            'compaign_name'       => 'required|string|max:255',
            'template_slot_id'    => 'required|integer|exists:template_slots,id',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',

            // filtres
            'compaign_category_id' => 'nullable|integer|exists:compaign_categories,id',

            // 🔵 cinema chains (many-to-many)
            'cinema_chain_id'      => 'nullable|array',
            'cinema_chain_id.*'    => 'integer|exists:cinema_chains,id',

            'location_id'          => 'array',
            'location_id.*'        => 'integer|exists:locations,id',

            'movie_genre_id'       => 'array',
            'movie_genre_id.*'     => 'integer|exists:movie_genres,id',

            'movie_id'             => 'required|array|min:1',
            'movie_id.*'           => 'integer|exists:movies,id',

            'budget'               => 'required|integer|min:1',

            'langue'               => 'required|integer|exists:langues,id',
            'gender'               => 'required|integer|exists:genders,id',

            'hall_type_id'         => 'array',
            'hall_type_id.*'       => 'integer|exists:hall_types,id',

            'target_type'          => 'array',
            'target_type.*'        => 'integer|exists:target_types,id',

            // slots + dcps
            'slots'                       => 'required|array|min:1',
            'slots.*.slot_id'             => 'required|integer|exists:slots,id',
            'slots.*.dcps'                => 'required|array|min:1',
            'slots.*.dcps.*.dcp_id'       => 'required|integer|exists:dcp_creatives,id',

            'interest'                    => 'array',
            'interest.*'                  => 'integer|exists:interests,id',
        ]);

        return DB::transaction(function () use ($v, $request, $id) {

            // 1️⃣ récupérer la campagne
            $compaign = Compaign::findOrFail($id);

            // 2️⃣ update champs simples (⚠️ plus de cinema_chain_id ici)
            $compaign->update([
                'name'                  => $v['compaign_name'],
                'template_slot_id'      => $v['template_slot_id'],
                'start_date'            => $v['start_date'],
                'end_date'              => $v['end_date'],
                'langue_id'             => $v['langue'],
                'gender_id'             => $v['gender'],
                'budget'                => $v['budget'],
                'compaign_category_id'  => 1 ?? null,
            ]);

            // helper
            $ids = fn ($key) => array_values(array_filter(Arr::wrap($request->input($key))));

            // 3️⃣ relations many-to-many
            $compaign->movies()->sync($ids('movie_id'));

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

            // 4️⃣ slots
            $slotIds = collect($v['slots'])->pluck('slot_id')->unique()->toArray();
            $compaign->slots()->sync($slotIds);

            // 5️⃣ dcps avec slot_id (duration vient de dcp_creatives)
            $pivotData = [];

            foreach ($v['slots'] as $slotData) {
                foreach ($slotData['dcps'] as $dcp) {
                    $pivotData[$dcp['dcp_id']] = [
                        'slot_id' => $slotData['slot_id'],
                    ];
                }
            }

            $compaign->dcpCreatives()->sync($pivotData);

            // 6️⃣ regen XML
            CampaignXmlGenerator::generate($compaign);

            return response()->json([
                'message' => 'Compaign updated successfully.',
                'id'      => $compaign->id,
            ]);
        });
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

            'movie_id'             => 'required|array|min:1',
            'movie_id.*'           => 'integer|exists:movies,id',

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
            ]);

            // helper
            $ids = fn ($key) => array_values(array_filter(Arr::wrap($request->input($key))));

            /* -------------------------------------------------
            | 3️⃣ relations many-to-many
            -------------------------------------------------*/
            $compaign->movies()->sync($ids('movie_id'));

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

            return response()->json([
                'message' => 'Compaign updated successfully.',
                'id'      => $compaign->id,
            ]);
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

    public function my_compaigns()
    {
        $compaigns = Compaign::where('user_id',Auth()->user()->id)->orderBy('created_at', 'desc')->get();
        return Response()->json(compact('compaigns'));
    }


    public function advertiser_index()
    {
        $compaign_categories = CompaignCategory::orderBy('name', 'asc')->get() ;
        $brands = Brand::orderBy('name', 'asc')->get() ;
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
        $cinema_chains  = CinemaChain::orderBy('name', 'asc')->get() ;

        return view('advertiser.compaigns.index', compact('compaign_categories', 'brands','compaign_objectives','langues','locations','hall_types','movies','movie_genres','genders','target_types','interests','slots','dcp_creatives','cinema_chains'));
    }

    public function approuve(Compaign $compaign)
    {
        try
        {
            $compaign->update(['status' => 2]);
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

                    dd($contents['data']);
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
        $brands = Brand::orderBy('name', 'asc')->get() ;
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
        //$dcp_creatives = DcpCreative::orderBy('name', 'asc')->get() ;
        $dcp_creatives = DcpCreative::where('user_id',Auth()->user()->id)->orderBy('name', 'asc')->get() ;
        $cinema_chains  = CinemaChain::orderBy('name', 'asc')->get() ;
        $slot_templates = TemplateSlot::orderBy('name', 'asc')->get() ;

        return view('advertiser.compaigns.index_builder', compact('compaign_categories', 'brands','compaign_objectives','langues','locations','hall_types','movies','movie_genres','genders','target_types','interests','slots','dcp_creatives','cinema_chains','slot_templates'));
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

}
