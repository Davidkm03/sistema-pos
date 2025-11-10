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
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('tip_amount')->comment('Porcentaje de descuento aplicado');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('discount_percentage')->comment('Monto del descuento');
            $table->string('discount_reason', 255)->nullable()->after('discount_amount')->comment('Razón del descuento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['discount_percentage', 'discount_amount', 'discount_reason']);
        });
    }
};
