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
        Schema::table('goals', function (Blueprint $table) {
            // Agregar empresa_id después de id
            $table->unsignedBigInteger('empresa_id')->after('id')->nullable();
            
            // Agregar foreign key
            $table->foreign('empresa_id')
                  ->references('id')
                  ->on('empresas')
                  ->onDelete('cascade');
            
            // Agregar índice para mejorar performance
            $table->index('empresa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            // Eliminar foreign key primero
            $table->dropForeign(['empresa_id']);
            
            // Eliminar columna
            $table->dropColumn('empresa_id');
        });
    }
};
