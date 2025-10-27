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
        Schema::table('products', function (Blueprint $table) {
            // Tipo de IVA del producto
            $table->enum('tax_type', ['exempt', 'excluded', 'standard'])
                ->default('standard')
                ->after('price')
                ->comment('exempt=Exento(0%), excluded=Excluido(0%), standard=IVA normal');
            
            // Tasa personalizada de IVA (para productos con tasas especiales como 5%)
            $table->decimal('tax_rate', 5, 2)
                ->nullable()
                ->after('tax_type')
                ->comment('Tasa personalizada de IVA. NULL = usar tasa general del sistema');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['tax_type', 'tax_rate']);
        });
    }
};
