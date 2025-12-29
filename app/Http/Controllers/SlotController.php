<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Compaign;
use App\Models\DcpCreative;
use App\Models\Movie;
use App\Models\TemplateSlot;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

      /*public function getAvailableSlots(Request $request)
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


        }

        $availableSlots = Slot::where('template_slot_id',1)->get();
        return response()->json([
            'slots' => $availableSlots
        ]);
    }*/


    public function getAvailableSlots(Request $request)
    {
        $v = $request->validate([
            'start_date'            => 'required|date',
            'end_date'              => 'required|date|after_or_equal:start_date',
            'template_slot_id'     => 'required|exists:template_slots,id',
            'cinema_chain_id'      => 'nullable|integer|exists:cinema_chains,id',
            'location_id'          => 'required|array',
            'location_id.*'        => 'integer|exists:locations,id',
            'movie_genre_id'       => 'nullable|array',
            'movie_genre_id.*'     => 'integer|exists:movie_genres,id',
            'compaign_category_id' => 'nullable|integer|exists:compaign_categories,id',
        ]);

        $start = Carbon::parse($v['start_date'])->startOfDay();
        $end   = Carbon::parse($v['end_date'])->endOfDay();

        if (!empty($v['movie_genre_id'])) {
            $hasMovies = Movie::whereIn('movie_genre_id', $v['movie_genre_id'])
              //  ->whereNotNull('play_at')
               // ->whereBetween('play_at', [$start, $end])
                ->exists();

            if (!$hasMovies) {
                return response()->json([
                    'slots' => []
                ]);
            }

        }

        // 1️⃣ Tous les slots du template
        $slots = Slot::where('template_slot_id', $v['template_slot_id'])
            ->select('id', 'name', 'max_duration')
            ->get();

        if ($slots->isEmpty()) {
            return response()->json(['slots' => []]);
        }

        $slotIds = $slots->pluck('id');

        // 2️⃣ Calcul des durées déjà utilisées par slot
        $usedQuery = DB::table('compaign_slot_dcp as csd')
            ->join('compaigns as c', 'c.id', '=', 'csd.compaign_id')
            ->join('dcp_creatives as d', 'd.id', '=', 'csd.dcp_creative_id')
            ->join('compaign_location as cl', 'cl.compaign_id', '=', 'c.id')
            ->whereIn('csd.slot_id', $slotIds)
            ->whereIn('cl.location_id', $v['location_id'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('c.start_date', [$start, $end])
                ->orWhereBetween('c.end_date', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('c.start_date', '<=', $start)
                        ->where('c.end_date', '>=', $end);
                });
            });

        // ➕ filtres optionnels
        if (!empty($v['cinema_chain_id'])) {
            $usedQuery->join('locations as l', 'l.id', '=', 'cl.location_id')
                    ->where('l.cinema_chain_id', $v['cinema_chain_id']);
        }

        if (!empty($v['compaign_category_id'])) {
            $usedQuery->where('c.compaign_category_id', $v['compaign_category_id']);
        }

        if (!empty($v['movie_genre_id'])) {
            $usedQuery->join('compaign_movie_genre as cmg', 'cmg.compaign_id', '=', 'c.id')
                    ->whereIn('cmg.movie_genre_id', $v['movie_genre_id']);
        }

        // group by slot et somme des durées
        $usedBySlot = $usedQuery
            ->select('csd.slot_id', DB::raw('SUM(d.duration) as used'))
            ->groupBy('csd.slot_id')
            ->pluck('used', 'csd.slot_id'); // [slot_id => used]

        // 3️⃣ Construire la réponse avec remaining
        $result = $slots->map(function ($slot) use ($usedBySlot) {
            $used = (int) ($usedBySlot[$slot->id] ?? 0);
            $remaining = max(0, $slot->max_duration - $used);

            return [
                'id'            => $slot->id,
                'name'          => $slot->name,
                'max_duration' => (int) $slot->max_duration,
                'used'          => $used,
                'remaining'     => $remaining,
            ];
        })
        // 👉 si tu veux cacher ceux sans temps restant :
        ->filter(fn ($s) => $s['remaining'] > 0)
        ->values();

        return response()->json([
            'slots' => $result
        ]);
    }





}
