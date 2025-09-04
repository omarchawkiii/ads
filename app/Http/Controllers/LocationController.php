<?php

namespace App\Http\Controllers;

use App\Models\Location;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.locations.index');
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
}
