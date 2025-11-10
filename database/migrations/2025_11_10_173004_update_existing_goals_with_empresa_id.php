<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Actualizar metas existentes que no tienen empresa_id asignado
        // Asignarles el empresa_id del usuario que las creó
        DB::statement('
            UPDATE goals 
            SET empresa_id = (
                SELECT empresa_id 
                FROM users 
                WHERE users.id = goals.user_id
                LIMIT 1
            )
            WHERE empresa_id IS NULL
        ');

        // Si aún quedan metas sin empresa_id (por usuarios sin empresa_id),
        // asignarles la primera empresa disponible o eliminarlas
        $firstEmpresaId = DB::table('empresas')->first()->id ?? null;
        
        if ($firstEmpresaId) {
            DB::table('goals')
                ->whereNull('empresa_id')
                ->update(['empresa_id' => $firstEmpresaId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No es necesario revertir esta migración de datos
        // Los empresa_id se mantienen
    }
};
