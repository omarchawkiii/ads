<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Interest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\InterestController
 */
final class InterestControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_displays_view(): void
    {
        $interests = Interest::factory()->count(3)->create();

        $response = $this->get(route('interests.index'));

        $response->assertOk();
        $response->assertViewIs('interests.index');
        $response->assertViewHas('interests');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $interest = Interest::factory()->create();

        $response = $this->get(route('interests.show', $interest));

        $response->assertOk();
        $response->assertViewIs('interests.show');
        $response->assertViewHas('interest');
    }
}
