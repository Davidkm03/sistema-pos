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
        Schema::table('sales', function (Blueprint $table) {
            // Desglose de montos (verificar si ya existen)
            if (!Schema::hasColumn('sales', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0)->after('user_id')->comment('Subtotal sin IVA');
            }
            if (!Schema::hasColumn('sales', 'tax_amount')) {
                $table->decimal('tax_amount', 12, 2)->default(0)->after('subtotal')->comment('Monto total del IVA');
            }
            if (!Schema::hasColumn('sales', 'retention_amount')) {
                $table->decimal('retention_amount', 12, 2)->default(0)->after('tax_amount')->comment('Monto de retención en la fuente');
            }
            
            // Modificar el enum de payment_method si existe
            // SQLite no soporta ALTER COLUMN, así que esto solo aplicará si el campo no existe
            if (!Schema::hasColumn('sales', 'payment_method')) {
                $table->enum('payment_method', ['efectivo', 'tarjeta_debito', 'tarjeta_credito', 'transferencia'])
                    ->default('efectivo')
                    ->after('retention_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Solo eliminar los campos nuevos
            $columns = [];
            if (Schema::hasColumn('sales', 'subtotal')) $columns[] = 'subtotal';
            if (Schema::hasColumn('sales', 'tax_amount')) $columns[] = 'tax_amount';
            if (Schema::hasColumn('sales', 'retention_amount')) $columns[] = 'retention_amount';
            
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
