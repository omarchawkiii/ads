<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\CompaignCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CompaignCategoryController
 */
final class CompaignCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_displays_view(): void
    {
        $compaignCategories = CompaignCategory::factory()->count(3)->create();

        $response = $this->get(route('compaign-categories.index'));

        $response->assertOk();
        $response->assertViewIs('compaign_categories.index');
        $response->assertViewHas('compaign_categories');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $compaignCategory = CompaignCategory::factory()->create();

        $response = $this->get(route('compaign-categories.show', $compaignCategory));

        $response->assertOk();
        $response->assertViewIs('compaign_categories.show');
        $response->assertViewHas('compaign_category');
    }
}
