<?php

namespace App\Http\Controllers;

use App\Models\Langue;
use App\Models\Movie;
use App\Models\MovieGenre;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
class MovieController extends Controller
{
    public function index()
    {
        $genres = MovieGenre::orderBy('name')->get();
        $langues = Langue::orderBy('name')->get();
        return view('admin.movies.index',compact('genres','langues'));
    }

    public function show(Request $request)
    {
        //$movie = Movie::findOrFail($request->id) ;
        $movie = Movie::with('genre','langue')->findOrFail($request->id);
        return Response()->json(compact('movie'));
    }

    public function get()
    {
        $movies = Movie::with('genre','langue')->orderBy('name', 'asc')->get();

        return Response()->json(compact('movies'));
    }

    public function store(Request $request)
    {
        try
        {

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'movie_genre_id' => ['required', 'exists:movie_genres,id'],
                'langue_id' => ['required', 'exists:langues,id'],
                'runtime' => ['nullable', 'integer', 'min:1'],
                'status' => ['required', 'boolean'],
               // 'play_at' => ['nullable', 'date'],
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

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'movie_genre_id' => ['required', 'exists:movie_genres,id'],
                'langue_id' => ['required', 'exists:langues,id'],
                'runtime' => ['nullable', 'integer', 'min:1'],
                'status' => ['required', 'boolean'],
              //  'play_at' => ['nullable', 'date'],
            ]);

            $movie->update($validated);

            return response()->json([
                'message' => 'Movie updated successfully.',
                'data' => $movie,
            ]);

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
