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

    /*public function show(Request $request)
    {
        $compaign = Compaign::findOrFail($request->id) ;
        $compaign->load([
            'compaignObjective:id,name',
            'compaignCategory:id,name',
            'langue:id,name',
            'movie' => function ($q) {
                $q->withTrashed()->select('id', 'name');
            },
            'gender:id,name',
            'slot:id,name',
            'brands:id,name',
            'locations:id,name',
            'hallTypes:id,name',
            'movieGenres:id,name',
            'interests:id,name',
            'targetTypes:id,name',
        ]);

        return response()->json($compaign);

        $compaign = Compaign::findOrFail($request->id) ;

        return Response()->json(compact('compaign'));
    } */

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

    /*public function store(Request $request)
    {

        $v = $request->validate([
            'name'               => 'required|string|max:255',
            'compaign_objective' => 'required|integer|exists:compaign_objectives,id',
            'compaign_category'  => 'required|integer|exists:compaign_categories,id',
            'start_date'         => 'nullable|date',
            'end_date'           => 'nullable|date|after_or_equal:start_date',
            'budget'             => 'nullable|integer',
            'langue'             => 'required|integer|exists:langues,id',
            'note'               => 'nullable|string',
            'movie'              => 'required|integer|exists:movies,id',
            'gender'             => 'required|integer|exists:genders,id',
            'slot'               => 'required|integer|exists:slots,id',
            'duration'           => 'required|integer|in:15,30,45,60',

            // many-to-many (tableaux)
            'brand'        => 'array',
            'brand.*'      => 'integer|exists:brands,id',
            'location'     => 'array',
            'location.*'   => 'integer|exists:locations,id',
            'hall_type'    => 'array',
            'hall_type.*'  => 'integer|exists:hall_types,id',
            'movie_genre'  => 'array',
            'movie_genre.*'=> 'integer|exists:movie_genres,id',
            'interest'     => 'array',
            'interest.*'   => 'integer|exists:interests,id',
            'dcp_creative'   =>'array',
            // peut être int (single) ou array, on gère les deux
            'target_type'  => 'nullable',
        ]);

        return DB::transaction(function () use ($v, $request) {
            // 1) INSERT dans compaigns
            $compaign = Compaign::create([
                'name'                  => $v['name'],
                'compaign_objective_id' => $v['compaign_objective'],
                'compaign_category_id'  => $v['compaign_category'],
                'start_date'            => $v['start_date'] ?? null,
                'end_date'              => $v['end_date'] ?? null,
                'budget'                => $v['budget'] ?? null,
                'langue_id'             => $v['langue'],
                'note'                  => $v['note'] ?? null,
                'movie_id'              => $v['movie'],
                'gender_id'             => $v['gender'],
                'slot_id'               => $v['slot'],
                'ad_duration'           => $v['duration'], // colonne en DB
                'user_id' => Auth::user()->id,
                'status' =>1
            ]);


            // 2) SYNC des pivots (prend en charge [] et valeur simple)
            $ids = fn ($key) => array_values(array_filter(Arr::wrap($request->input($key))));

            $compaign->brands()->sync($ids('brand'));
            $compaign->locations()->sync($ids('location'));
            $compaign->hallTypes()->sync($ids('hall_type'));
            $compaign->movieGenres()->sync($ids('movie_genre'));
            $compaign->interests()->sync($ids('interest'));
            $compaign->targetTypes()->sync($ids('target_type'));
            $compaign->dcpCreatives()->sync($ids('dcp_creative'));

            return response()->json([
                'message' => 'Compaign created successfully.',
                'id'      => $compaign->id,
            ], 201);
        });
    }*/

    public function store(Request $request)
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
        ]);

        return DB::transaction(function () use ($v, $request) {

            // 1️⃣ create compaign
            $compaign = Compaign::create([
                'name'             => $v['compaign_name'],
                'template_slot_id' => $v['template_slot_id'],
                'compaign_objective_id' =>1,
                'langue_id'             => 1,
                'movie_id'              => 6,
                'gender_id'             => 1,
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

            return response()->json([
                'message' => 'Compaign created successfully.',
                'id'      => $compaign->id,
            ], 201);
        });
    }

    public function update(Request $request, Compaign $compaign)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $compaign->update($validated);
            return response()->json([
                'message' => 'Compaign updated successfully.',
                'data' => $compaign,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
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
        $compaigns = Compaign::where('user_id',Auth()->user()->id)->orderBy('name', 'asc')->get();
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
        $dcp_creatives = DcpCreative::orderBy('name', 'asc')->get() ;
        $cinema_chains  = CinemaChain::orderBy('name', 'asc')->get() ;
        $slot_templates = TemplateSlot::orderBy('name', 'asc')->get() ;

        return view('advertiser.compaigns.index_builder', compact('compaign_categories', 'brands','compaign_objectives','langues','locations','hall_types','movies','movie_genres','genders','target_types','interests','slots','dcp_creatives','cinema_chains','slot_templates'));
    }

    public function edit($id)
    {

        $compaign = Compaign::with([
            'brands:id',
            'locations:id',
            'hallTypes:id',
            'movieGenres:id',
            'interests:id',
            'targetTypes:id',
            'dcpCreatives:id,duration',
            'slots:id',
            'templateSlot:id',
        ])->findOrFail($id);


        // mêmes données que create()
        $dcp_creatives = DcpCreative::where('status', 1)->get();

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
        $slot_templates = TemplateSlot::orderBy('name', 'asc')->get() ;

        return view('advertiser.compaigns.edit_builder', compact(
            'compaign',
            'dcp_creatives',
            'compaign_categories', 'brands','compaign_objectives','langues','locations','hall_types','movies','movie_genres','genders','target_types','interests','slots','dcp_creatives','cinema_chains','slot_templates'
        ));
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
