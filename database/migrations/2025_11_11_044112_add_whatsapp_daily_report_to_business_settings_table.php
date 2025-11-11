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
            $table->boolean('whatsapp_daily_report_enabled')->default(false);
            $table->time('whatsapp_report_time')->default('19:00:00');
            $table->string('owner_whatsapp', 20)->nullable();
            $table->boolean('whatsapp_report_include_combos')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_settings', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp_daily_report_enabled',
                'whatsapp_report_time',
                'owner_whatsapp',
                'whatsapp_report_include_combos'
            ]);
        });
    }
};
