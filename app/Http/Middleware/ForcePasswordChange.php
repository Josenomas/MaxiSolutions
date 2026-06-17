<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->debe_cambiar_password) {
            if (!$request->is('cambiar-password') && !$request->is('logout')) {
                return redirect()->route('cambiar-password')
                    ->with('warning', 'Debes cambiar tu contraseña temporal antes de continuar.');
            }
        }

        return $next($request);
    }
}
