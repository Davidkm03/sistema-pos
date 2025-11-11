<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmpresaScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Solo aplicar el scope si hay un usuario autenticado
        if (Auth::check()) {
            $user = Auth::user();
            
            // Si el usuario tiene empresa_id, filtrar por ella
            if ($user->empresa_id) {
                $builder->where($model->getTable() . '.empresa_id', $user->empresa_id);
            } else {
                // CRÍTICO: Usuario autenticado SIN empresa_id
                // Esto es un problema de seguridad - registrar y bloquear
                Log::error('EmpresaScope: Usuario autenticado sin empresa_id', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'model' => get_class($model),
                    'table' => $model->getTable(),
                ]);
                
                // Retornar consulta vacía para evitar leak de datos
                // Usar whereRaw('1 = 0') para forzar resultado vacío
                $builder->whereRaw('1 = 0');
            }
        }
    }
}
