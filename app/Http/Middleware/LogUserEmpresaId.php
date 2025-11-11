<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class LogUserEmpresaId
{
    /**
     * Handle an incoming request.
     *
     * Log empresa_id del usuario autenticado en cada request
     * para debug de multi-tenancy
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Solo loguear en rutas importantes (ventas, reportes, etc)
            if ($this->shouldLog($request)) {
                Log::channel('daily')->info('User Access', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'empresa_id' => $user->empresa_id,
                    'url' => $request->url(),
                    'method' => $request->method(),
                    'ip' => $request->ip(),
                ]);
            }
        }
        
        return $next($request);
    }
    
    /**
     * Determinar si debemos loguear este request
     */
    private function shouldLog(Request $request): bool
    {
        $routeName = $request->route()?->getName();
        
        // Loguear solo rutas de datos sensibles
        $sensitiveRoutes = [
            'sales',
            'pos',
            'reports',
            'livewire',
        ];
        
        foreach ($sensitiveRoutes as $route) {
            if (str_contains($request->path(), $route)) {
                return true;
            }
        }
        
        return false;
    }
}
