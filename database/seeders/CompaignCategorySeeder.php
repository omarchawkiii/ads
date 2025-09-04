<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompaignCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        DB::table('compaign_categories')->insert([
            ['name' => 'Technology',       'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Food & Beverage',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Travel',           'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Retail',           'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Finance',          'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
