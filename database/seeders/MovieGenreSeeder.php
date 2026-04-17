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
            ['name' => 'Biographical',             'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Family',                   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Anime',                    'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Foreign Language / Arthouse', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Religious / Inspirational','created_at' => $now, 'updated_at' => $now],
            ['name' => 'Malay / Local Film',       'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Chinese Film',             'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Indian / Tamil Film',      'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
