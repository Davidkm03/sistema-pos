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
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            
            // Relación con la venta
            $table->foreignId('sale_id')
                ->constrained('sales')
                ->onDelete('cascade');
            
            // Método de pago
            $table->enum('payment_method', ['efectivo', 'tarjeta_debito', 'tarjeta_credito', 'transferencia'])
                ->comment('Método de pago utilizado');
            
            // Detalles para transferencias
            $table->enum('transfer_type', ['nequi', 'daviplata', 'bancolombia', 'llave', 'otro'])
                ->nullable()
                ->comment('Tipo de transferencia si payment_method=transferencia');
            
            $table->string('transfer_reference')
                ->nullable()
                ->comment('Número de referencia o aprobación de la transferencia');
            
            // Monto pagado con este método
            $table->decimal('amount', 12, 2)
                ->comment('Monto pagado con este método');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_details');
    }
};
