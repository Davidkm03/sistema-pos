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
            // Agregar document_type si no existe
            if (!Schema::hasColumn('sales', 'document_type')) {
                $table->enum('document_type', ['receipt', 'invoice'])
                      ->default('receipt')
                      ->after('status')
                      ->comment('Tipo de documento: receipt (tiquete) o invoice (factura)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'document_type')) {
                $table->dropColumn('document_type');
            }
        });
    }
};
