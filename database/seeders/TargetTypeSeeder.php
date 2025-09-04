<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TargetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        DB::table('target_types')->insert([
            ['name' => 'Age Group', 'detail' => '18–24', 'cpm' => '1','created_at' => $now, 'updated_at' => $now],
            ['name' => 'Age Group', 'detail' => '24–36', 'cpm' => '2', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Age Group', 'detail' => '36-54', 'cpm' => '3','created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
