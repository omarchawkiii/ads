<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Prix (RM) par diffusion ajouté si la campagne cible ce type de salle. NULL = prix par défaut.
        Schema::table('hall_types', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->nullable();
        });
        Schema::table('configs', function (Blueprint $table) {
            $table->decimal('default_hall_type_price', 8, 2)->default(0);
        });
        Schema::table('invoice_lines', function (Blueprint $table) {
            $table->decimal('hall_price', 8, 2)->default(0)->after('slot_price');
            $table->string('hall_types')->nullable()->after('hall_price');
        });
    }

    public function down(): void
    {
        Schema::table('invoice_lines', fn (Blueprint $t) => $t->dropColumn(['hall_price', 'hall_types']));
        Schema::table('configs', fn (Blueprint $t) => $t->dropColumn('default_hall_type_price'));
        Schema::table('hall_types', fn (Blueprint $t) => $t->dropColumn('price'));
    }
};
