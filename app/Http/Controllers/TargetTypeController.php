<?php

namespace App\Http\Controllers;

use App\Models\TargetType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TargetTypeController extends Controller
{
    public function index()
    {
        return view('admin.target_types.index');
    }

    public function show(Request $request)
    {
        $target = TargetType::findOrFail($request->id) ;
        return Response()->json(compact('target'));
    }

    public function get()
    {
        $targets = TargetType::orderBy('name', 'asc')->get();
        return Response()->json(compact('targets'));
    }

    public function store(Request $request)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'detail' => ['required', 'string', 'max:255'],
                'cpm'  => ['required', 'integer', 'min:0'],
            ]);
            $target_type = TargetType::create($validated);
            return response()->json([
                'message' => '^Target Type created successfully.',
                'data' => $target_type,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function update(Request $request, TargetType $target)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'detail' => ['required', 'string', 'max:255'],
                'cpm'  => ['required', 'integer', 'min:0'],
            ]);

            $target->update($validated);
            return response()->json([
                'message' => 'Target Type updated successfully.',
                'data' => $target,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function destroy(TargetType $target)
    {
        try
        {
            $target->delete();
            return response()->json([
                'message' => 'Target Type deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }
}
