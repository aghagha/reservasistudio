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
        'u/register',
        'u/login',
        'u/view',
        'u/edit',
        'j/kotastudio',
        'j/jadwal',
        'j/newjadwal',
        'r/store',
        'r/edit',
        'r/cancel',
        'r/issue',
        'r/history',
        'r/kontak',
        'web/authenticate'
    ];
}
