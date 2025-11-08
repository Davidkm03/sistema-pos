<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevenir MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Prevenir clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');
        
        // Activar protección XSS del navegador
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Forzar HTTPS en producción (comentado para desarrollo local)
        if (config('app.env') === 'production') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }
        
        // Content Security Policy básico
        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://cdnjs.cloudflare.com; img-src 'self' data: https:; font-src 'self' https://fonts.bunny.net; connect-src 'self';");
        
        // Referrer Policy - Controlar información enviada en referrer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Permissions Policy - Deshabilitar features no necesarias
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        return $response;
    }
}
