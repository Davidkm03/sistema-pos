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
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // ID autoincrementable
            $table->string('name', 150); // Campo name con máximo 150 caracteres
            $table->string('phone', 20)->nullable(); // Campo phone con máximo 20 caracteres (puede ser nulo)
            $table->string('email')->nullable(); // Campo email que puede ser nulo
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
