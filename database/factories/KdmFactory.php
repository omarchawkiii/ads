<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Cpl;
use App\Models\Kdm;
use App\Models\Screen;

class KdmFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Kdm::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'name' => $this->faker->name(),
            'AnnotationText' =>  $this->faker->name(),
            'ContentKeysNotValidBefore' =>  $this->faker->name(),
            'ContentKeysNotValidAfter' =>  $this->faker->name(),
            'SubjectName' =>  $this->faker->name(),
            'DeviceListDescription' =>  $this->faker->name(),
            'SerialNumber' =>  $this->faker->name(),
            'path_file' =>  $this->faker->name(),
            'server_name' =>  $this->faker->name(),
            'file_type' =>  $this->faker->name(),
            'id_server' =>  $this->faker->name(),
            'file_size' => $this->faker->randomFloat(2, 0, 999999.99),
            'file_progress' => $this->faker->randomFloat(2, 0, 999999.99),
            'tms_path' =>  $this->faker->name(),
            'last_update' => $this->faker->dateTime(),
            'screen_id' => Screen::factory(),
            'cpl_id' => Cpl::factory(),
        ];
    }
}
