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
            // Campos de anulación
            $table->timestamp('cancelled_at')->nullable()->after('status');
            $table->foreignId('cancelled_by')->nullable()->after('cancelled_at')->constrained('users')->nullOnDelete();
            $table->text('cancellation_reason')->nullable()->after('cancelled_by');
            
            // Campos de corrección
            $table->foreignId('corrected_sale_id')->nullable()->after('cancellation_reason')
                ->comment('ID de la venta que corrige esta venta')->constrained('sales')->nullOnDelete();
            $table->foreignId('original_sale_id')->nullable()->after('corrected_sale_id')
                ->comment('ID de la venta original si esta es una corrección')->constrained('sales')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['cancelled_by']);
            $table->dropForeign(['corrected_sale_id']);
            $table->dropForeign(['original_sale_id']);
            $table->dropColumn([
                'cancelled_at',
                'cancelled_by',
                'cancellation_reason',
                'corrected_sale_id',
                'original_sale_id',
            ]);
        });
    }
};
