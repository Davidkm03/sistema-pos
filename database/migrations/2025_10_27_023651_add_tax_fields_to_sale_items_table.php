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
        Schema::table('sale_items', function (Blueprint $table) {
            // Verificar campos existentes
            if (!Schema::hasColumn('sale_items', 'unit_price')) {
                $table->decimal('unit_price', 10, 2)->default(0)->after('quantity')->comment('Precio unitario sin IVA');
            }
            
            if (!Schema::hasColumn('sale_items', 'tax_rate')) {
                $table->decimal('tax_rate', 5, 2)->default(0)->after('unit_price')->comment('Tasa de IVA aplicada (%)');
            }
            
            if (!Schema::hasColumn('sale_items', 'tax_amount')) {
                $table->decimal('tax_amount', 10, 2)->default(0)->after('tax_rate')->comment('Monto del IVA');
            }
            
            // El campo subtotal ya existe
            // El campo 'total' debería existir o se puede agregar aquí si no existe
            if (!Schema::hasColumn('sale_items', 'total')) {
                $table->decimal('total', 10, 2)->default(0)->after('tax_amount')->comment('Total con IVA');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropColumn(['unit_price', 'tax_rate', 'tax_amount', 'subtotal']);
        });
    }
};
