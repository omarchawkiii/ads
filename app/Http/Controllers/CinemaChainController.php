<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CinemaChain;
use App\Models\Config;

class CinemaChainController extends Controller
{
    public function index(Request $request)
    {
        $config = Config::first();
        return view('admin.cinema_chains.index', compact('config'));
    }

    public function show(Request $request)
    {
        $cinemaChain = CinemaChain::findOrFail($request->id);
        return response()->json(compact('cinemaChain'));
    }

    public function get()
    {
        $cinemaChains = CinemaChain::orderBy('name', 'asc')->get();
        return response()->json(compact('cinemaChains'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $cinemaChain = CinemaChain::create($validated);

            return response()->json([
                'message' => 'Cinema chain created successfully.',
                'data' => $cinemaChain,
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function update(Request $request, CinemaChain $cinemaChain)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $cinemaChain->update($validated);

            return response()->json([
                'message' => 'Cinema chain updated successfully.',
                'data' => $cinemaChain,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function destroy(CinemaChain $cinemaChain)
    {
        try {
            $cinemaChain->delete();

            return response()->json([
                'message' => 'Cinema chain deleted successfully.',
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }
}
