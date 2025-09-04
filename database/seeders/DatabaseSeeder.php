<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GenderSeeder::class,
            LangueSeeder::class,
            CompaignCategorySeeder::class,
            CompaignObjectiveSeeder::class,
            BrandSeeder::class,
            LocationSeeder::class,
            HallTypeSeeder::class,
            MovieGenreSeeder::class,
            TargetTypeSeeder::class,
            InterestSeeder::class,
            SlotSeeder::class,
            AdminUserSeeder::class,
            AdvertiserUserSeeder::class,
        ]);
        //\App\Models\Location::factory(3)->create();
       // \App\Models\Screen::factory(3)->create();
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
