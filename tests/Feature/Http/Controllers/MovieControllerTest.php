<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\MovieController
 */
final class MovieControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_displays_view(): void
    {
        $movies = Movie::factory()->count(3)->create();

        $response = $this->get(route('movies.index'));

        $response->assertOk();
        $response->assertViewIs('movies.index');
        $response->assertViewHas('movies');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $movie = Movie::factory()->create();

        $response = $this->get(route('movies.show', $movie));

        $response->assertOk();
        $response->assertViewIs('movies.show');
        $response->assertViewHas('movie');
    }
}
