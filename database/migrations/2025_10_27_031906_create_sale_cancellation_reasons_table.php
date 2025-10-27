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
        Schema::create('sale_cancellation_reasons', function (Blueprint $table) {
            $table->id();
            
            // Razón de anulación
            $table->string('reason')->unique();
            
            // Si requiere aprobación de administrador
            $table->boolean('requires_admin_approval')->default(false);
            
            // Si está activa (para desactivar sin eliminar)
            $table->boolean('active')->default(true);
            
            // Orden para mostrar en el select
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_cancellation_reasons');
    }
};
