<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieGenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        DB::table('movie_genres')->insert([
            ['name' => 'Action',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Romance', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Horror',  'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
