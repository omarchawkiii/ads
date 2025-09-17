<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
class MovieController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.movies.index');
    }

    public function show(Request $request)
    {
        $movie = Movie::findOrFail($request->id) ;
        return Response()->json(compact('movie'));
    }

    public function get()
    {
        $movies = Movie::orderBy('name', 'asc')->get();
        return Response()->json(compact('movies'));
    }

    public function store(Request $request)
    {
        try
        {

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);
             $uuid = (string) Str::uuid();
            $validated['uuid'] =  'urn:uuid:' . $uuid;
            $movie = Movie::create($validated);

            return response()->json([
                'message' => 'Movie created successfully.',
                'data' => $movie,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function update(Request $request, Movie $movie)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);
             $uuid = (string) Str::uuid();
            $validated['uuid']  = 'urn:uuid:' . $uuid;
            $movie->update($validated);

            return response()->json([
                'message' => 'Movie updated successfully.',
                'data' => $movie,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function destroy(Movie $movie)
    {
        try
        {
            $movie->delete();

            return response()->json([
                'message' => 'Movie deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }
}
