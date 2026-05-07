<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dcp_creatives', function (Blueprint $table) {
            if (!Schema::hasColumn('dcp_creatives', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('customer_id');
            }
            $table->text('approval_note')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('dcp_creatives', function (Blueprint $table) {
            $table->dropColumn(['status', 'approval_note']);
        });
    }
};
