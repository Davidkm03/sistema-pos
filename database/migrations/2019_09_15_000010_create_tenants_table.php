<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary();
            
            // Información del negocio/empresa
            $table->string('name'); // Nombre del negocio
            $table->string('email')->nullable(); // Email del negocio
            $table->string('phone')->nullable(); // Teléfono del negocio
            $table->string('address')->nullable(); // Dirección del negocio
            
            // Plan y estado
            $table->string('plan')->default('free'); // free, basic, premium, enterprise
            $table->boolean('is_active')->default(true); // Activo/Inactivo
            $table->timestamp('trial_ends_at')->nullable(); // Fin del periodo de prueba
            
            $table->timestamps();
            $table->json('data')->nullable(); // Datos adicionales personalizados
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
