<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Compaign;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CompaignController
 */
final class CompaignControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_displays_view(): void
    {
        $compaigns = Compaign::factory()->count(3)->create();

        $response = $this->get(route('compaigns.index'));

        $response->assertOk();
        $response->assertViewIs('compaigns.index');
        $response->assertViewHas('compaigns');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $compaign = Compaign::factory()->create();

        $response = $this->get(route('compaigns.show', $compaign));

        $response->assertOk();
        $response->assertViewIs('compaigns.show');
        $response->assertViewHas('compaign');
    }
}
