<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change number and status from integer to string
        DB::statement('ALTER TABLE invoices MODIFY number VARCHAR(50) NULL');
        DB::statement('ALTER TABLE invoices MODIFY status VARCHAR(20) NULL');
        DB::statement('ALTER TABLE invoices MODIFY tax DECIMAL(5,2) NULL DEFAULT 6.00');

        // Fix existing rows that have integer 0 / 1 stored as status
        DB::table('invoices')->where('status', '0')->update(['status' => 'unpaid']);
        DB::table('invoices')->where('status', '1')->update(['status' => 'paid']);
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE invoices MODIFY number INT NULL');
        DB::statement('ALTER TABLE invoices MODIFY status INT NULL');
        DB::statement('ALTER TABLE invoices MODIFY tax INT NULL');
    }
};
