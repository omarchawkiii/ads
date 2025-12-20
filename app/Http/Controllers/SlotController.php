<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Compaign;
use App\Models\DcpCreative;
use App\Models\Movie;
use App\Models\TemplateSlot;

class SlotController extends Controller
{
    public function index()
    {
        return view('admin.slots.index');
    }

    public function show(Request $request)
    {
        $template = TemplateSlot::with('slots')->findOrFail($request->id);
        return response()->json(compact('template'));

        /*$slot = Slot::findOrFail($request->id) ;
        return Response()->json(compact('slot'));*/
    }

    public function get()
    {

        $templateSlots = TemplateSlot::with('slots')
        ->orderBy('name', 'asc')
        ->get();

        return response()->json(compact('templateSlots'));
        /*$slots = Slot::orderBy('name', 'asc')->get();
        return Response()->json(compact('slots'));*/
    }

    /*public function store(Request $request)
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
    }*/

    public function store(Request $request)
    {
        try
        {
            $request->validate([
                'template_name' => 'required|string|max:255',
                'slots' => 'required|array|min:1',
                'slots.*.name' => 'required|string|max:255',
                'slots.*.max_duration' => 'required|integer|min:1',
            ]);

            $template = TemplateSlot::create([
                'name' => $request->template_name,
            ]);

            foreach ($request->slots as $slot) {
                Slot::create([
                    'template_slot_id' => $template->id,
                    'segment_name' => $slot['segment_name'] ?? null,
                    'name' => $slot['name'],
                    'max_duration' => $slot['max_duration'],
                    'cpm' => 0,
                    'max_ad_slot' => $slot['max_ad_slot'] ?? 1,
                ]);
            }
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

    /*public function update(Request $request, Slot $slot)
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
    }*/

    public function update(Request $request, $id)
    {
        $request->validate([
            'template_name' => 'required|string|max:255',
            'slots' => 'required|array|min:1',
            'slots.*.name' => 'required|string|max:255',
            'slots.*.max_duration' => 'required|integer|min:1',
        ]);

        $template = TemplateSlot::findOrFail($id);

        // Update template name
        $template->update([
            'name' => $request->template_name,
        ]);

        $sentSlotIds = [];

        foreach ($request->slots as $slotData) {

            // ✅ Cas 1 : slot existant → update
            if (!empty($slotData['id'])) {
                $slot = Slot::where('id', $slotData['id'])
                            ->where('template_slot_id', $template->id)
                            ->first();

                if ($slot) {
                    $slot->update([
                        'segment_name' => $slotData['segment_name'] ?? null,
                        'name' => $slotData['name'],
                        'max_duration' => $slotData['max_duration'],
                        'max_ad_slot' => $slotData['max_ad_slot'] ?? 1,
                    ]);

                    $sentSlotIds[] = $slot->id;
                }

            } else {
                // ✅ Cas 2 : nouveau slot → create
                $newSlot = Slot::create([
                    'template_slot_id' => $template->id,
                    'segment_name' => $slotData['segment_name'] ?? null,
                    'name' => $slotData['name'],
                    'max_duration' => $slotData['max_duration'],
                    'cpm' => 0,
                    'max_ad_slot' => $slotData['max_ad_slot'] ?? 1,
                ]);

                $sentSlotIds[] = $newSlot->id;
            }
        }

        // ✅ Cas 3 : slots supprimés côté front → delete en base
        Slot::where('template_slot_id', $template->id)
            ->whereNotIn('id', $sentSlotIds)
            ->delete();

        return response()->json(['success' => true]);
    }


    public function destroy($id)
    {
        try {
            $template = TemplateSlot::with('slots')->findOrFail($id);

            // Supprimer les slots liés
            foreach ($template->slots as $slot) {
                $slot->delete();
            }

            // Supprimer le template
            $template->delete();

            return response()->json([
                'message' => 'Template and Slots deleted successfully.',
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


         // ✅ Check if movies exist for selected genres
        if (!empty($movieGenreIds)) {

            $movieExists = Movie::whereIn('movie_genre_id', $movieGenreIds)->exists();

            if (!$movieExists) {
                return response()->json([
                    'slots' => [],
                    'message' => 'No movies found for the selected genre(s).'
                ]);
            }
        }

        foreach ($slots as $slot) {
            $totalDcpDuration = $dcpCreatives->sum('duration');
            //$totalDcpDuration = intval( $totalDcpDuration);


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
                ->whereHas('locations', function ($q) use ($locationIds) {
                    $q->whereIn('locations.id', $locationIds);
                })
                ->with('dcpCreatives') // on charge le dcp_creative lié
                ->get()
                ->sum(function($compaign) {
                    return $compaign->dcpCreatives->sum('duration');
                });


            if ($slot->max_duration >= $usedDuration && ($slot->max_duration - $usedDuration ) >=  $totalDcpDuration ) {
                $availableSlots[] = $slot;
            }

            /*echo $slot->max_duration . " | ";
            echo $usedDuration . " | ";
            echo $usedDuration . " | ";
            echo $totalDcpDuration . " | ";

            echo "________";*/
        }

        return response()->json([
            'slots' => $availableSlots
        ]);
    }


}
