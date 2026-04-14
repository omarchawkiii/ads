<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cinema_chains', function (Blueprint $table) {
            $table->string('contact_name')->nullable()->after('name');
            $table->string('contact_email')->nullable()->after('contact_name');
            $table->string('ip_address')->nullable()->after('contact_email');
            $table->string('username')->nullable()->after('ip_address');
            $table->string('password')->nullable()->after('username');
        });
    }

    public function down(): void
    {
        Schema::table('cinema_chains', function (Blueprint $table) {
            $table->dropColumn(['contact_name', 'contact_email', 'ip_address', 'username', 'password']);
        });
    }
};
