<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Location;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class LocationController extends Controller
{



    public function campaign_definitions()
    {
        $config  = Config::first() ;
        return view('admin.campaign_definitions.index',compact('config'));
    }


    public function index(Request $request)
    {
        $config  = Config::first() ;
        return view('admin.locations.index',compact('config'));
    }

    public function show(Request $request)
    {
        $location = Location::findOrFail($request->id) ;
        return Response()->json(compact('location'));
    }

    public function get()
    {
        $locations = Location::orderBy('name', 'asc')->get();
        return Response()->json(compact('locations'));
    }

    public function store(Request $request)
    {
        try
        {

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'cpm'  => ['required', 'integer', 'min:0'],
            ]);
            $location = Location::create($validated);

            return response()->json([
                'message' => 'Location created successfully.',
                'data' => $location,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function update(Request $request, Location $location)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'cpm'  => ['required', 'integer', 'min:0'],
            ]);

            $location->update($validated);

            return response()->json([
                'message' => 'Location updated successfully.',
                'data' => $location,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function destroy(Location $location)
    {
        try
        {
            $location->delete();

            return response()->json([
                'message' => 'Location deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function refresh_locations()
    {
        $config = Config::first() ;
        $url = rtrim($config->link, '/') . '/api/adsmart/locations';

        try {
            $client = new Client();
            $response = $client->request('GET', $url,[
                'connect_timeout' => 5,
                'query' => [
                    'username' => $config->user,
                    'password' =>$config->password,
                ],
            ]);

            $contents = json_decode($response->getBody(), true);

            if($contents)
            {
                if( $contents['status'])
                {
                    foreach($contents['data'] as $data)
                    {
                        Location::updateOrCreate([
                            'name' => $data['name']
                         ],
                         [
                            'name' => $data['name'],
                         ]);
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
}
