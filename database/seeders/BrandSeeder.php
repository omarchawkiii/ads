<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        DB::table('brands')->insert([
            ['name' => 'Samsung', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'KFC',     'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Apple',   'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
