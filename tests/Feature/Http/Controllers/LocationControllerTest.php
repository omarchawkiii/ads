<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\LocationController
 */
final class LocationControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_displays_view(): void
    {
        $locations = Location::factory()->count(3)->create();

        $response = $this->get(route('locations.index'));

        $response->assertOk();
        $response->assertViewIs('locations.index');
        $response->assertViewHas('locations');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $location = Location::factory()->create();

        $response = $this->get(route('locations.show', $location));

        $response->assertOk();
        $response->assertViewIs('locations.show');
        $response->assertViewHas('location');
    }
}
