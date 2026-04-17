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
            ['name' => 'Food & Beverage',              'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Fast Food / QSR',              'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Groceries & Supermarket',      'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Health & Wellness',            'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pharmaceuticals & Medical',    'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Insurance & Financial Services','created_at' => $now, 'updated_at' => $now],
            ['name' => 'Banking, Investment & Fintech','created_at' => $now, 'updated_at' => $now],
            ['name' => 'Automotive',                   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Automotive Services',          'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Real Estate & Property',       'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Home & Living',                'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Telecommunications',           'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Fashion & Apparel',            'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Beauty & Personal Care',       'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Luxury Goods & Watches',       'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Electronics & Gadgets',        'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Gaming',                       'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Retail & E-Commerce',          'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Travel & Tourism',             'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Family & Parenting',           'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Dating & Social Apps',         'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Entertainment & Events',       'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Movie & Film Trailer',         'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Education & Training',         'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Career & Recruitment',         'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sports & Fitness',             'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Outdoor & Adventure',          'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Spiritual & Religious',        'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Government & Public Service',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Non-Profit & NGO',             'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Automotive — EV & Green',      'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Food Delivery & Apps',         'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Other / General',              'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
