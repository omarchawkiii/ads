<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Compaign;
use App\Models\DcpCreative;

class SlotController extends Controller
{
    public function index()
    {
        return view('admin.slots.index');
    }

    public function show(Request $request)
    {
        $slot = Slot::findOrFail($request->id) ;
        return Response()->json(compact('slot'));
    }

    public function get()
    {
        $slots = Slot::orderBy('name', 'asc')->get();
        return Response()->json(compact('slots'));
    }

    public function store(Request $request)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'cpm'  => ['required', 'integer', 'min:0'],
                'max_duration' => ['required', 'integer', 'min:1'],
            ]);
            $slot = Slot::create($validated);
            return response()->json([
                'message' => 'Slot created successfully.',
                'data' => $slot,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function update(Request $request, Slot $slot)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'cpm'  => ['required', 'integer', 'min:0'],
                'max_duration' => ['required', 'integer', 'min:1'],
            ]);

            $slot->update($validated);
            return response()->json([
                'message' => 'Slot updated successfully.',
                'data' => $slot,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function destroy(Slot $slot)
    {
        try
        {
            $slot->delete();
            return response()->json([
                'message' => 'Slot deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'location_id' => 'required|array',
            'location_id.*' => 'integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'movie_id' => 'required|integer',
            'movie_genre_id' => 'nullable|array',
            'movie_genre_id.*' => 'integer',
            'dcp_creative' => 'required|array',
        ]);

        $locationIds = $request->location_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $movieId = $request->movie_id;
        $movieGenreIds = $request->movie_genre_id ?? [];

        $slots = Slot::all();
        $availableSlots = [];

        $dcpCreatives = DcpCreative::whereIn('id', $request->dcp_creative)->get();

        foreach ($slots as $slot) {
            $totalDcpDuration = $dcpCreatives->sum('duration');
            if ($slot->max_duration < $totalDcpDuration) {
                continue; // skip ce slot
            }
            // Calculer la durée déjà utilisée sur ce slot
            $usedDuration = Compaign::where('slot_id', $slot->id)
                ->when(count($movieGenreIds), function($q) use ($movieGenreIds) {
                    $q->whereHas('movie', function($mq) use ($movieGenreIds) {
                        $mq->whereIn('movie_genre_id', $movieGenreIds);
                    });
                })
                ->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate]);
                })
                ->with('dcpCreatives') // on charge le dcp_creative lié
                ->get()
                ->sum(function($compaign) {
                    return $compaign->dcpCreatives->sum('duration');
                });


            if ($slot->max_duration > $usedDuration && ($slot->max_duration - $usedDuration ) >  $totalDcpDuration ) {
                $availableSlots[] = $slot;
            }
        }

        return response()->json([
            'slots' => $availableSlots
        ]);
    }


}
