<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    /**
     * Handle an incoming request and add security headers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // X-Frame-Options: Previene clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');

        // X-Content-Type-Options: Previene MIME sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-XSS-Protection: Activa protección XSS del navegador
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy: Controla qué información se envía en el header Referer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy: Controla qué features del navegador se permiten
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Content-Security-Policy: Previene XSS y otros ataques de inyección
        // TEMPORAL: CSP más permisivo para chatbot
        $host = $request->getHost();
        if (str_contains($host, 'hateachistopher')) {
            // CSP relajado para chatbot
            $csp = [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
                "style-src 'self' 'unsafe-inline'",
                "font-src 'self' data:",
                "img-src 'self' data: https:",
                "connect-src 'self' https://api.anthropic.com",
                "frame-ancestors 'none'",
            ];
        } else {
            // CSP normal para otros subdominios
            $csp = [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
                "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com",
                "font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com data:",
                "img-src 'self' data: https:",
                "connect-src 'self'",
                "frame-ancestors 'none'",
            ];
        }
        $response->headers->set('Content-Security-Policy', implode('; ', $csp));

        // Strict-Transport-Security: Fuerza HTTPS (solo si estás usando HTTPS en producción)
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
