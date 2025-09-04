<?php

namespace App\Http\Controllers;

use App\Models\CompaignObjective;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompaignObjectiveController extends Controller
{
    public function index()
    {
        return view('admin.compaign_objectives.index');
    }

    public function show(Request $request)
    {
        $compaign_objective = CompaignObjective::findOrFail($request->id) ;
        return Response()->json(compact('compaign_objective'));
    }

    public function get()
    {
        $compaign_objectives = CompaignObjective::orderBy('name', 'asc')->get();
        return Response()->json(compact('compaign_objectives'));
    }

    public function store(Request $request)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);
            $compaign_objective = CompaignObjective::create($validated);
            return response()->json([
                'message' => 'Compaign Objective created successfully.',
                'data' => $compaign_objective,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function update(Request $request, CompaignObjective $compaign_objective)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $compaign_objective->update($validated);
            return response()->json([
                'message' => 'Compaign Objective updated successfully.',
                'data' => $compaign_objective,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function destroy(CompaignObjective $compaign_objective)
    {
        try
        {
            $compaign_objective->delete();
            return response()->json([
                'message' => 'Compaign Objective deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }
}
