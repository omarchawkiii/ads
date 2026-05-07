<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add a temporary ENUM column
        Schema::table('dcp_creatives', function (Blueprint $table) {
            $table->enum('status_new', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->after('status');
        });

        // 2. Populate the new column from the old INT values
        DB::statement("
            UPDATE dcp_creatives SET status_new = CASE
                WHEN status = 2 THEN 'approved'
                WHEN status = 3 THEN 'rejected'
                ELSE 'pending'
            END
        ");

        // 3. Drop old INT column, rename new column
        Schema::table('dcp_creatives', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('dcp_creatives', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });
    }

    public function down(): void
    {
        Schema::table('dcp_creatives', function (Blueprint $table) {
            $table->integer('status_old')->default(0)->after('status');
        });

        DB::statement("
            UPDATE dcp_creatives SET status_old = CASE
                WHEN status = 'approved' THEN 2
                WHEN status = 'rejected' THEN 3
                ELSE 1
            END
        ");

        Schema::table('dcp_creatives', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('dcp_creatives', function (Blueprint $table) {
            $table->renameColumn('status_old', 'status');
        });
    }
};
