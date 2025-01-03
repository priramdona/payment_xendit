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
        '/redirects/*',
        '/callbacks/*',
        '/payment-gateways/callback/*',
        'payment-gateways/payment-methods-callback',
        'payment-gateways/payment-methods-succeeded',
        'payment-gateways/create-va-callback',
        'payment-gateways/va-paid',
        'payment-gateways/invoice-paid',
        'payment-gateways/paylater-paid',
        'payment-gateways/disbursements',
    ];
}
