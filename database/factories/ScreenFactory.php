<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Location;
use App\Models\Screen;

class ScreenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Screen::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'seat' => $this->faker->randomNumber(),
            'api_namespace' => $this->faker->name(),
            'type' => $this->faker->name(),
            'masking_movement' => $this->faker->name(),
            'screen_h' => $this->faker->randomFloat(2, 0, 999999.99),
            'screen_w' => $this->faker->randomFloat(2, 0, 999999.99),
            'screen_d' => $this->faker->randomFloat(2, 0, 999999.99),
            'projector_brand' => $this->faker->name(),
            'projector_model' => $this->faker->name(),
            'projector_ip_lan' => $this->faker->name(),
            'lens_model' => $this->faker->name(),
            'installed' => $this->faker->boolean(),
            'server_brand' => $this->faker->name(),
            'server_model' => $this->faker->name(),
            'server_ip_lan' => $this->faker->name(),
            'ingest_capabilities' => $this->faker->name(),
            'd_brand' => $this->faker->name(),
            'd_model' => $this->faker->name(),
            'automation_brand' => $this->faker->name(),
            'automation_model' => $this->faker->name(),
            'automation_ip_lan' => $this->faker->name(),
            'satelite_or_live' => $this->faker->name(),
            'transmission_brand' => $this->faker->name(),
            'transmission_model' => $this->faker->name(),
            'transmission_ip_lan' => $this->faker->name(),
            'processor_brand' => $this->faker->name(),
            'processor_model' => $this->faker->name(),
            'processor_ip_lan' => $this->faker->name(),
            'audio_type' => $this->faker->name(),
            'audio_brand' => $this->faker->name(),
            'audio_model' => $this->faker->name(),
            'audio_channel' => $this->faker->name(),
            'audio_frequency' => $this->faker->name(),
            'location_id' => Location::factory(),
        ];
    }
}
