<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Brand;
use App\Models\Compaign;
use App\Models\CompaignCategory;
use App\Models\CompaignObjective;
use App\Models\CompaignObjectiveCompaignCategoryBrandLangueMovieGenderSlotLocation;
use App\Models\Gender;
use App\Models\Langue;
use App\Models\Location;
use App\Models\Movie;
use App\Models\Slot;

class CompaignFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Compaign::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'compaign_objective_id' => CompaignObjective::factory(),
            'compaign_category_id' => CompaignCategory::factory(),
            'brand_id' => Brand::factory(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'budget' => $this->faker->numberBetween(-10000, 10000),
            'langue_id' => Langue::factory(),
            'note' => $this->faker->text(),
            'movie_id' => Movie::factory(),
            'gender_id' => Gender::factory(),
            'slot_id' => Slot::factory(),
            'ad_duration' => $this->faker->numberBetween(-10000, 10000),
            'location_id' => Location::factory(),
            'compaign_objective_compaign_category_brand_langue_movie_gender_slot_location_id' => CompaignObjectiveCompaignCategoryBrandLangueMovieGenderSlotLocation::factory(),
        ];
    }
}
