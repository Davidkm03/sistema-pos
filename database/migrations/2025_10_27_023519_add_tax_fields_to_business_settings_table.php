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
            // Verificar si ya existe tax_enabled antes de agregar
            if (!Schema::hasColumn('business_settings', 'tax_enabled')) {
                $table->boolean('tax_enabled')->default(false)->after('id');
            }
            if (!Schema::hasColumn('business_settings', 'tax_name')) {
                $table->string('tax_name')->default('IVA')->after('tax_enabled');
            }
            if (!Schema::hasColumn('business_settings', 'tax_rate')) {
                $table->decimal('tax_rate', 5, 2)->default(19.00)->after('tax_name')->comment('Tasa general de IVA en porcentaje');
            }
            if (!Schema::hasColumn('business_settings', 'tax_included_in_price')) {
                $table->boolean('tax_included_in_price')->default(false)->after('tax_rate')->comment('Si TRUE, los precios ya incluyen IVA');
            }
            
            // Configuración de retenciones
            if (!Schema::hasColumn('business_settings', 'retention_enabled')) {
                $table->boolean('retention_enabled')->default(false)->after('tax_included_in_price');
            }
            if (!Schema::hasColumn('business_settings', 'retention_rate')) {
                $table->decimal('retention_rate', 5, 2)->default(3.5)->after('retention_enabled')->comment('Tasa de retención en la fuente');
            }
            if (!Schema::hasColumn('business_settings', 'applies_retention_from')) {
                $table->decimal('applies_retention_from', 12, 2)->default(800000)->after('retention_rate')->comment('Monto desde el cual se aplica retención');
            }
            
            // Requerimientos
            if (!Schema::hasColumn('business_settings', 'tax_id_required')) {
                $table->boolean('tax_id_required')->default(false)->after('applies_retention_from')->comment('Si se requiere NIT del cliente para vender');
            }
            
            // Régimen tributario del negocio (business_tax_id ya existe)
            if (!Schema::hasColumn('business_settings', 'business_tax_regime')) {
                $table->enum('business_tax_regime', ['simplified', 'common'])->default('simplified')->after('business_tax_id');
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
                'business_tax_regime',
            ]);
        });
    }
};
