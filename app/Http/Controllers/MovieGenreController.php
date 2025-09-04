<?php

namespace App\Http\Controllers;

use App\Models\MovieGenre;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovieGenreController extends Controller
{
    public function index()
    {
        return view('admin.movie_genres.index');
    }

    public function show(Request $request)
    {
        $movie_genre = MovieGenre::findOrFail($request->id) ;
        return Response()->json(compact('movie_genre'));
    }

    public function get()
    {
        $movie_genres = MovieGenre::orderBy('name', 'asc')->get();
        return Response()->json(compact('movie_genres'));
    }

    public function store(Request $request)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);
            $movie_genre = MovieGenre::create($validated);
            return response()->json([
                'message' => 'Movie Genres created successfully.',
                'data' => $movie_genre,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function update(Request $request, MovieGenre $movie_genre)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $movie_genre->update($validated);
            return response()->json([
                'message' => 'Movie Genres updated successfully.',
                'data' => $movie_genre,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function destroy(MovieGenre $movie_genre)
    {
        try
        {
            $movie_genre->delete();
            return response()->json([
                'message' => 'Movie Genres deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }
}
