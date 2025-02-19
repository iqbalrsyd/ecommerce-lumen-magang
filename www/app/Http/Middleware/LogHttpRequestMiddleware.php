<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogHttpRequest
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        Log::info("HTTP Request", [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status' => $response->getStatusCode(),
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return $response;
    }
}
