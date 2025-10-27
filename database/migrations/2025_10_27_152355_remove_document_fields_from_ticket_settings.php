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
        Schema::table('ticket_settings', function (Blueprint $table) {
            // Eliminar campos de numeración que ahora están en business_settings
            $table->dropColumn(['receipt_prefix', 'receipt_number', 'receipt_padding']);

            // También eliminar campos de negocio que ya están en business_settings
            $table->dropColumn(['business_name', 'address', 'phone', 'email', 'tax_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_settings', function (Blueprint $table) {
            $table->string('receipt_prefix', 10)->default('VT');
            $table->integer('receipt_number')->default(1);
            $table->integer('receipt_padding')->default(6);
            $table->string('business_name')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('tax_id', 50)->nullable();
        });
    }
};
