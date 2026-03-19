<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('configs')->updateOrInsert(
            ['id' => 1],
            [
                'use_noc'    => 1,
                'link'       => 'http://192.168.200.236',
                'user'       => 'adsmart',
                'password'   => 'adsmart',
                'tax'        => 6.00,
                'created_at' => '2025-09-14 03:20:48',
                'updated_at' => '2025-09-14 03:26:42',
            ]
        );
    }
}
