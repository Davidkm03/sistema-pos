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
        Schema::table('business_settings', function (Blueprint $table) {
            // Agregar columnas de facturación/documentos
            $table->string('billing_type', 50)->default('simple_receipt')->after('business_tax_regime');
            $table->string('receipt_prefix', 10)->default('RV')->after('billing_type');
            $table->integer('receipt_counter')->default(1)->after('receipt_prefix');
            $table->text('receipt_header')->nullable()->after('receipt_counter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_settings', function (Blueprint $table) {
            $table->dropColumn([
                'billing_type',
                'receipt_prefix', 
                'receipt_counter',
                'receipt_header'
            ]);
        });
    }
};
