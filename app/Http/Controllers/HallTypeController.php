<?php

namespace App\Http\Controllers;

use App\Models\HallType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HallTypeController extends Controller
{
    public function index()
    {
        return view('admin.halls.index');
    }

    public function show(Request $request)
    {
        $hall = HallType::findOrFail($request->id) ;
        return Response()->json(compact('hall'));
    }

    public function get()
    {
        $halls = HallType::orderBy('name', 'asc')->get();
        return Response()->json(compact('halls'));
    }

    public function store(Request $request)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);
            $hall = HallType::create($validated);
            return response()->json([
                'message' => 'hall created successfully.',
                'data' => $hall,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function update(Request $request, HallType $hall)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $hall->update($validated);
            return response()->json([
                'message' => 'hall updated successfully.',
                'data' => $hall,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function destroy(HallType $hall)
    {
        try
        {
            $hall->delete();
            return response()->json([
                'message' => 'hall deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }
}
