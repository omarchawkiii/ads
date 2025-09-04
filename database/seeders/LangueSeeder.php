<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Langue;

class LangueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect(['English', 'Bahasa Malaysia', 'Mandarin', 'Tamil'])
            ->each(fn ($name) => Langue::firstOrCreate(['name' => $name]));
    }
}
