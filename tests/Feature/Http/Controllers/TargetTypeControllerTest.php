<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\TargetType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TargetTypeController
 */
final class TargetTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_displays_view(): void
    {
        $targetTypes = TargetType::factory()->count(3)->create();

        $response = $this->get(route('target-types.index'));

        $response->assertOk();
        $response->assertViewIs('target_types.index');
        $response->assertViewHas('target_types');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $targetType = TargetType::factory()->create();

        $response = $this->get(route('target-types.show', $targetType));

        $response->assertOk();
        $response->assertViewIs('target_types.show');
        $response->assertViewHas('target_type');
    }
}
