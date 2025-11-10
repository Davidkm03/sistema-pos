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
            $table->decimal('max_discount_cashier', 5, 2)->default(15)->after('resolution_expiry')->comment('Descuento máximo que puede dar un cajero (%)');
            $table->decimal('max_discount_seller', 5, 2)->default(10)->after('max_discount_cashier')->comment('Descuento máximo que puede dar un vendedor (%)');
            $table->decimal('max_discount_admin', 5, 2)->default(100)->after('max_discount_seller')->comment('Descuento máximo que puede dar un admin (%)');
            $table->boolean('require_discount_reason')->default(true)->after('max_discount_admin')->comment('Requerir razón para descuentos');
            $table->decimal('require_reason_from', 5, 2)->default(5)->after('require_discount_reason')->comment('Requerir razón desde este porcentaje');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_settings', function (Blueprint $table) {
            $table->dropColumn(['max_discount_cashier', 'max_discount_seller', 'max_discount_admin', 'require_discount_reason', 'require_reason_from']);
        });
    }
};
