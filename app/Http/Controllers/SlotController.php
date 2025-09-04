<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
}
