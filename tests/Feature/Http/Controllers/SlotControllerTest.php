<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Slot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SlotController
 */
final class SlotControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_displays_view(): void
    {
        $slots = Slot::factory()->count(3)->create();

        $response = $this->get(route('slots.index'));

        $response->assertOk();
        $response->assertViewIs('slots.index');
        $response->assertViewHas('slots');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $slot = Slot::factory()->create();

        $response = $this->get(route('slots.show', $slot));

        $response->assertOk();
        $response->assertViewIs('slots.show');
        $response->assertViewHas('slot');
    }
}
