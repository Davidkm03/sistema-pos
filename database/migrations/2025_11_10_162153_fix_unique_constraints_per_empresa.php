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
        // 1. Eliminar índice único de SKU en products
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['sku']);
        });
        
        // Crear índice único compuesto (empresa_id, sku)
        Schema::table('products', function (Blueprint $table) {
            $table->unique(['empresa_id', 'sku'], 'products_empresa_sku_unique');
        });

        // 2. Eliminar índice único de quote_number en quotes
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropUnique(['quote_number']);
        });
        
        // Crear índice único compuesto (empresa_id, quote_number)
        Schema::table('quotes', function (Blueprint $table) {
            $table->unique(['empresa_id', 'quote_number'], 'quotes_empresa_quote_number_unique');
        });

        // 3. Crear índice único compuesto para categories (empresa_id, name)
        Schema::table('categories', function (Blueprint $table) {
            $table->unique(['empresa_id', 'name'], 'categories_empresa_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir cambios en products
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('products_empresa_sku_unique');
        });
        
        Schema::table('products', function (Blueprint $table) {
            $table->unique('sku');
        });

        // Revertir cambios en quotes
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropUnique('quotes_empresa_quote_number_unique');
        });
        
        Schema::table('quotes', function (Blueprint $table) {
            $table->unique('quote_number');
        });

        // Revertir cambios en categories
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique('categories_empresa_name_unique');
        });
    }
};
