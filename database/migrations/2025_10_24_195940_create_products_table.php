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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // ID autoincrementable
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Llave foránea con eliminación en cascada
            $table->string('name', 200); // Campo name con máximo 200 caracteres
            $table->string('sku', 50)->unique(); // Campo sku único con máximo 50 caracteres
            $table->decimal('price', 10, 2); // Campo price con 10 dígitos y 2 decimales
            $table->decimal('cost', 10, 2); // Campo cost con 10 dígitos y 2 decimales
            $table->integer('stock')->default(0); // Campo stock con valor por defecto 0
            $table->string('image')->nullable(); // Campo image que puede ser nulo
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
