<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Detectar si estamos en subdominio chatbot
            if ($request->getHost() === 'hateachistopher.maxisolutions.cl') {
                return route('chatbot.login');
            }

            // Detectar si estamos en subdominio paes
            if ($request->getHost() === 'paes.maxisolutions.cl') {
                return route('paes.login');
            }

            return route('login');
        }
    }
}
