<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Compaign;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Creates campaigns WITHOUT send_to_noc, so nothing leaves the app.
 * Run against a dedicated DB: DB_DATABASE=adsmart_campaign_test
 */
final class CompaignStoreTest extends TestCase
{
    use RefreshDatabase;

    private array $ref;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $now = now();
        $id = fn (string $t, array $r) => DB::table($t)->insertGetId($r + ['created_at' => $now, 'updated_at' => $now]);

        $this->ref = [
            'user'     => User::factory()->create(['role' => 2]),
            'langue'   => $id('langues', ['name' => 'EN']),
            'gender'   => $id('genders', ['name' => 'All']),
            'template' => $id('template_slots', ['name' => 'T1']),
            'slot'     => $id('slots', ['name' => 'S1', 'cpm' => 10]),
            'location' => $id('locations', ['name' => 'L1', 'cpm' => 10]),
            'dcp1'     => $id('dcp_creatives', ['name' => 'a', 'duration' => 15]),
            'dcp2'     => $id('dcp_creatives', ['name' => 'b', 'duration' => 15]),
        ];
    }

    private function payload(array $over = []): array
    {
        return $over + [
            'compaign_name'    => 'Test',
            'template_slot_id' => $this->ref['template'],
            'start_date'       => '2026-11-01',
            'end_date'         => '2026-11-10',
            'location_id'      => [$this->ref['location']],
            'langue'           => $this->ref['langue'],
            'gender'           => $this->ref['gender'],
            'budget'           => '100.00',
            'slots'            => [[
                'slot_id' => $this->ref['slot'],
                'dcps'    => [['dcp_id' => $this->ref['dcp1'], 'position' => 1]],
            ]],
        ];
    }

    private function create(array $over = [])
    {
        return $this->actingAs($this->ref['user'])
            ->postJson(route('advertiser.compaigns.store', [], false), $this->payload($over));
    }

    #[Test]
    public function creates_campaign_without_noc(): void
    {
        $r = $this->create();

        $r->assertStatus(201)->assertJson(['noc_results' => []]);
        $c = Compaign::firstOrFail();
        $this->assertFalse((bool) $c->noc_sent);
        Storage::disk('public')->assertExists("campaigns/campaign_{$c->id}.xml");
        $this->assertDatabaseHas('compaign_slot_dcp', ['compaign_id' => $c->id, 'position' => 1]);
    }

    #[Test]
    public function non_overlapping_dates_same_position_is_fine(): void
    {
        $this->create()->assertStatus(201);
        $this->create(['start_date' => '2026-12-01', 'end_date' => '2026-12-10'])->assertStatus(201);
        $this->assertSame(2, Compaign::count());
    }

    #[Test]
    public function overlapping_dates_same_slot_location_position_is_rejected(): void
    {
        $this->create()->assertStatus(201);

        // same dates, slot, location, position -> should be refused
        $this->create(['compaign_name' => 'Clash'])->assertStatus(422);
        $this->assertSame(1, Compaign::count());
    }

    #[Test]
    public function overlapping_dates_different_position_is_fine(): void
    {
        $this->create()->assertStatus(201);

        $this->create(['slots' => [[
            'slot_id' => $this->ref['slot'],
            'dcps'    => [['dcp_id' => $this->ref['dcp2'], 'position' => 2]],
        ]]])->assertStatus(201);
    }

    #[Test]
    public function touching_dates_count_as_overlap(): void
    {
        $this->create()->assertStatus(201); // 2026-11-01 -> 2026-11-10

        $this->create(['start_date' => '2026-11-10', 'end_date' => '2026-11-20'])->assertStatus(422);
    }

    #[Test]
    public function rejected_campaign_does_not_block_position(): void
    {
        $this->create()->assertStatus(201);
        Compaign::query()->update(['status' => 4]);

        $this->create(['compaign_name' => 'Again'])->assertStatus(201);
    }

    private function update(int $id, array $over = [])
    {
        return $this->actingAs($this->ref['user'])
            ->putJson(route('advertiser.compaigns.update', ['id' => $id], false), $this->payload($over));
    }

    #[Test]
    public function update_can_resave_itself_but_not_steal_a_position(): void
    {
        $this->create(['start_date' => '2026-11-01', 'end_date' => '2026-11-10'])->assertStatus(201);
        $this->create([
            'start_date' => '2026-11-01', 'end_date' => '2026-11-10',
            'slots' => [['slot_id' => $this->ref['slot'], 'dcps' => [['dcp_id' => $this->ref['dcp2'], 'position' => 2]]]],
        ])->assertStatus(201);

        [$a, $b] = Compaign::orderBy('id')->pluck('id')->all();

        // A resaved unchanged: ok
        $this->update($a)->assertSuccessful();

        // B tries to take position 1 (held by A): refused
        $this->update($b, ['slots' => [['slot_id' => $this->ref['slot'], 'dcps' => [['dcp_id' => $this->ref['dcp2'], 'position' => 1]]]]])
            ->assertStatus(422);
    }
}
