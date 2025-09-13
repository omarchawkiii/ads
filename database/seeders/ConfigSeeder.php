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
        $now = Carbon::now();
        DB::table('configs')->insert([
            ['use_noc' => 0,'link' => 'http://192.168.1.1','user' => 'user','password' => 'password',    'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
