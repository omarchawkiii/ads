<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Prix (RM) par diffusion. NULL = utiliser le prix par défaut de la config.
        Schema::table('locations', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->nullable()->after('cpm');
        });
        Schema::table('slots', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->nullable()->after('cpm');
        });
        Schema::table('configs', function (Blueprint $table) {
            $table->decimal('default_location_price', 8, 2)->default(0);
            $table->decimal('default_slot_price', 8, 2)->default(0);
        });

        // Détail figé de chaque facture : diffusions × (prix location + prix slot).
        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->string('dcp_name');
            $table->string('slot_name')->nullable();
            $table->string('cinema_chain')->nullable();
            $table->string('location_name');
            $table->unsignedInteger('played');
            $table->decimal('location_price', 8, 2);
            $table->decimal('slot_price', 8, 2);
            $table->decimal('amount', 12, 2);
            $table->timestamps();
        });

        // 8,2 plafonne à 999 999,99 : trop juste pour une facturation à la diffusion.
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('total_ttc', 12, 2)->nullable()->change();
            $table->decimal('total_ht', 12, 2)->nullable()->change();
            $table->decimal('total_tax', 12, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_lines');
        Schema::table('configs', function (Blueprint $table) {
            $table->dropColumn(['default_location_price', 'default_slot_price']);
        });
        Schema::table('slots', fn (Blueprint $t) => $t->dropColumn('price'));
        Schema::table('locations', fn (Blueprint $t) => $t->dropColumn('price'));
    }
};
