<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Location;
use App\Models\Screen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ScreenController
 */
final class ScreenControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $screens = Screen::factory()->count(3)->create();

        $response = $this->get(route('screen.index'));

        $response->assertOk();
        $response->assertViewIs('screens.index');
        $response->assertViewHas('screens');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $screen = Screen::factory()->create();

        $response = $this->get(route('screen.show', $screen));

        $response->assertOk();
        $response->assertViewIs('screens.show');
        $response->assertViewHas('screen');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ScreenController::class,
            'store',
            \App\Http\Requests\ScreenControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name();
        $seat = $this->faker->numberBetween(-100000, 100000);
        $api_namespace = $this->faker->word();
        $type = $this->faker->word();
        $masking_movement = $this->faker->word();
        $screen_h = $this->faker->randomFloat(/** decimal_attributes **/);
        $screen_w = $this->faker->randomFloat(/** decimal_attributes **/);
        $screen_d = $this->faker->randomFloat(/** decimal_attributes **/);
        $projector_brand = $this->faker->word();
        $projector_model = $this->faker->word();
        $projector_ip_lan = $this->faker->word();
        $lens_model = $this->faker->word();
        $installed = $this->faker->boolean();
        $server_brand = $this->faker->word();
        $server_model = $this->faker->word();
        $server_ip_lan = $this->faker->word();
        $ingest_capabilities = $this->faker->word();
        $automation_brand = $this->faker->word();
        $automation_model = $this->faker->word();
        $automation_ip_lan = $this->faker->word();
        $satelite_or_live = $this->faker->word();
        $transmission_brand = $this->faker->word();
        $transmission_model = $this->faker->word();
        $transmission_ip_lan = $this->faker->word();
        $processor_brand = $this->faker->word();
        $processor_model = $this->faker->word();
        $processor_ip_lan = $this->faker->word();
        $audio_type = $this->faker->word();
        $audio_brand = $this->faker->word();
        $audio_model = $this->faker->word();
        $audio_channel = $this->faker->word();
        $audio_frequency = $this->faker->word();
        $location = Location::factory()->create();

        $response = $this->post(route('screen.store'), [
            'name' => $name,
            'seat' => $seat,
            'api_namespace' => $api_namespace,
            'type' => $type,
            'masking_movement' => $masking_movement,
            'screen_h' => $screen_h,
            'screen_w' => $screen_w,
            'screen_d' => $screen_d,
            'projector_brand' => $projector_brand,
            'projector_model' => $projector_model,
            'projector_ip_lan' => $projector_ip_lan,
            'lens_model' => $lens_model,
            'installed' => $installed,
            'server_brand' => $server_brand,
            'server_model' => $server_model,
            'server_ip_lan' => $server_ip_lan,
            'ingest_capabilities' => $ingest_capabilities,
            'automation_brand' => $automation_brand,
            'automation_model' => $automation_model,
            'automation_ip_lan' => $automation_ip_lan,
            'satelite_or_live' => $satelite_or_live,
            'transmission_brand' => $transmission_brand,
            'transmission_model' => $transmission_model,
            'transmission_ip_lan' => $transmission_ip_lan,
            'processor_brand' => $processor_brand,
            'processor_model' => $processor_model,
            'processor_ip_lan' => $processor_ip_lan,
            'audio_type' => $audio_type,
            'audio_brand' => $audio_brand,
            'audio_model' => $audio_model,
            'audio_channel' => $audio_channel,
            'audio_frequency' => $audio_frequency,
            'location_id' => $location->id,
        ]);

        $screens = Screen::query()
            ->where('name', $name)
            ->where('seat', $seat)
            ->where('api_namespace', $api_namespace)
            ->where('type', $type)
            ->where('masking_movement', $masking_movement)
            ->where('screen_h', $screen_h)
            ->where('screen_w', $screen_w)
            ->where('screen_d', $screen_d)
            ->where('projector_brand', $projector_brand)
            ->where('projector_model', $projector_model)
            ->where('projector_ip_lan', $projector_ip_lan)
            ->where('lens_model', $lens_model)
            ->where('installed', $installed)
            ->where('server_brand', $server_brand)
            ->where('server_model', $server_model)
            ->where('server_ip_lan', $server_ip_lan)
            ->where('ingest_capabilities', $ingest_capabilities)
            ->where('automation_brand', $automation_brand)
            ->where('automation_model', $automation_model)
            ->where('automation_ip_lan', $automation_ip_lan)
            ->where('satelite_or_live', $satelite_or_live)
            ->where('transmission_brand', $transmission_brand)
            ->where('transmission_model', $transmission_model)
            ->where('transmission_ip_lan', $transmission_ip_lan)
            ->where('processor_brand', $processor_brand)
            ->where('processor_model', $processor_model)
            ->where('processor_ip_lan', $processor_ip_lan)
            ->where('audio_type', $audio_type)
            ->where('audio_brand', $audio_brand)
            ->where('audio_model', $audio_model)
            ->where('audio_channel', $audio_channel)
            ->where('audio_frequency', $audio_frequency)
            ->where('location_id', $location->id)
            ->get();
        $this->assertCount(1, $screens);
        $screen = $screens->first();

        $response->assertRedirect(route('screens.index'));
    }
}
