<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CinemaChain;
use App\Models\Config;
use App\Models\Location;
use App\Models\User;

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
                'name'          => ['required', 'string', 'max:255'],
                'contact_name'  => ['nullable', 'string', 'max:255'],
                'contact_email' => ['nullable', 'email', 'max:255'],
                'ip_address'    => ['nullable', 'string', 'max:255'],
                'username'      => ['nullable', 'string', 'max:255'],
                'password'      => ['nullable', 'string', 'max:255'],
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
                'name'          => ['required', 'string', 'max:255'],
                'contact_name'  => ['nullable', 'string', 'max:255'],
                'contact_email' => ['nullable', 'email', 'max:255'],
                'ip_address'    => ['nullable', 'string', 'max:255'],
                'username'      => ['nullable', 'string', 'max:255'],
                'password'      => ['nullable', 'string', 'max:255'],
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

    public function getLocations(Request $request)
    {
        $locations = Location::whereIn('cinema_chain_id', $request->cinema_chain_ids)->get(['id', 'name']);
        return response()->json(['locations' => $locations]);
    }

    /** GET /users/{user}/cinema-chains — all chains with assigned flag */
    public function getUserChains(User $user)
    {
        $allChains   = CinemaChain::orderBy('name')->get(['id', 'name']);
        $assignedIds = $user->cinemaChains()->pluck('cinema_chain_id')->toArray();

        return response()->json([
            'cinemaChains' => $allChains,
            'assignedIds'  => $assignedIds,
        ]);
    }

    /** POST /users/{user}/cinema-chains/sync — sync assigned chains */
    public function syncUserChains(Request $request, User $user)
    {
        try {
            $request->validate([
                'cinema_chain_ids'   => ['nullable', 'array'],
                'cinema_chain_ids.*' => ['integer', 'exists:cinema_chains,id'],
            ]);

            $user->cinemaChains()->sync($request->cinema_chain_ids ?? []);

            return response()->json(['message' => 'Cinema chains assigned successfully.']);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'Operation failed.'], 500);
        }
    }
}
