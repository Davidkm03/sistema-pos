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
        // Agregar campos de facturación a business_settings
        Schema::table('business_settings', function (Blueprint $table) {
            // Solo agregar los campos que no existen
            if (!Schema::hasColumn('business_settings', 'show_tax_disclaimer')) {
                $table->boolean('show_tax_disclaimer')->default(true)->after('receipt_footer');
            }

            // Campos para facturación con DIAN
            if (!Schema::hasColumn('business_settings', 'invoice_prefix')) {
                $table->string('invoice_prefix', 10)->default('FV')->after('show_tax_disclaimer');
            }
            if (!Schema::hasColumn('business_settings', 'dian_resolution')) {
                $table->string('dian_resolution', 50)->nullable()->after('invoice_prefix');
            }
            if (!Schema::hasColumn('business_settings', 'resolution_date')) {
                $table->date('resolution_date')->nullable()->after('dian_resolution');
            }
            if (!Schema::hasColumn('business_settings', 'range_from')) {
                $table->unsignedInteger('range_from')->nullable()->after('resolution_date');
            }
            if (!Schema::hasColumn('business_settings', 'range_to')) {
                $table->unsignedInteger('range_to')->nullable()->after('range_from');
            }
            if (!Schema::hasColumn('business_settings', 'resolution_expiry')) {
                $table->date('resolution_expiry')->nullable()->after('range_to');
            }
        });

        // Agregar campos de documento a sales
        Schema::table('sales', function (Blueprint $table) {
            // Solo agregar invoice_number si no existe
            if (!Schema::hasColumn('sales', 'invoice_number')) {
                $table->unsignedInteger('invoice_number')->nullable()->after('receipt_number');
                $table->index(['document_type', 'invoice_number']);
            }
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
                'receipt_header',
                'receipt_footer',
                'show_tax_disclaimer',
                'invoice_prefix',
                'dian_resolution',
                'resolution_date',
                'range_from',
                'range_to',
                'resolution_expiry',
            ]);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['document_type', 'receipt_number']);
            $table->dropIndex(['document_type', 'invoice_number']);
            $table->dropColumn(['document_type', 'receipt_number', 'invoice_number']);
        });
    }
};
