<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\CompaignObjective;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CompaignObjectiveController
 */
final class CompaignObjectiveControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_displays_view(): void
    {
        $compaignObjectives = CompaignObjective::factory()->count(3)->create();

        $response = $this->get(route('compaign-objectives.index'));

        $response->assertOk();
        $response->assertViewIs('compaign_objectives.index');
        $response->assertViewHas('compaign_objectives');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $compaignObjective = CompaignObjective::factory()->create();

        $response = $this->get(route('compaign-objectives.show', $compaignObjective));

        $response->assertOk();
        $response->assertViewIs('compaign_objectives.show');
        $response->assertViewHas('compaign_objective');
    }
}
