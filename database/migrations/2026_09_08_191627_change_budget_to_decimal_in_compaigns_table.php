<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Raw SQL is used here (rather than Schema::table(...)->change()) so this doesn't
     * require the doctrine/dbal package, which isn't installed in this project.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE `compaigns` MODIFY `budget` DECIMAL(12,2) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE `compaigns` MODIFY `budget` INT NULL');
    }
};
