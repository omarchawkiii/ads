<?php

namespace App\Http\Controllers;

use App\Models\CinemaChain;
use App\Models\Langue;
use App\Models\MasterMovie;
use App\Models\Movie;
use App\Models\MovieGenre;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    public function index()
    {
        $genres        = MovieGenre::orderBy('name')->get();
        $langues       = Langue::orderBy('name')->get();
        $masterMovies  = MasterMovie::orderBy('title')->get();
        $cinemaChains  = CinemaChain::orderBy('name')->get();
        return view('admin.movies.index', compact('genres', 'langues', 'masterMovies', 'cinemaChains'));
    }

    public function show(Request $request)
    {
        $movie = Movie::with('genre', 'langue')->findOrFail($request->id);
        return response()->json(compact('movie'));
    }

    public function get()
    {
        $movies = Movie::with('genre', 'langue')->orderBy('name', 'asc')->get();
        return response()->json(compact('movies'));
    }

    public function sync()
    {
        $chains  = CinemaChain::whereNotNull('ip_address')->get();
        $results = [];

        foreach ($chains as $chain) {

            if (empty(trim($chain->ip_address))) {
                $results[] = ['cinema_chain' => $chain->name, 'success' => false, 'reason' => 'No IP configured.'];
                continue;
            }



            try {
                $url      = rtrim($chain->ip_address, '/') . '/api/adsmart/movies';

                $response = (new Client())->get($url, [
                    'connect_timeout' => 5,
                    'timeout'         => 15,
                    'query'           => [
                        'username' => $chain->username,
                        'password' => $chain->password,
                    ],
                ]);


                $body = json_decode($response->getBody(), true);

                if (empty($body['status']) || empty($body['data'])) {
                    $results[] = [
                        'cinema_chain' => $chain->name,
                        'success'      => false,
                        'reason'       => $body['message'] ?? 'Empty response.',
                    ];
                    continue;
                }

                $created = 0;
                $updated = 0;

                foreach ($body['data'] as $movieData) {
                    $title = $movieData['title'] ?? null;

                    foreach ($movieData['locations'] ?? [] as $loc) {
                        $splUuid = $loc['spl_uuid'] ?? null;
                        if (!$splUuid) continue;

                        $attrs = [
                            'name'            => $title,
                            'title'           => $title,
                            'titleShort'      => $loc['titleShort']    ?? null,
                            'moviescods_id'   => $loc['moviescods_id'] ?? null,
                            'code'            => $loc['code']          ?? null,
                            'exist_inPos'     => !empty($loc['exist_inPos']),
                            'date_linking'    => $loc['date_linking']  ?? null,
                            'status'          => ($loc['status'] ?? '') === 'active' ? 1 : 0,
                            'cinema_chain_id' => $chain->id,
                        ];

                        $existing = Movie::where('spl_uuid', $splUuid)->first();

                        if ($existing) {
                            $existing->update($attrs);
                            $updated++;
                        } else {
                            $attrs['spl_uuid'] = $splUuid;
                            $attrs['uuid']     = 'urn:uuid:' . Str::uuid();
                            Movie::create($attrs);
                            $created++;
                        }
                    }
                }

                $results[] = [
                    'cinema_chain' => $chain->name,
                    'success'      => true,
                    'created'      => $created,
                    'updated'      => $updated,
                ];

            } catch (RequestException $e) {
                if ($e->hasResponse()) {
                    $parsed = json_decode((string) $e->getResponse()->getBody(), true);
                    $detail = $parsed['message'] ?? (string) $e->getResponse()->getBody();
                } else {
                    $detail = $e->getMessage();
                }
                $results[] = ['cinema_chain' => $chain->name, 'success' => false, 'reason' => $detail];
            } catch (\Exception $e) {
                $results[] = ['cinema_chain' => $chain->name, 'success' => false, 'reason' => $e->getMessage()];
            }
        }

        $totalCreated = collect($results)->sum('created');
        $totalUpdated = collect($results)->sum('updated');

        return response()->json([
            'results'       => $results,
            'total_created' => $totalCreated,
            'total_updated' => $totalUpdated,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'            => ['required', 'string', 'max:255'],
                'movie_genre_id'  => ['required', 'exists:movie_genres,id'],
                'langue_id'       => ['required', 'exists:langues,id'],
                'cinema_chain_id' => ['nullable', 'exists:cinema_chains,id'],
                'runtime'         => ['nullable', 'integer', 'min:1'],
                'status'          => ['required', 'boolean'],
            ]);
            $validated['uuid'] = 'urn:uuid:' . Str::uuid();
            $movie = Movie::create($validated);

            return response()->json(['message' => 'Movie created successfully.', 'data' => $movie], 201);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Operation failed.'], 500);
        }
    }

    public function update(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'movie_genre_id'  => ['required', 'exists:movie_genres,id'],
            'langue_id'       => ['required', 'exists:langues,id'],
            'cinema_chain_id' => ['nullable', 'exists:cinema_chains,id'],
            'runtime'         => ['nullable', 'integer', 'min:1'],
            'status'          => ['required', 'boolean'],
        ]);

        $movie->update($validated);
        return response()->json(['message' => 'Movie updated successfully.', 'data' => $movie]);
    }

    public function destroy(Movie $movie)
    {
        try {
            $movie->delete();
            return response()->json(['message' => 'Movie deleted successfully.']);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Operation failed.'], 500);
        }
    }
}
