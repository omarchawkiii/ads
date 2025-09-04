<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InterestController extends Controller
{
    public function index()
    {
        return view('admin.interests.index');
    }

    public function show(Request $request)
    {
        $interest = Interest::findOrFail($request->id) ;
        return Response()->json(compact('interest'));
    }

    public function get()
    {
        $interests = Interest::orderBy('name', 'asc')->get();
        return Response()->json(compact('interests'));
    }

    public function store(Request $request)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);
            $interest = Interest::create($validated);
            return response()->json([
                'message' => 'Interests created successfully.',
                'data' => $interest,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function update(Request $request, Interest $interest)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $interest->update($validated);
            return response()->json([
                'message' => 'Interest updated successfully.',
                'data' => $interest,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function destroy(Interest $interest)
    {
        try
        {
            $interest->delete();
            return response()->json([
                'message' => 'Interests deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }
}
