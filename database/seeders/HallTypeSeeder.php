<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HallTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        DB::table('hall_types')->insert([
            ['name' => 'Standard',    'created_at' => $now, 'updated_at' => $now],
            ['name' => 'IMAX',        'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Dolby Atmos', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
