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
        Schema::create('ticket_settings', function (Blueprint $table) {
            $table->id();
            $table->string('business_name')->default('Mi Negocio');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('tax_id')->nullable(); // RIF, NIT, etc.
            $table->string('ticket_header')->nullable();
            $table->string('ticket_footer')->default('¡Gracias por su compra!');
            $table->boolean('show_tax_id')->default(true);
            $table->boolean('show_address')->default(true);
            $table->boolean('show_phone')->default(true);
            $table->boolean('show_email')->default(false);
            $table->string('receipt_prefix')->default('VT');
            $table->integer('receipt_number')->default(1);
            $table->integer('receipt_padding')->default(6); // Para 000001
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_settings');
    }
};
