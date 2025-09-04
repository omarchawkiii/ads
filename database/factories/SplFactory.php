<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Location;
use App\Models\Spl;

class SplFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Spl::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'uuid' => $this->faker->uuid(),
            'annotation' =>  $this->faker->name(),
            'issue_date' =>  $this->faker->name(),
            'creator' =>  $this->faker->name(),
            'path_file' =>  $this->faker->name(),
            'server_name' =>  $this->faker->name(),
            'last_update' => $this->faker->dateTime(),
            'file_type' =>  $this->faker->name(),
            'duration' => $this->faker->numberBetween(-10000, 10000),
            'is_downloaded' => $this->faker->numberBetween(-10000, 10000),
            'tms_path' =>  $this->faker->name(),
            'id_server' =>  $this->faker->name(),
            'id_local_server' => $this->faker->numberBetween(-10000, 10000),
            'file_size' => $this->faker->randomFloat(2, 0, 999999.99),
            'file_progress' => $this->faker->randomFloat(2, 0, 999999.99),
            'spl_type' =>  $this->faker->name(),
            'location_id' => Location::factory(),
        ];
    }
}
