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
        Schema::table('customers', function (Blueprint $table) {
            // Tipo de documento
            $table->enum('tax_id_type', ['CC', 'NIT', 'CE', 'Pasaporte'])
                ->default('CC')
                ->after('phone')
                ->comment('CC=Cédula, NIT=NIT, CE=Cédula Extranjería, Pasaporte');
            
            // Número de documento o NIT
            $table->string('tax_id')
                ->nullable()
                ->after('tax_id_type')
                ->comment('Número de documento o NIT');
            
            // Régimen tributario
            $table->enum('tax_regime', ['simplified', 'common'])
                ->default('simplified')
                ->after('tax_id')
                ->comment('simplified=Régimen Simplificado, common=Régimen Común');
            
            // Si es agente de retención
            $table->boolean('is_retention_agent')
                ->default(false)
                ->after('tax_regime')
                ->comment('Si es agente de retención en la fuente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['tax_id_type', 'tax_id', 'tax_regime', 'is_retention_agent']);
        });
    }
};
