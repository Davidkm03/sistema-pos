<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class EnsureUserHasEmpresa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Solo validar si el usuario está autenticado
        if (auth()->check()) {
            $user = auth()->user();
            
            // Si el usuario NO tiene empresa_id asignado
            if (!$user->empresa_id) {
                Log::critical('Usuario sin empresa_id intentando acceder al sistema', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl(),
                ]);
                
                // Si es super-admin, permitir acceso (pueden estar configurando empresas)
                if ($user->hasRole('super-admin')) {
                    Log::info('Super-admin sin empresa_id accediendo (permitido)', [
                        'user_id' => $user->id,
                    ]);
                    return $next($request);
                }
                
                // Para otros usuarios, bloquear acceso
                auth()->logout();
                
                return redirect()->route('login')->with('error', 
                    'Tu cuenta no está asociada a ninguna empresa. Contacta al administrador.'
                );
            }
        }
        
        return $next($request);
    }
}
