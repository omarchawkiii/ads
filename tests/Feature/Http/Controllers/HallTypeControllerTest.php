<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\HallType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\HallTypeController
 */
final class HallTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_displays_view(): void
    {
        $hallTypes = HallType::factory()->count(3)->create();

        $response = $this->get(route('hall-types.index'));

        $response->assertOk();
        $response->assertViewIs('hall_types.index');
        $response->assertViewHas('hall_types');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $hallType = HallType::factory()->create();

        $response = $this->get(route('hall-types.show', $hallType));

        $response->assertOk();
        $response->assertViewIs('hall_types.show');
        $response->assertViewHas('hall_type');
    }
}
