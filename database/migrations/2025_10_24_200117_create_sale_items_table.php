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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id(); // ID autoincrementable
            $table->foreignId('sale_id')->constrained()->onDelete('cascade'); // Llave foránea con eliminación en cascada
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Llave foránea con eliminación en cascada
            $table->integer('quantity'); // Campo quantity de tipo integer
            $table->decimal('price', 10, 2); // Campo price con 10 dígitos y 2 decimales (precio al momento de la venta)
            $table->decimal('subtotal', 10, 2); // Campo subtotal con 10 dígitos y 2 decimales
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
