<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // `discount` devient le MONTANT de la remise en RM (avant : entier jamais utilisé).
            $table->decimal('discount', 12, 2)->default(0)->change();
            $table->string('discount_type', 10)->nullable()->after('discount');   // percent | fixed
            $table->decimal('discount_value', 12, 2)->nullable()->after('discount_type'); // % ou RM saisi
            $table->string('discount_reason', 500)->nullable()->after('discount_value');
            $table->foreignId('discount_by')->nullable()->after('discount_reason')->constrained('users')->nullOnDelete();
            $table->timestamp('discount_at')->nullable()->after('discount_by');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('discount_by');
            $table->dropColumn(['discount_type', 'discount_value', 'discount_reason', 'discount_at']);
            $table->integer('discount')->default(0)->change();
        });
    }
};
