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
        Schema::create('sales', function (Blueprint $table) {
            $table->id(); // ID autoincrementable
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Llave foránea con eliminación en cascada
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null'); // Llave foránea que puede ser nula con set null
            $table->decimal('total', 10, 2); // Campo total con 10 dígitos y 2 decimales
            $table->enum('payment_method', ['efectivo', 'tarjeta']); // Enum para método de pago
            $table->enum('status', ['pendiente', 'completada', 'cancelada'])->default('completada'); // Enum para status con valor por defecto
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
