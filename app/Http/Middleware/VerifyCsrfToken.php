<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/pago/webpay/return',
        '/pago/flow/confirm',
        '/pago/flow/return',
        '/flow-test',
        'pago/flow/*',  // Wildcard para todas las rutas de Flow
    ];
}
