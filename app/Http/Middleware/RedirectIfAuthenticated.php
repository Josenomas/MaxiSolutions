<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Redirigir según el guard usado
                if ($guard === 'chatbot') {
                    return redirect()->route('chatbot.dashboard');
                }

                if ($guard === 'paes') {
                    return redirect()->route('paes.dashboard');
                }

                // Por defecto, usar la ruta HOME del dominio principal
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
