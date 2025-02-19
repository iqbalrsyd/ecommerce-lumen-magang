<?php
// app/Http/Kernel.php

namespace App\Http;

use Laravel\Lumen\Http\Middleware\Authenticate;
use Closure;
use Illuminate\Http\Request;

class Kernel extends \Illuminate\Foundation\Http\Kernel
{
    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
    ];
}
