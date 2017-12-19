<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/traktor/store',
        '/plot/store',
        '/work/plot/stor',
        '/work/plot/*/traktor/*/store',
        '/work/plots',
        '/filter/*'
    ];
}
