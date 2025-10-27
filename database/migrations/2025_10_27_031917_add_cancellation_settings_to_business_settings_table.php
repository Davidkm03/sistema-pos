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
            // Configuración de anulaciones
            if (!Schema::hasColumn('business_settings', 'max_cancellation_days')) {
                $table->integer('max_cancellation_days')->default(1)->after('business_tax_regime')
                    ->comment('Días máximo para anular sin ser admin');
            }
            
            if (!Schema::hasColumn('business_settings', 'require_cancellation_approval')) {
                $table->boolean('require_cancellation_approval')->default(false)->after('max_cancellation_days')
                    ->comment('Si se requiere aprobación para anulaciones');
            }
            
            if (!Schema::hasColumn('business_settings', 'cancellation_approval_amount')) {
                $table->decimal('cancellation_approval_amount', 12, 2)->default(100000)->after('require_cancellation_approval')
                    ->comment('Monto desde el cual se requiere aprobación');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_settings', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('business_settings', 'max_cancellation_days')) $columns[] = 'max_cancellation_days';
            if (Schema::hasColumn('business_settings', 'require_cancellation_approval')) $columns[] = 'require_cancellation_approval';
            if (Schema::hasColumn('business_settings', 'cancellation_approval_amount')) $columns[] = 'cancellation_approval_amount';
            
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
