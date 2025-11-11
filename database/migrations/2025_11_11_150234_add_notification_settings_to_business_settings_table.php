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
            $table->boolean('enable_stock_notifications')->default(true);
            $table->boolean('enable_sale_notifications')->default(true);
            $table->boolean('enable_large_sale_notifications')->default(true);
            $table->boolean('enable_system_error_notifications')->default(true);
            $table->boolean('enable_quote_notifications')->default(true);
            $table->boolean('enable_goal_notifications')->default(true);
            $table->integer('large_sale_threshold')->default(100000); // Umbral para ventas grandes
            $table->boolean('enable_email_notifications')->default(false); // Para futuras integraciones
            $table->boolean('enable_push_notifications')->default(true); // Notificaciones en navegador
        });
    }

    public function down(): void
    {
        Schema::table('business_settings', function (Blueprint $table) {
            $table->dropColumn([
                'enable_stock_notifications',
                'enable_sale_notifications',
                'enable_large_sale_notifications',
                'enable_system_error_notifications',
                'enable_quote_notifications',
                'enable_goal_notifications',
                'large_sale_threshold',
                'enable_email_notifications',
                'enable_push_notifications',
            ]);
        });
    }
};
