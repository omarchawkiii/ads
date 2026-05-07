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
                'template_name'          => 'required|string|max:255',
                'slots'                  => 'required|array|min:1',
                'slots.*.type'           => 'nullable|in:ads,segment',
                'slots.*.name'           => 'required_if:slots.*.type,ads|nullable|string|max:255',
                'slots.*.max_duration'   => 'required_if:slots.*.type,ads|nullable|integer|min:1',
                'slots.*.segment_select' => 'required_if:slots.*.type,segment|nullable|string',
            ]);

            $template = TemplateSlot::create([
                'name' => $request->template_name,
            ]);

            foreach ($request->slots as $slotData) {
                $slotType  = $slotData['type'] ?? 'ads';
                $isSegment = $slotType === 'segment';

                $slot = Slot::create([
                    'template_slot_id' => $template->id,
                    'type'             => $slotType,
                    'segment_name'     => !$isSegment ? ($slotData['segment_name'] ?? null) : null,
                    'name'             => $isSegment ? ($slotData['segment_select'] ?? 'Segment 1') : $slotData['name'],
                    'max_duration'     => !$isSegment ? ($slotData['max_duration'] ?? 0) : 0,
                    'cpm'              => 0,
                    'max_ad_slot'      => !$isSegment ? ($slotData['max_ad_slot'] ?? 1) : 0,
                ]);

                if (!$isSegment) {
                    foreach ($slotData['ad_slot_types'] ?? [] as $posType) {
                        $slot->positions()->create(['type' => $posType]);
                    }
                }
            }
            return response()->json([
                'message' => 'Slot created successfully.',
                'data' => $slot,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed.', 'errors' => $e->errors()], 422);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'template_name'          => 'required|string|max:255',
            'slots'                  => 'required|array|min:1',
            'slots.*.type'           => 'nullable|in:ads,segment',
            'slots.*.name'           => 'required_if:slots.*.type,ads|nullable|string|max:255',
            'slots.*.max_duration'   => 'required_if:slots.*.type,ads|nullable|integer|min:1',
            'slots.*.segment_select' => 'required_if:slots.*.type,segment|nullable|string',
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
                    $slotType  = $slotData['type'] ?? 'ads';
                    $isSegment = $slotType === 'segment';

                    $slot->update([
                        'type'         => $slotType,
                        'segment_name' => !$isSegment ? ($slotData['segment_name'] ?? null) : null,
                        'name'         => $isSegment ? ($slotData['segment_select'] ?? 'Segment 1') : $slotData['name'],
                        'max_duration' => !$isSegment ? ($slotData['max_duration'] ?? 0) : 0,
                        'max_ad_slot'  => !$isSegment ? ($slotData['max_ad_slot'] ?? 1) : 0,
                    ]);

                    $sentSlotIds[] = $slot->id;

                    $slot->positions()->delete();
                    if (!$isSegment) {
                        foreach ($slotData['ad_slot_types'] ?? [] as $posType) {
                            $slot->positions()->create(['type' => $posType]);
                        }
                    }
                }

            } else {
                $slotType  = $slotData['type'] ?? 'ads';
                $isSegment = $slotType === 'segment';

                $newSlot = Slot::create([
                    'template_slot_id' => $template->id,
                    'type'             => $slotType,
                    'segment_name'     => !$isSegment ? ($slotData['segment_name'] ?? null) : null,
                    'name'             => $isSegment ? ($slotData['segment_select'] ?? 'Segment 1') : $slotData['name'],
                    'max_duration'     => !$isSegment ? ($slotData['max_duration'] ?? 0) : 0,
                    'cpm'              => 0,
                    'max_ad_slot'      => !$isSegment ? ($slotData['max_ad_slot'] ?? 1) : 0,
                ]);

                $sentSlotIds[] = $newSlot->id;

                if (!$isSegment) {
                    foreach ($slotData['ad_slot_types'] ?? [] as $posType) {
                        $newSlot->positions()->create(['type' => $posType]);
                    }
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
            ->where('type', 'ads')
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
            'master_movie_id'    => 'nullable|array',
            'master_movie_id.*'  => 'integer|exists:master_movies,id',
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
            ->where('type', 'ads')
            ->select('id', 'name', 'max_duration', 'max_ad_slot')
            ->get();

        if ($slots->isEmpty()) {
            return response()->json(['slots' => []]);
        }

        $slotIds = $slots->pluck('id')->toArray();

        // 2️⃣ DCPs déjà réservés — on sélectionne TOUTES les campagnes par position
        $assignmentsQuery = DB::table('compaign_slot_dcp as csd')
            ->join('compaigns as c', 'c.id', '=', 'csd.compaign_id')
            ->join('dcp_creatives as d', 'd.id', '=', 'csd.dcp_creative_id')
            ->whereIn('csd.slot_id', $slotIds)
            ->whereExists(function ($sub) use ($v) {
                $sub->select(DB::raw(1))
                    ->from('compaign_location as cl')
                    ->whereColumn('cl.compaign_id', 'c.id')
                    ->whereIn('cl.location_id', $v['location_id']);
            })
            ->whereExists(function ($sub) use ($v) {
                $sub->select(DB::raw(1))
                    ->from('compaign_cinema_chain as ccc')
                    ->whereColumn('ccc.compaign_id', 'c.id')
                    ->whereIn('ccc.cinema_chain_id', $v['cinema_chain_id']);
            })
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('c.start_date', [$start, $end])
                ->orWhereBetween('c.end_date', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('c.start_date', '<=', $start)
                        ->where('c.end_date', '>=', $end);
                });
            });

        if (!empty($v['hall_type_id'])) {
            $assignmentsQuery->whereExists(function ($sub) use ($v) {
                $sub->select(DB::raw(1))
                    ->from('compaign_hall_type as cht')
                    ->whereColumn('cht.compaign_id', 'c.id')
                    ->whereIn('cht.hall_type_id', $v['hall_type_id']);
            });
        }

        $allAssignments = $assignmentsQuery
            ->select(
                'csd.slot_id',
                'csd.position',
                'c.id as campaign_id',
                'c.start_date as campaign_start',
                'c.end_date as campaign_end',
                'd.id as dcp_id',
                'd.duration'
            )
            ->get();

        // Group by slot_id, then by position (keep ALL campaigns per position)
        $assignmentsBySlot = $allAssignments->groupBy('slot_id');

        // 3️⃣ Batch queries for conflict detail names
        $campaignIds = $allAssignments->pluck('campaign_id')->unique()->values()->toArray();

        $conflictLocations    = collect();
        $conflictCinemaChains = collect();
        $conflictHallTypes    = collect();

        if (!empty($campaignIds)) {
            $conflictLocations = DB::table('compaign_location as cl')
                ->join('locations as l', 'l.id', '=', 'cl.location_id')
                ->whereIn('cl.compaign_id', $campaignIds)
                ->whereIn('cl.location_id', $v['location_id'])
                ->select('cl.compaign_id', 'l.name')
                ->get()
                ->groupBy('compaign_id')
                ->map(fn($g) => $g->pluck('name')->toArray());

            $conflictCinemaChains = DB::table('compaign_cinema_chain as ccc')
                ->join('cinema_chains as cc', 'cc.id', '=', 'ccc.cinema_chain_id')
                ->whereIn('ccc.compaign_id', $campaignIds)
                ->whereIn('ccc.cinema_chain_id', $v['cinema_chain_id'])
                ->select('ccc.compaign_id', 'cc.name')
                ->get()
                ->groupBy('compaign_id')
                ->map(fn($g) => $g->pluck('name')->toArray());

            if (!empty($v['hall_type_id'])) {
                $conflictHallTypes = DB::table('compaign_hall_type as cht')
                    ->join('hall_types as ht', 'ht.id', '=', 'cht.hall_type_id')
                    ->whereIn('cht.compaign_id', $campaignIds)
                    ->whereIn('cht.hall_type_id', $v['hall_type_id'])
                    ->select('cht.compaign_id', 'ht.name')
                    ->get()
                    ->groupBy('compaign_id')
                    ->map(fn($g) => $g->pluck('name')->toArray());
            }
        }

        // Helper: build reserved_info for a group of rows sharing the same position
        $buildReservedInfo = function ($rows) use ($start, $end, $conflictLocations, $conflictCinemaChains, $conflictHallTypes) {

            $campIds      = $rows->pluck('campaign_id')->toArray();
            $locations    = collect($campIds)->flatMap(fn($cid) => $conflictLocations[$cid] ?? [])->unique()->values()->toArray();
            $cinemaChains = collect($campIds)->flatMap(fn($cid) => $conflictCinemaChains[$cid] ?? [])->unique()->values()->toArray();
            $hallTypes    = collect($campIds)->flatMap(fn($cid) => $conflictHallTypes[$cid] ?? [])->unique()->values()->toArray();

            // Clip each campaign's range to the user's requested period
            $reservedRanges = $rows->map(function ($r) use ($start, $end) {
                $rs = Carbon::parse($r->campaign_start);
                $re = Carbon::parse($r->campaign_end);
                $rs = $rs->gt($start) ? $rs->copy()->startOfDay() : $start->copy()->startOfDay();
                $re = $re->lt($end)   ? $re->copy()->startOfDay() : $end->copy()->startOfDay();
                return ['start' => $rs, 'end' => $re];
            })->sortBy(fn($p) => $p['start']->timestamp)->values();

            $periods = $reservedRanges->map(fn($p) => [
                'from' => $p['start']->format('d/m/Y'),
                'to'   => $p['end']->format('d/m/Y'),
            ])->toArray();

            // Compute free gaps within [start, end]
            $freePeriods = [];
            $cursor  = $start->copy()->startOfDay();
            $endDay  = $end->copy()->startOfDay();

            foreach ($reservedRanges as $range) {
                if ($cursor->lt($range['start'])) {
                    $freePeriods[] = [
                        'from' => $cursor->format('d/m/Y'),
                        'to'   => $range['start']->copy()->subDay()->format('d/m/Y'),
                    ];
                }
                $next = $range['end']->copy()->addDay();
                if ($next->gt($cursor)) {
                    $cursor = $next;
                }
            }

            if ($cursor->lte($endDay)) {
                $freePeriods[] = [
                    'from' => $cursor->format('d/m/Y'),
                    'to'   => $endDay->format('d/m/Y'),
                ];
            }

            return [
                'periods'       => $periods,
                'free_periods'  => $freePeriods,
                'locations'     => $locations,
                'cinema_chains' => $cinemaChains,
                'hall_types'    => $hallTypes,
            ];
        };

        // 4️⃣ Construction finale
        $result = $slots->map(function ($slot) use ($assignmentsBySlot, $buildReservedInfo) {

            $slotRows   = $assignmentsBySlot[$slot->id] ?? collect();
            $byPosition = $slotRows->groupBy('position');

            $positions    = [];
            $usedDuration = 0;
            $assignedCount = 0;

            foreach ($slot->positions as $posObj) {
                $pos     = $posObj->id;
                $isSmart = $posObj->type;

                if ($byPosition->has($pos)) {
                    $rows     = $byPosition[$pos];
                    $firstRow = $rows->first();
                    $positions[] = [
                        'position'      => $pos,
                        'type'          => 'reserved',
                        'dcp_id'        => $firstRow->dcp_id,
                        'duration'      => (int) $firstRow->duration,
                        'reserved_info' => $buildReservedInfo($rows),
                    ];
                    $usedDuration += (int) $firstRow->duration;
                    $assignedCount++;
                } else {
                    $positions[] = [
                        'position' => $pos,
                        'type'     => $isSmart ? 'smart' : 'free',
                    ];
                }
            }

            for ($pos = count($slot->positions)+1; $pos <= $slot->max_ad_slot; $pos++) {
                if ($byPosition->has($pos)) {
                    $rows     = $byPosition[$pos];
                    $firstRow = $rows->first();
                    $positions[] = [
                        'position'      => $pos,
                        'type'          => 'reserved',
                        'dcp_id'        => $firstRow->dcp_id,
                        'duration'      => (int) $firstRow->duration,
                        'reserved_info' => $buildReservedInfo($rows),
                    ];
                    $usedDuration += (int) $firstRow->duration;
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
            ->where('type', 'ads')
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

            'master_movie_id'      => 'nullable|array',
            'master_movie_id.*'    => 'integer|exists:master_movies,id',

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
            ->where('type', 'ads')
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

        // 3️⃣ DCP des autres campagnes avec filtres (whereExists = pas de produit cartésien)
        $otherAssignmentsQuery = DB::table('compaign_slot_dcp as csd')
            ->join('compaigns as c', 'c.id', '=', 'csd.compaign_id')
            ->join('dcp_creatives as d', 'd.id', '=', 'csd.dcp_creative_id')
            ->whereIn('csd.slot_id', $slotIds)
            ->whereExists(function ($sub) use ($v) {
                $sub->select(DB::raw(1))
                    ->from('compaign_location as cl')
                    ->whereColumn('cl.compaign_id', 'c.id')
                    ->whereIn('cl.location_id', $v['location_id']);
            })
            ->whereExists(function ($sub) use ($v) {
                $sub->select(DB::raw(1))
                    ->from('compaign_cinema_chain as ccc')
                    ->whereColumn('ccc.compaign_id', 'c.id')
                    ->whereIn('ccc.cinema_chain_id', $v['cinema_chain_id']);
            })
            ->where('csd.compaign_id', '<>', $v['compaign_id'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('c.start_date', [$start, $end])
                ->orWhereBetween('c.end_date', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('c.start_date', '<=', $start)
                        ->where('c.end_date', '>=', $end);
                });
            });

        if (!empty($v['hall_type_id'])) {
            $otherAssignmentsQuery->whereExists(function ($sub) use ($v) {
                $sub->select(DB::raw(1))
                    ->from('compaign_hall_type as cht')
                    ->whereColumn('cht.compaign_id', 'c.id')
                    ->whereIn('cht.hall_type_id', $v['hall_type_id']);
            });
        }

        $otherAssignments = $otherAssignmentsQuery
            ->select(
                'csd.slot_id',
                'csd.position',
                'csd.compaign_id',
                'c.id as campaign_id',
                'c.start_date as campaign_start',
                'c.end_date as campaign_end',
                'd.id as dcp_id',
                'd.name as dcp_name',
                'd.duration'
            )
            ->orderBy('csd.position')
            ->get();

        // 4️⃣ Batch queries for conflict details on other campaigns
        $otherCampaignIds = $otherAssignments->pluck('campaign_id')->unique()->values()->toArray();

        $conflictLocations    = collect();
        $conflictCinemaChains = collect();
        $conflictHallTypes    = collect();

        if (!empty($otherCampaignIds)) {
            $conflictLocations = DB::table('compaign_location as cl')
                ->join('locations as l', 'l.id', '=', 'cl.location_id')
                ->whereIn('cl.compaign_id', $otherCampaignIds)
                ->whereIn('cl.location_id', $v['location_id'])
                ->select('cl.compaign_id', 'l.name')
                ->get()
                ->groupBy('compaign_id')
                ->map(fn($g) => $g->pluck('name')->toArray());

            $conflictCinemaChains = DB::table('compaign_cinema_chain as ccc')
                ->join('cinema_chains as cc', 'cc.id', '=', 'ccc.cinema_chain_id')
                ->whereIn('ccc.compaign_id', $otherCampaignIds)
                ->whereIn('ccc.cinema_chain_id', $v['cinema_chain_id'])
                ->select('ccc.compaign_id', 'cc.name')
                ->get()
                ->groupBy('compaign_id')
                ->map(fn($g) => $g->pluck('name')->toArray());

            if (!empty($v['hall_type_id'])) {
                $conflictHallTypes = DB::table('compaign_hall_type as cht')
                    ->join('hall_types as ht', 'ht.id', '=', 'cht.hall_type_id')
                    ->whereIn('cht.compaign_id', $otherCampaignIds)
                    ->whereIn('cht.hall_type_id', $v['hall_type_id'])
                    ->select('cht.compaign_id', 'ht.name')
                    ->get()
                    ->groupBy('compaign_id')
                    ->map(fn($g) => $g->pluck('name')->toArray());
            }
        }

        // Helper: build reserved_info for a group of rows sharing the same position
        $buildReservedInfo = function ($rows) use ($start, $end, $conflictLocations, $conflictCinemaChains, $conflictHallTypes) {

            $campIds      = $rows->pluck('campaign_id')->toArray();
            $locations    = collect($campIds)->flatMap(fn($cid) => $conflictLocations[$cid] ?? [])->unique()->values()->toArray();
            $cinemaChains = collect($campIds)->flatMap(fn($cid) => $conflictCinemaChains[$cid] ?? [])->unique()->values()->toArray();
            $hallTypes    = collect($campIds)->flatMap(fn($cid) => $conflictHallTypes[$cid] ?? [])->unique()->values()->toArray();

            $reservedRanges = $rows->map(function ($r) use ($start, $end) {
                $rs = Carbon::parse($r->campaign_start);
                $re = Carbon::parse($r->campaign_end);
                $rs = $rs->gt($start) ? $rs->copy()->startOfDay() : $start->copy()->startOfDay();
                $re = $re->lt($end)   ? $re->copy()->startOfDay() : $end->copy()->startOfDay();
                return ['start' => $rs, 'end' => $re];
            })->sortBy(fn($p) => $p['start']->timestamp)->values();

            $periods = $reservedRanges->map(fn($p) => [
                'from' => $p['start']->format('d/m/Y'),
                'to'   => $p['end']->format('d/m/Y'),
            ])->toArray();

            $freePeriods = [];
            $cursor  = $start->copy()->startOfDay();
            $endDay  = $end->copy()->startOfDay();

            foreach ($reservedRanges as $range) {
                if ($cursor->lt($range['start'])) {
                    $freePeriods[] = [
                        'from' => $cursor->format('d/m/Y'),
                        'to'   => $range['start']->copy()->subDay()->format('d/m/Y'),
                    ];
                }
                $next = $range['end']->copy()->addDay();
                if ($next->gt($cursor)) {
                    $cursor = $next;
                }
            }

            if ($cursor->lte($endDay)) {
                $freePeriods[] = [
                    'from' => $cursor->format('d/m/Y'),
                    'to'   => $endDay->format('d/m/Y'),
                ];
            }

            return [
                'periods'       => $periods,
                'free_periods'  => $freePeriods,
                'locations'     => $locations,
                'cinema_chains' => $cinemaChains,
                'hall_types'    => $hallTypes,
            ];
        };

        // 5️⃣ Group séparé : campagne courante VS autres (priorité à la courante)
        $currentBySlot = $currentAssignments->groupBy('slot_id');
        $otherBySlot   = $otherAssignments->groupBy('slot_id');

        // 6️⃣ Construction finale avec ordre exact et smart positions
        $result = $slots->map(function ($slot) use ($currentBySlot, $otherBySlot, $buildReservedInfo, $v) {

            $currentByPos = ($currentBySlot[$slot->id] ?? collect())->keyBy('position');
            $otherByPos   = ($otherBySlot[$slot->id] ?? collect())->groupBy('position');

            $positions = [];
            $usedDuration = 0;
            $assignedCount = 0;

            foreach ($slot->positions as $posObj) {
                $pos = $posObj->id;
                $isSmart = $posObj->type; // true/false

                // 🔴 Autre campagne en conflit = priorité absolue → reserved
                if ($otherByPos->has($pos)) {
                    $rows     = $otherByPos[$pos];
                    $firstRow = $rows->first();
                    $positions[] = [
                        'position'      => $pos,
                        'type'          => 'reserved',
                        'dcp_id'        => $firstRow->dcp_id,
                        'dcp_name'      => null,
                        'duration'      => (int) $firstRow->duration,
                        'reserved_info' => $buildReservedInfo($rows),
                    ];
                    $usedDuration += (int) $firstRow->duration;
                    $assignedCount++;
                // 🟢 Seulement la campagne courante (pas de conflit)
                } elseif ($currentByPos->has($pos)) {
                    $row = $currentByPos[$pos];
                    $positions[] = [
                        'position' => $pos,
                        'type'     => 'current',
                        'dcp_id'   => $row->dcp_id,
                        'dcp_name' => $row->dcp_name,
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
                // 🔴 Autre campagne en conflit = priorité absolue → reserved
                if ($otherByPos->has($pos)) {
                    $rows     = $otherByPos[$pos];
                    $firstRow = $rows->first();
                    $positions[] = [
                        'position'      => $pos,
                        'type'          => 'reserved',
                        'dcp_id'        => $firstRow->dcp_id,
                        'duration'      => (int) $firstRow->duration,
                        'reserved_info' => $buildReservedInfo($rows),
                    ];
                    $usedDuration += (int) $firstRow->duration;
                    $assignedCount++;
                // 🟢 Seulement la campagne courante (pas de conflit)
                } elseif ($currentByPos->has($pos)) {
                    $row = $currentByPos[$pos];
                    $positions[] = [
                        'position' => $pos,
                        'type'     => 'current',
                        'dcp_id'   => $row->dcp_id,
                        'dcp_name' => $row->dcp_name,
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

            // En mode edit : toujours afficher si la campagne courante ou une autre campagne
            // a des positions dans ce slot (pour montrer les conflits)
            $hasCurrentCampaign = $currentByPos->isNotEmpty();
            $hasReservedPosition = $otherByPos->isNotEmpty();

            if (
                ($remainingDuration <= 0 || $assignedCount >= $slot->max_ad_slot)
                && !$hasCurrentCampaign
                && !$hasReservedPosition
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
