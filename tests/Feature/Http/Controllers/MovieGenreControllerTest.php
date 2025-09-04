<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\MovieGenre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\MovieGenreController
 */
final class MovieGenreControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_displays_view(): void
    {
        $movieGenres = MovieGenre::factory()->count(3)->create();

        $response = $this->get(route('movie-genres.index'));

        $response->assertOk();
        $response->assertViewIs('movie_genres.index');
        $response->assertViewHas('movie_genres');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $movieGenre = MovieGenre::factory()->create();

        $response = $this->get(route('movie-genres.show', $movieGenre));

        $response->assertOk();
        $response->assertViewIs('movie_genres.show');
        $response->assertViewHas('movie_genre');
    }
}
