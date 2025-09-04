<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdvertiserUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'advertiser@gmail.com'], // <-- sans espace
            [
                'username' => 'advertiser',
                'name'     => 'advertiser',
                'role'     => 2,
                'password' => Hash::make('advertiser'),
            ]
        );
    }
}
