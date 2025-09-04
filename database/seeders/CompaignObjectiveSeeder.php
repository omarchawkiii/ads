<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompaignObjectiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        DB::table('compaign_objectives')->insert([
            ['name' => 'Branding',    'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Promo',       'created_at' => $now, 'updated_at' => $now],
            ['name' => 'QR Voucher',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Awareness',   'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
