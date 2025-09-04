<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        DB::table('interests')->insert([
            ['name' => 'Sports','created_at' => $now, 'updated_at' => $now],
            ['name' => 'Travel', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Gaming', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
