<?php

namespace App\Http\Controllers;

use App\Models\Brand;
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

    public function show(Request $request)
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
    }

    public function get()
    {
        $compaigns = Compaign::with('user')->orderBy('name', 'asc')->get();
        return Response()->json(compact('compaigns'));
    }

    public function store(Request $request)
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


        return view('advertiser.compaigns.index', compact('compaign_categories', 'brands','compaign_objectives','langues','locations','hall_types','movies','movie_genres','genders','target_types','interests','slots','dcp_creatives'));
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

}
