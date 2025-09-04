<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        DB::table('slots')->insert([
            ['name' => 'Slot A (Pre-Feature)', 'cpm' => '1',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Slot B (After Trailers)', 'cpm' => '1.5', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Slot C (General Pre-roll)', 'cpm' => '2',  'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
