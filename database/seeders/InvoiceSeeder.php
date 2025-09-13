<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        DB::table('invoices')->insert([

            ['number' => 1,'compaign_id' => 1,'date' => '2025-09-12','due_date' => '2025-10-12','status' => '1','discount' => '0','tax' => 6,'total_ttc' => 4452,'total_ht' => 4200,'created_at' => $now, 'updated_at' => $now],
            ['number' => 2,'compaign_id' => 1,'date' => '2025-09-12','due_date' => '2025-10-12','status' => '1','discount' => '0','tax' => 6,'total_ttc' =>2650 ,'total_ht' => 2500,'created_at' => $now, 'updated_at' => $now],
            ['number' => 3,'compaign_id' => 1,'date' => '2025-09-12','due_date' => '2025-10-12','status' => '1','discount' => '0','tax' => 6,'total_ttc' =>3286,'total_ht' =>3100,'created_at' => $now, 'updated_at' => $now],

        ]);
    }
}
