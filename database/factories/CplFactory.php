<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Cpl;
use App\Models\Location;

class CplFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cpl::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'durationEdits' =>  $this->faker->name(),
            'storageKind' =>  $this->faker->name(),
            'name' => $this->faker->name(),
            'contentKind' =>  $this->faker->name(),
            'editRate_numerator' =>  $this->faker->name(),
            'editRate_denominator' =>  $this->faker->name(),
            'editRateFPS' =>  $this->faker->name(),
            'pictureWidth' => $this->faker->randomFloat(2, 0, 999999.99),
            'pictureHeight' => $this->faker->randomFloat(2, 0, 999999.99),
            'pictureEncodingAlgorithm' =>  $this->faker->name(),
            'pictureEncryptionAlgorithm' =>  $this->faker->name(),
            'soundChannelCount' => $this->faker->randomFloat(2, 0, 999999.99),
            'soundQuantizationBits' => $this->faker->randomFloat(2, 0, 999999.99),
            'soundEncodingAlgorithm' =>  $this->faker->name(),
            'soundEncryptionAlgorithm' =>  $this->faker->name(),
            'encryptionKeysCount' => $this->faker->randomFloat(2, 0, 999999.99),
            'framesPerEdit' => $this->faker->randomFloat(2, 0, 999999.99),
            'is3D' => $this->faker->boolean(),
            'standardCompliance' =>  $this->faker->name(),
            'soundSamplingRate_numerator' =>  $this->faker->name(),
            'soundSamplingRate_denominator' =>  $this->faker->name(),
            'assets' => $this->faker->randomFloat(2, 0, 999999.99),
            'cplSizeInBytes' => $this->faker->randomFloat(2, 0, 999999.99),
            'packageSizeInBytes' => $this->faker->randomFloat(2, 0, 999999.99),
            'markersCount' =>  $this->faker->name(),
            'playable' =>  $this->faker->name(),
            'last_update' => $this->faker->dateTime(),
            'cpl_list_uuivd' =>  $this->faker->name(),
            'id_auditorium' => $this->faker->randomFloat(2, 0, 999999.99),
            'id_server' =>  $this->faker->name(),
            'location_id' => Location::factory(),
        ];
    }
}
