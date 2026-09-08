<?php

namespace App\Http\Controllers;

use App\Models\MasterMovie;
use App\Models\Movie;
use App\Models\MovieGenre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'year'             => 'nullable|date',
            'rating'           => 'nullable|string|max:20',
            'imdb_rating'      => 'nullable|numeric|min:0|max:10',
            'runtime'          => 'nullable|integer|min:1',
            'plot'             => 'nullable|string',
            'country'          => 'nullable|string|max:255',
            'language'         => 'nullable|string|max:255',
            'director'         => 'nullable|string|max:255',
            'actors'           => 'nullable|string',
            'writer'           => 'nullable|string|max:255',
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
            'year'             => 'nullable|date',
            'rating'           => 'nullable|string|max:20',
            'imdb_rating'      => 'nullable|numeric|min:0|max:10',
            'runtime'          => 'nullable|integer|min:1',
            'plot'             => 'nullable|string',
            'country'          => 'nullable|string|max:255',
            'language'         => 'nullable|string|max:255',
            'director'         => 'nullable|string|max:255',
            'actors'           => 'nullable|string',
            'writer'           => 'nullable|string|max:255',
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

    /**
     * Search movies on OMDB by title. GET /master-movies/omdb/search?s=...&page=1
     */
    public function omdbSearch(Request $request)
    {
        $request->validate([
            'q'    => 'required|string|min:1|max:255',
            'page' => 'nullable|integer|min:1',
        ]);

        $apiKey = config('services.omdb.key');
        if (!$apiKey) {
            return response()->json(['message' => 'OMDB API key is not configured on the server.'], 500);
        }

        $response = Http::get('https://www.omdbapi.com/', [
            'apikey' => $apiKey,
            's'      => $request->input('q'),
            'type'   => 'movie',
            'page'   => $request->input('page', 1),
        ]);

        $data = $response->json();

        if (!$response->ok() || ($data['Response'] ?? 'False') === 'False') {
            return response()->json([
                'message' => $data['Error'] ?? 'No results found.',
                'results' => [],
            ], 200);
        }

        return response()->json([
            'results'     => $data['Search'] ?? [],
            'total'       => (int) ($data['totalResults'] ?? 0),
            'imported_ids'=> MasterMovie::whereNotNull('imdb_id')->pluck('imdb_id'),
        ]);
    }

    /**
     * Fetch full details for one title from OMDB. GET /master-movies/omdb/{imdbId}
     */
    public function omdbShow($imdbId)
    {
        $apiKey = config('services.omdb.key');
        if (!$apiKey) {
            return response()->json(['message' => 'OMDB API key is not configured on the server.'], 500);
        }

        $response = Http::get('https://www.omdbapi.com/', [
            'apikey' => $apiKey,
            'i'      => $imdbId,
            'plot'   => 'full',
        ]);

        $data = $response->json();

        if (!$response->ok() || ($data['Response'] ?? 'False') === 'False') {
            return response()->json(['message' => $data['Error'] ?? 'Movie not found.'], 404);
        }

        $data['already_imported'] = MasterMovie::where('imdb_id', $imdbId)->exists();

        return response()->json(['movie' => $data]);
    }

    /**
     * Import a title from OMDB straight into the master_movies table.
     * POST /master-movies/omdb/import  { imdb_id: "tt1234567" }
     */
    public function omdbImport(Request $request)
    {
        $request->validate([
            'imdb_id' => 'required|string|max:20',
        ]);

        $imdbId = $request->input('imdb_id');

        $existing = MasterMovie::where('imdb_id', $imdbId)->first();
        if ($existing) {
            return response()->json([
                'message'     => 'This movie has already been imported.',
                'masterMovie' => $existing->load('genres'),
            ], 200);
        }

        $apiKey = config('services.omdb.key');
        if (!$apiKey) {
            return response()->json(['message' => 'OMDB API key is not configured on the server.'], 500);
        }

        $response = Http::get('https://www.omdbapi.com/', [
            'apikey' => $apiKey,
            'i'      => $imdbId,
            'plot'   => 'full',
        ]);

        $data = $response->json();
        if (!$response->ok() || ($data['Response'] ?? 'False') === 'False') {
            return response()->json(['message' => $data['Error'] ?? 'Movie not found.'], 404);
        }

        $masterMovie = MasterMovie::create([
            'imdb_id'     => $imdbId,
            'title'       => $data['Title'] ?? '',
            'year'        => $this->parseOmdbDate($data['Released'] ?? null, $data['Year'] ?? null),
            'rating'      => (($data['Rated'] ?? 'N/A') !== 'N/A') ? $data['Rated'] : null,
            'imdb_rating' => (($data['imdbRating'] ?? 'N/A') !== 'N/A') ? $data['imdbRating'] : null,
            'runtime'     => $this->parseOmdbRuntime($data['Runtime'] ?? null),
            'plot'        => (($data['Plot'] ?? 'N/A') !== 'N/A') ? $data['Plot'] : null,
            'country'     => (($data['Country'] ?? 'N/A') !== 'N/A') ? $data['Country'] : null,
            'language'    => (($data['Language'] ?? 'N/A') !== 'N/A') ? $data['Language'] : null,
            'director'    => (($data['Director'] ?? 'N/A') !== 'N/A') ? $data['Director'] : null,
            'actors'      => (($data['Actors'] ?? 'N/A') !== 'N/A') ? $data['Actors'] : null,
            'writer'      => (($data['Writer'] ?? 'N/A') !== 'N/A') ? $data['Writer'] : null,
        ]);

        $posterUrl = $data['Poster'] ?? null;
        if ($posterUrl && $posterUrl !== 'N/A') {
            $imagePath = $this->downloadImage($posterUrl);
            if ($imagePath) {
                $masterMovie->update(['image' => $imagePath]);
            }
        }

        if (!empty($data['Genre']) && $data['Genre'] !== 'N/A') {
            $genreIds = [];
            foreach (explode(',', $data['Genre']) as $name) {
                $name = trim($name);
                if ($name === '') continue;
                $genre = MovieGenre::whereRaw('LOWER(name) = ?', [strtolower($name)])->first()
                    ?? MovieGenre::create(['name' => $name]);
                $genreIds[] = $genre->id;
            }
            $masterMovie->genres()->sync($genreIds);
        }

        return response()->json([
            'message'     => 'Movie imported successfully.',
            'masterMovie' => $masterMovie->load('genres'),
        ], 201);
    }

    /**
     * OMDB "Released" is like "10 Sep 2026", "Year" is like "2026" or "2019–2021".
     * Returns a Y-m-d string usable by the date input, or null.
     */
    private function parseOmdbDate(?string $released, ?string $year): ?string
    {
        if ($released && $released !== 'N/A') {
            try {
                return \Carbon\Carbon::createFromFormat('d M Y', $released)->format('Y-m-d');
            } catch (\Exception $e) {
                // fall through to year-only handling below
            }
        }

        if ($year) {
            $year = substr(trim($year), 0, 4);
            if (ctype_digit($year)) {
                return $year . '-01-01';
            }
        }

        return null;
    }

    /**
     * OMDB "Runtime" is like "142 min".
     */
    private function parseOmdbRuntime(?string $runtime): ?int
    {
        if ($runtime && preg_match('/(\d+)/', $runtime, $m)) {
            return (int) $m[1];
        }
        return null;
    }

    private function downloadImage(string $url): ?string
    {
        try {
            $response = Http::timeout(15)->get($url);
            if (!$response->ok()) return null;

            $dest = storage_path('app/public/master_movies');
            if (!is_dir($dest)) {
                mkdir($dest, 0755, true);
            }

            $ext = 'jpg';
            if (preg_match('/\.(jpe?g|png|webp)(\?|$)/i', $url, $m)) {
                $ext = strtolower($m[1]);
            }

            $filename = Str::uuid() . '.' . $ext;
            file_put_contents($dest . DIRECTORY_SEPARATOR . $filename, $response->body());

            return 'master_movies/' . $filename;
        } catch (\Exception $e) {
            return null;
        }
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
