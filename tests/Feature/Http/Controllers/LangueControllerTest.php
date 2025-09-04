<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Langue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\LangueController
 */
final class LangueControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_displays_view(): void
    {
        $langues = Langue::factory()->count(3)->create();

        $response = $this->get(route('langues.index'));

        $response->assertOk();
        $response->assertViewIs('langues.index');
        $response->assertViewHas('langues');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $langue = Langue::factory()->create();

        $response = $this->get(route('langues.show', $langue));

        $response->assertOk();
        $response->assertViewIs('langues.show');
        $response->assertViewHas('langue');
    }
}
