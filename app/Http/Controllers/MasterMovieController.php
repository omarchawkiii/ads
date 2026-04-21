<?php

namespace App\Http\Controllers;

use App\Models\MasterMovie;
use App\Models\Movie;
use App\Models\MovieGenre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterMovieController extends Controller
{
    public function get()
    {
        $masterMovies = MasterMovie::with('genres')
            ->withCount('movies')
            ->withCount(['movies as noc_count' => fn($q) => $q->whereNotNull('cinema_chain_id')])
            ->orderBy('title')
            ->get();
        return response()->json(compact('masterMovies'));
    }

    public function getLinked()
    {
        $movies = Movie::with('genre', 'langue', 'masterMovie', 'cinemaChain')
            ->whereNotNull('master_movie_id')
            ->orderBy('name')
            ->get();
        return response()->json(compact('movies'));
    }

    public function getUnlinked()
    {
        $movies = Movie::with('genre', 'langue', 'cinemaChain')
            ->whereNull('master_movie_id')
            ->orderBy('name')
            ->get();
        return response()->json(compact('movies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'year'             => 'nullable|string|max:10',
            'rating'           => 'nullable|string|max:20',
            'runtime'          => 'nullable|integer|min:1',
            'plot'             => 'nullable|string',
            'image'            => 'nullable|image|max:2048',
            'movie_genre_ids'  => 'nullable|array',
            'movie_genre_ids.*'=> 'integer|exists:movie_genres,id',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $validated['image'] = $this->saveImage($request->file('image'));
        }

        $masterMovie = MasterMovie::create($validated);
        $masterMovie->genres()->sync($request->input('movie_genre_ids', []));

        return response()->json([
            'message'     => 'Master movie created successfully.',
            'masterMovie' => $masterMovie->load('genres'),
        ], 201);
    }

    public function show($id)
    {
        $masterMovie = MasterMovie::with('genres')->findOrFail($id);
        return response()->json(compact('masterMovie'));
    }

    public function update(Request $request, $id)
    {
        $masterMovie = MasterMovie::findOrFail($id);

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'year'             => 'nullable|string|max:10',
            'rating'           => 'nullable|string|max:20',
            'runtime'          => 'nullable|integer|min:1',
            'plot'             => 'nullable|string',
            'image'            => 'nullable|image|max:2048',
            'movie_genre_ids'  => 'nullable|array',
            'movie_genre_ids.*'=> 'integer|exists:movie_genres,id',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($masterMovie->image) {
                Storage::disk('public')->delete($masterMovie->image);
            }
            $validated['image'] = $this->saveImage($request->file('image'));
        }

        $masterMovie->update($validated);
        $masterMovie->genres()->sync($request->input('movie_genre_ids', []));

        return response()->json([
            'message'     => 'Master movie updated successfully.',
            'masterMovie' => $masterMovie->load('genres'),
        ]);
    }

    public function destroy($id)
    {
        $masterMovie = MasterMovie::findOrFail($id);
        if ($masterMovie->image) {
            Storage::disk('public')->delete($masterMovie->image);
        }
        $masterMovie->delete();
        return response()->json(['message' => 'Master movie deleted successfully.']);
    }

    public function link(Request $request)
    {
        $request->validate([
            'movie_id'        => 'required|integer|exists:movies,id',
            'master_movie_id' => 'required|integer|exists:master_movies,id',
        ]);

        Movie::findOrFail($request->movie_id)->update([
            'master_movie_id' => $request->master_movie_id,
        ]);

        return response()->json(['message' => 'Movie linked successfully.']);
    }

    public function unlink($movieId)
    {
        Movie::findOrFail($movieId)->update(['master_movie_id' => null]);
        return response()->json(['message' => 'Movie unlinked successfully.']);
    }

    private function saveImage($file): string
    {
        $dest = storage_path('app/public/master_movies');
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }
        $filename = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move($dest, $filename);
        return 'master_movies/' . $filename;
    }
}
