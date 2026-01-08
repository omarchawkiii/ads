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

    public function show__(Request $request)
    {
        $template = TemplateSlot::with('slots')->findOrFail($request->id);
        return response()->json(compact('template'));


    }

    public function show(Request $request)
    {
        $template = TemplateSlot::with(['slots.positions'])->findOrFail($request->id);

        // Optionnel : transformer les positions en tableau simple de types pour le front
        $template->slots->transform(function ($slot) {
            $slot->ad_slot_types = $slot->positions->pluck('type')->toArray();
            return $slot;
        });

        return response()->json([
            'template' => $template
        ]);
    }

    public function get()
    {

        $templateSlots = TemplateSlot::with('slots')
        ->orderBy('name', 'asc')
        ->get();

        return response()->json(compact('templateSlots'));

    }

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

            foreach ($request->slots as $slotData) {
                $slot = Slot::create([
                    'template_slot_id' => $template->id,
                    'segment_name' => $slotData['segment_name'] ?? null,
                    'name' => $slotData['name'],
                    'max_duration' => $slotData['max_duration'],
                    'cpm' => 0,
                    'max_ad_slot' => $slotData['max_ad_slot'] ?? 1,
                ]);

                foreach ($slotData['ad_slot_types'] as $type) {
                    $slot->positions()->create([
                        'type' => $type
                    ]);
                }
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


    public function update(Request $request, $id)
    {
        $request->validate([
            'template_name' => 'required|string|max:255',
            'slots' => 'required|array|min:1',
            'slots.*.name' => 'required|string|max:255',
            'slots.*.max_duration' => 'required|integer|min:1',
        ]);

        $template = TemplateSlot::findOrFail($id);

        $template->update([
            'name' => $request->template_name,
        ]);

        $sentSlotIds = [];

        foreach ($request->slots as $slotData) {

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

                    $slot->positions()->delete();
                    foreach ($slotData['ad_slot_types'] as $type) {
                        $slot->positions()->create([
                            'type' => $type
                        ]);
                    }

                }

            } else {

                $newSlot = Slot::create([
                    'template_slot_id' => $template->id,
                    'segment_name' => $slotData['segment_name'] ?? null,
                    'name' => $slotData['name'],
                    'max_duration' => $slotData['max_duration'],
                    'cpm' => 0,
                    'max_ad_slot' => $slotData['max_ad_slot'] ?? 1,
                ]);

                $sentSlotIds[] = $newSlot->id;

                foreach ($slotData['ad_slot_types'] as $type) {
                    $newSlot->positions()->create([
                        'type' => $type
                    ]);
                }

            }
        }

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



    public function getAvailableSlots__(Request $request)
    {
        $v = $request->validate([
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'template_slot_id'  => 'required|exists:template_slots,id',

            'cinema_chain_id'   => 'required|array|min:1',
            'cinema_chain_id.*' => 'integer|exists:cinema_chains,id',

            'location_id'       => 'required|array|min:1',
            'location_id.*'     => 'integer|exists:locations,id',

            'movie_id'          => 'nullable|array',
            'movie_id.*'        => 'integer|exists:movies,id',

            'movie_genre_id'    => 'nullable|array',
            'movie_genre_id.*'  => 'integer|exists:movie_genres,id',

            'hall_type_id'      => 'nullable|array',
            'hall_type_id.*'    => 'integer|exists:hall_types,id',

            'compaign_category_id' => 'nullable|integer|exists:compaign_categories,id',
        ]);

        $start = Carbon::parse($v['start_date'])->startOfDay();
        $end   = Carbon::parse($v['end_date'])->endOfDay();

        /* -------------------------------------------------
        | 1️⃣ Slots du template + positions
        -------------------------------------------------*/
        $slots = Slot::with('positions')
            ->where('template_slot_id', $v['template_slot_id'])
            ->select('id', 'name', 'max_duration', 'max_ad_slot')
            ->get();

        if ($slots->isEmpty()) {
            return response()->json(['slots' => []]);
        }

        $slotIds = $slots->pluck('id')->toArray();

        /* -------------------------------------------------
        | 2️⃣ DCPs déjà réservés
        -------------------------------------------------*/
        $assignments = DB::table('compaign_slot_dcp as csd')
            ->join('compaigns as c', 'c.id', '=', 'csd.compaign_id')
            ->join('dcp_creatives as d', 'd.id', '=', 'csd.dcp_creative_id')
            ->join('compaign_location as cl', 'cl.compaign_id', '=', 'c.id')
            ->join('compaign_cinema_chain as ccc', 'ccc.compaign_id', '=', 'c.id')
            ->whereIn('csd.slot_id', $slotIds)
            ->whereIn('cl.location_id', $v['location_id'])
            ->whereIn('ccc.cinema_chain_id', $v['cinema_chain_id'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('c.start_date', [$start, $end])
                  ->orWhereBetween('c.end_date', [$start, $end])
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->where('c.start_date', '<=', $start)
                         ->where('c.end_date', '>=', $end);
                  });
            })
            ->select(
                'csd.slot_id',
                'csd.position',
                'd.id as dcp_id',
                'd.duration'
            )
            ->get()
            ->groupBy('slot_id');

        /* -------------------------------------------------
        | 3️⃣ Construction finale
        -------------------------------------------------*/
        $result = $slots->map(function ($slot) use ($assignments) {

            $slotAssignments = $assignments[$slot->id] ?? collect();
            $byPosition = $slotAssignments->keyBy('position');

            // 🔥 nombre de positions smart
            $smartCount = $slot->positions->where('type', true)->count();

            $positions = [];
            $usedDuration = 0;
            $assignedCount = 0;

            for ($pos = 1; $pos <= $slot->max_ad_slot; $pos++) {

                // 🎯 POSITION SMART (par ordre)
                if ($pos <= $smartCount) {
                    $positions[] = [
                        'position' => $pos,
                        'type'     => 'smart',
                    ];
                    continue;
                }

                // 🔒 Réservée
                if ($byPosition->has($pos)) {
                    $row = $byPosition[$pos];

                    $positions[] = [
                        'position' => $pos,
                        'type'     => 'reserved',
                        'dcp_id'   => $row->dcp_id,
                        'duration' => (int) $row->duration,
                    ];

                    $usedDuration += (int) $row->duration;
                    $assignedCount++;
                }
                // 🆓 Libre
                else {
                    $positions[] = [
                        'position' => $pos,
                        'type'     => 'free',
                    ];
                }
            }

            $remainingDuration = max(0, (int) $slot->max_duration - $usedDuration);

            if ($remainingDuration <= 0 || $assignedCount >= $slot->max_ad_slot) {
                return null;
            }

            return [
                'slot_id'            => $slot->id,
                'name'               => $slot->name,
                'max_duration'       => (int) $slot->max_duration,
                'max_ad_slot'        => (int) $slot->max_ad_slot,
                'assigned_dcp'       => $assignedCount,
                'remaining_duration' => $remainingDuration,
                'positions'          => $positions,
            ];
        })
        ->filter()
        ->values();

        return response()->json([
            'slots' => $result
        ]);
    }


    public function getAvailableSlots(Request $request)
    {
        $v = $request->validate([
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'template_slot_id'  => 'required|exists:template_slots,id',
            'cinema_chain_id'   => 'required|array|min:1',
            'cinema_chain_id.*' => 'integer|exists:cinema_chains,id',
            'location_id'       => 'required|array|min:1',
            'location_id.*'     => 'integer|exists:locations,id',
            'movie_id'          => 'nullable|array',
            'movie_id.*'        => 'integer|exists:movies,id',
            'movie_genre_id'    => 'nullable|array',
            'movie_genre_id.*'  => 'integer|exists:movie_genres,id',
            'hall_type_id'      => 'nullable|array',
            'hall_type_id.*'    => 'integer|exists:hall_types,id',
            'compaign_category_id' => 'nullable|integer|exists:compaign_categories,id',
        ]);

        $start = Carbon::parse($v['start_date'])->startOfDay();
        $end   = Carbon::parse($v['end_date'])->endOfDay();

        // 1️⃣ Slots avec positions
        $slots = Slot::with('positions')
            ->where('template_slot_id', $v['template_slot_id'])
            ->select('id', 'name', 'max_duration', 'max_ad_slot')
            ->get();

        if ($slots->isEmpty()) {
            return response()->json(['slots' => []]);
        }

        $slotIds = $slots->pluck('id')->toArray();

        // 2️⃣ DCPs déjà réservés
        $assignments = DB::table('compaign_slot_dcp as csd')
            ->join('compaigns as c', 'c.id', '=', 'csd.compaign_id')
            ->join('dcp_creatives as d', 'd.id', '=', 'csd.dcp_creative_id')
            ->join('compaign_location as cl', 'cl.compaign_id', '=', 'c.id')
            ->join('compaign_cinema_chain as ccc', 'ccc.compaign_id', '=', 'c.id')
            ->whereIn('csd.slot_id', $slotIds)
            ->whereIn('cl.location_id', $v['location_id'])
            ->whereIn('ccc.cinema_chain_id', $v['cinema_chain_id'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('c.start_date', [$start, $end])
                ->orWhereBetween('c.end_date', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('c.start_date', '<=', $start)
                        ->where('c.end_date', '>=', $end);
                });
            })
            ->select(
                'csd.slot_id',
                'csd.position',
                'd.id as dcp_id',
                'd.duration'
            )
            ->get()
            ->groupBy('slot_id');

        // 3️⃣ Construction finale avec ordre réel des positions
        $result = $slots->map(function ($slot) use ($assignments) {

            $slotAssignments = $assignments[$slot->id] ?? collect();
            $byPosition = $slotAssignments->keyBy('position');

            $positions = [];
            $usedDuration = 0;
            $assignedCount = 0;

            // Parcourir les positions existantes dans l'ordre
            foreach ($slot->positions as $posObj) {
                $pos = $posObj->id; // on peut utiliser id ou créer un index si besoin
                $isSmart = $posObj->type; // true ou false

                if ($byPosition->has($pos)) {
                    $row = $byPosition[$pos];
                    $positions[] = [
                        'position' => $pos,
                        'type'     => 'reserved',
                        'dcp_id'   => $row->dcp_id,
                        'duration' => (int) $row->duration,
                    ];
                    $usedDuration += (int) $row->duration;
                    $assignedCount++;
                } else {
                    $positions[] = [
                        'position' => $pos,
                        'type'     => $isSmart ? 'smart' : 'free',
                    ];
                }
            }

            // 🔹 Si moins de positions que max_ad_slot, compléter à la fin
            for ($pos = count($slot->positions)+1; $pos <= $slot->max_ad_slot; $pos++) {
                if ($byPosition->has($pos)) {
                    $row = $byPosition[$pos];
                    $positions[] = [
                        'position' => $pos,
                        'type'     => 'reserved',
                        'dcp_id'   => $row->dcp_id,
                        'duration' => (int) $row->duration,
                    ];
                    $usedDuration += (int) $row->duration;
                    $assignedCount++;
                } else {
                    $positions[] = [
                        'position' => $pos,
                        'type'     => 'free',
                    ];
                }
            }

            $remainingDuration = max(0, (int) $slot->max_duration - $usedDuration);

            if ($remainingDuration <= 0 || $assignedCount >= $slot->max_ad_slot) {
                return null;
            }

            return [
                'slot_id'            => $slot->id,
                'name'               => $slot->name,
                'max_duration'       => (int) $slot->max_duration,
                'max_ad_slot'        => (int) $slot->max_ad_slot,
                'assigned_dcp'       => $assignedCount,
                'remaining_duration' => $remainingDuration,
                'positions'          => $positions,
            ];
        })
        ->filter()
        ->values();

        return response()->json([
            'slots' => $result
        ]);
    }


    public function getAvailableSlotsEdit__(Request $request)
    {
        $v = $request->validate([
            'start_date'           => 'required|date',
            'end_date'             => 'required|date|after_or_equal:start_date',
            'template_slot_id'     => 'required|exists:template_slots,id',
            'compaign_id'          => 'required|integer|exists:compaigns,id',
            'cinema_chain_id'      => 'required|array|min:1',
            'cinema_chain_id.*'    => 'integer|exists:cinema_chains,id',
            'location_id'          => 'required|array|min:1',
            'location_id.*'        => 'integer|exists:locations,id',
            'movie_id'             => 'nullable|array',
            'movie_id.*'           => 'integer|exists:movies,id',
            'movie_genre_id'       => 'nullable|array',
            'movie_genre_id.*'     => 'integer|exists:movie_genres,id',
            'hall_type_id'         => 'nullable|array',
            'hall_type_id.*'       => 'integer|exists:hall_types,id',
            'compaign_category_id' => 'nullable|integer|exists:compaign_categories,id',
        ]);

        $start = Carbon::parse($v['start_date'])->startOfDay();
        $end   = Carbon::parse($v['end_date'])->endOfDay();

        // 1️⃣ Slots du template
        $slots = Slot::where('template_slot_id', $v['template_slot_id'])
            ->select('id', 'name', 'max_duration', 'max_ad_slot')
            ->get();

        if ($slots->isEmpty()) {
            return response()->json(['slots' => []]);
        }

        $slotIds = $slots->pluck('id')->toArray();

        // 2️⃣ DCP de la campagne courante (toujours inclus)
        $currentCampaignAssignments = DB::table('compaign_slot_dcp as csd')
            ->join('dcp_creatives as d', 'd.id', '=', 'csd.dcp_creative_id')
            ->whereIn('csd.slot_id', $slotIds)
            ->where('csd.compaign_id', $v['compaign_id'])
            ->select(
                'csd.slot_id',
                'csd.position',
                'csd.compaign_id',
                'd.id as dcp_id',
                'd.name as dcp_name',
                'd.duration'
            )
            ->get();

        // 3️⃣ DCP des autres campagnes avec filtres
        $otherAssignmentsQuery = DB::table('compaign_slot_dcp as csd')
            ->join('compaigns as c', 'c.id', '=', 'csd.compaign_id')
            ->join('dcp_creatives as d', 'd.id', '=', 'csd.dcp_creative_id')
            ->join('compaign_location as cl', 'cl.compaign_id', '=', 'c.id')
            ->join('compaign_cinema_chain as ccc', 'ccc.compaign_id', '=', 'c.id')
            ->whereIn('csd.slot_id', $slotIds)
            ->whereIn('cl.location_id', $v['location_id'])
            ->whereIn('ccc.cinema_chain_id', $v['cinema_chain_id'])
            ->where('csd.compaign_id', '<>', $v['compaign_id']) // 🔥 exclut la campagne courante
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('c.start_date', [$start, $end])
                ->orWhereBetween('c.end_date', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('c.start_date', '<=', $start)
                        ->where('c.end_date', '>=', $end);
                });
            });

        if (!empty($v['compaign_category_id'])) {
            $otherAssignmentsQuery->where('c.compaign_category_id', $v['compaign_category_id']);
        }

        if (!empty($v['movie_id'])) {
            $otherAssignmentsQuery
                ->join('compaign_movie as cm', 'cm.compaign_id', '=', 'c.id')
                ->whereIn('cm.movie_id', $v['movie_id']);
        } elseif (!empty($v['movie_genre_id'])) {
            $otherAssignmentsQuery
                ->join('compaign_movie_genre as cmg', 'cmg.compaign_id', '=', 'c.id')
                ->whereIn('cmg.movie_genre_id', $v['movie_genre_id']);
        }

        if (!empty($v['hall_type_id'])) {
            $otherAssignmentsQuery
                ->join('compaign_hall_type as cht', 'cht.compaign_id', '=', 'c.id')
                ->whereIn('cht.hall_type_id', $v['hall_type_id']);
        }

        $otherAssignments = $otherAssignmentsQuery
            ->select(
                'csd.slot_id',
                'csd.position',
                'csd.compaign_id',
                'd.id as dcp_id',
                'd.name as dcp_name',
                'd.duration'
            )
            ->orderBy('csd.position')
            ->get();

        // 4️⃣ Merge des deux collections
        $assignments = $currentCampaignAssignments->merge($otherAssignments)->groupBy('slot_id');

        // 5️⃣ Construction finale des slots
        $result = $slots->map(function ($slot) use ($assignments, $v) {

            $slotAssignments = $assignments[$slot->id] ?? collect();
            $byPosition = $slotAssignments->keyBy('position');

            $positions = [];
            $usedDuration = 0;
            $assignedCount = 0;

            for ($pos = 1; $pos <= $slot->max_ad_slot; $pos++) {

                if ($byPosition->has($pos)) {
                    $row = $byPosition[$pos];

                    $isCurrent = $row->compaign_id == $v['compaign_id'];

                    $positions[] = [
                        'position' => $pos,
                        'type'     => $isCurrent ? 'current' : 'reserved',
                        'dcp_id'   => $row->dcp_id,
                        'dcp_name' => $isCurrent ? $row->dcp_name : null,
                        'duration' => (int) $row->duration,
                    ];

                    $usedDuration += (int) $row->duration;
                    $assignedCount++;
                } else {
                    $positions[] = [
                        'position' => $pos,
                        'type'     => 'free',
                    ];
                }
            }

            $remainingDuration = max(0, (int) $slot->max_duration - $usedDuration);

            // 🔥 Toujours afficher le slot si la campagne courante y a un DCP
            $hasCurrentCampaign = $slotAssignments
                ->contains(fn ($row) => $row->compaign_id == $v['compaign_id']);

            if (
                ($remainingDuration <= 0 || $assignedCount >= $slot->max_ad_slot)
                && !$hasCurrentCampaign
            ) {
                return null;
            }

            return [
                'slot_id'            => $slot->id,
                'name'               => $slot->name,
                'max_duration'       => (int) $slot->max_duration,
                'max_ad_slot'        => (int) $slot->max_ad_slot,
                'assigned_dcp'       => $assignedCount,
                'remaining_duration' => $remainingDuration,
                'positions'          => $positions,
            ];
        })
        ->filter()
        ->values();

        return response()->json([
            'slots' => $result
        ]);
    }


    public function getAvailableSlotsEdit(Request $request)
    {
        $v = $request->validate([
            'start_date'           => 'required|date',
            'end_date'             => 'required|date|after_or_equal:start_date',
            'template_slot_id'     => 'required|exists:template_slots,id',
            'compaign_id'          => 'required|exists:compaigns,id',

            'cinema_chain_id'      => 'required|array|min:1',
            'cinema_chain_id.*'    => 'integer|exists:cinema_chains,id',

            'location_id'          => 'required|array|min:1',
            'location_id.*'        => 'integer|exists:locations,id',

            'movie_id'             => 'nullable|array',
            'movie_id.*'           => 'integer|exists:movies,id',

            'movie_genre_id'       => 'nullable|array',
            'movie_genre_id.*'     => 'integer|exists:movie_genres,id',

            'hall_type_id'         => 'nullable|array',
            'hall_type_id.*'       => 'integer|exists:hall_types,id',

            'compaign_category_id' => 'nullable|integer|exists:compaign_categories,id',
        ]);

        $start = Carbon::parse($v['start_date'])->startOfDay();
        $end   = Carbon::parse($v['end_date'])->endOfDay();

        // 1️⃣ Slots du template
        $slots = Slot::with('positions')
            ->where('template_slot_id', $v['template_slot_id'])
            ->select('id', 'name', 'max_duration', 'max_ad_slot')
            ->get();

        if ($slots->isEmpty()) {
            return response()->json(['slots' => []]);
        }

        $slotIds = $slots->pluck('id');

        // 2️⃣ DCP de la campagne courante
        $currentAssignments = DB::table('compaign_slot_dcp as csd')
            ->join('dcp_creatives as d', 'd.id', '=', 'csd.dcp_creative_id')
            ->where('csd.compaign_id', $v['compaign_id'])
            ->whereIn('csd.slot_id', $slotIds)
            ->select(
                'csd.slot_id',
                'csd.position',
                'csd.compaign_id',
                'd.id as dcp_id',
                'd.name as dcp_name',
                'd.duration'
            )
            ->get();

        // 3️⃣ DCP des autres campagnes avec filtres
        $otherAssignmentsQuery = DB::table('compaign_slot_dcp as csd')
            ->join('compaigns as c', 'c.id', '=', 'csd.compaign_id')
            ->join('dcp_creatives as d', 'd.id', '=', 'csd.dcp_creative_id')
            ->join('compaign_location as cl', 'cl.compaign_id', '=', 'c.id')
            ->join('compaign_cinema_chain as ccc', 'ccc.compaign_id', '=', 'c.id')
            ->whereIn('csd.slot_id', $slotIds)
            ->whereIn('cl.location_id', $v['location_id'])
            ->whereIn('ccc.cinema_chain_id', $v['cinema_chain_id'])
            ->where('csd.compaign_id', '<>', $v['compaign_id'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('c.start_date', [$start, $end])
                ->orWhereBetween('c.end_date', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('c.start_date', '<=', $start)
                        ->where('c.end_date', '>=', $end);
                });
            });

        if (!empty($v['compaign_category_id'])) {
            $otherAssignmentsQuery->where('c.compaign_category_id', $v['compaign_category_id']);
        }

        if (!empty($v['movie_id'])) {
            $otherAssignmentsQuery
                ->join('compaign_movie as cm', 'cm.compaign_id', '=', 'c.id')
                ->whereIn('cm.movie_id', $v['movie_id']);
        } elseif (!empty($v['movie_genre_id'])) {
            $otherAssignmentsQuery
                ->join('compaign_movie_genre as cmg', 'cmg.compaign_id', '=', 'c.id')
                ->whereIn('cmg.movie_genre_id', $v['movie_genre_id']);
        }

        if (!empty($v['hall_type_id'])) {
            $otherAssignmentsQuery
                ->join('compaign_hall_type as cht', 'cht.compaign_id', '=', 'c.id')
                ->whereIn('cht.hall_type_id', $v['hall_type_id']);
        }

        $otherAssignments = $otherAssignmentsQuery
            ->select(
                'csd.slot_id',
                'csd.position',
                'csd.compaign_id',
                'd.id as dcp_id',
                'd.name as dcp_name',
                'd.duration'
            )
            ->orderBy('csd.position')
            ->get();

        // 4️⃣ Merge & group by slot
        $assignments = $currentAssignments
            ->merge($otherAssignments)
            ->groupBy('slot_id');

        // 5️⃣ Construction finale avec ordre exact et smart positions
        $result = $slots->map(function ($slot) use ($assignments, $v) {

            $slotAssignments = $assignments[$slot->id] ?? collect();
            $byPosition = $slotAssignments->keyBy('position');

            $positions = [];
            $usedDuration = 0;
            $assignedCount = 0;

            foreach ($slot->positions as $posObj) {
                $pos = $posObj->id;
                $isSmart = $posObj->type; // true/false

                if ($byPosition->has($pos)) {
                    $row = $byPosition[$pos];
                    $isCurrent = $row->compaign_id == $v['compaign_id'];

                    $positions[] = [
                        'position' => $pos,
                        'type'     => $isCurrent ? 'current' : 'reserved',
                        'dcp_id'   => $row->dcp_id,
                        'dcp_name' => $isCurrent ? $row->dcp_name : null,
                        'duration' => (int) $row->duration,
                    ];

                    $usedDuration += (int) $row->duration;
                    $assignedCount++;
                } else {
                    $positions[] = [
                        'position' => $pos,
                        'type'     => $isSmart ? 'smart' : 'free',
                    ];
                }
            }

            // compléter les positions manquantes jusqu'à max_ad_slot
            for ($pos = count($slot->positions)+1; $pos <= $slot->max_ad_slot; $pos++) {
                if ($byPosition->has($pos)) {
                    $row = $byPosition[$pos];
                    $positions[] = [
                        'position' => $pos,
                        'type'     => 'reserved',
                        'dcp_id'   => $row->dcp_id,
                        'duration' => (int) $row->duration,
                    ];
                    $usedDuration += (int) $row->duration;
                    $assignedCount++;
                } else {
                    $positions[] = [
                        'position' => $pos,
                        'type'     => 'free',
                    ];
                }
            }

            $remainingDuration = max(0, (int) $slot->max_duration - $usedDuration);

            $hasCurrentCampaign = $slotAssignments
                ->contains(fn ($row) => $row->compaign_id == $v['compaign_id']);

            if (
                ($remainingDuration <= 0 || $assignedCount >= $slot->max_ad_slot)
                && !$hasCurrentCampaign
            ) {
                return null;
            }

            return [
                'slot_id'            => $slot->id,
                'name'               => $slot->name,
                'max_duration'       => (int) $slot->max_duration,
                'max_ad_slot'        => (int) $slot->max_ad_slot,
                'assigned_dcp'       => $assignedCount,
                'remaining_duration' => $remainingDuration,
                'positions'          => $positions,
            ];
        })
        ->filter()
        ->values();

        return response()->json([
            'slots' => $result
        ]);
    }






}
