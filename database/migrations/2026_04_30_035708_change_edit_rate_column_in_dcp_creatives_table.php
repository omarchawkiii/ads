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
            $table->string('edit_rate', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('dcp_creatives', function (Blueprint $table) {
            $table->decimal('edit_rate', 8, 4)->nullable()->change();
        });
    }
};
