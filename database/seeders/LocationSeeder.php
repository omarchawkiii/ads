<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        DB::table('locations')->insert([
            ['name' => 'TGV KLCC',            'created_at' => $now, 'updated_at' => $now],
            ['name' => 'TGV Sunway Pyramid',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'TGV 1 Utama',         'created_at' => $now, 'updated_at' => $now],
            ['name' => 'TGV Mid Valley',      'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
