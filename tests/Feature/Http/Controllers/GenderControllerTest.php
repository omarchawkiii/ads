<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Gender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\GenderController
 */
final class GenderControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_displays_view(): void
    {
        $genders = Gender::factory()->count(3)->create();

        $response = $this->get(route('genders.index'));

        $response->assertOk();
        $response->assertViewIs('genders.index');
        $response->assertViewHas('genders');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $gender = Gender::factory()->create();

        $response = $this->get(route('genders.show', $gender));

        $response->assertOk();
        $response->assertViewIs('genders.show');
        $response->assertViewHas('gender');
    }
}
