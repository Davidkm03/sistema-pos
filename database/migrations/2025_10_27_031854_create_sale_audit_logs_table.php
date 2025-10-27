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
        Schema::create('sale_audit_logs', function (Blueprint $table) {
            $table->id();
            
            // Relación con la venta
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            
            // Tipo de acción
            $table->enum('action', ['created', 'cancelled', 'corrected', 'modified'])
                ->comment('Tipo de acción realizada');
            
            // Usuario que realizó la acción
            $table->foreignId('performed_by')->constrained('users')->onDelete('cascade');
            
            // Razón y detalles
            $table->text('reason')->nullable()->comment('Razón de la acción');
            
            // Datos antes y después del cambio (JSON)
            $table->json('old_data')->nullable()->comment('Datos antes del cambio');
            $table->json('new_data')->nullable()->comment('Datos después del cambio');
            
            // Información técnica
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            
            $table->timestamp('created_at');
            
            // Índices para búsqueda rápida
            $table->index('sale_id');
            $table->index('action');
            $table->index('performed_by');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_audit_logs');
    }
};
