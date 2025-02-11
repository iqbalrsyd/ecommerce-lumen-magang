<?php
namespace App\Http\Middleware;

use Closure;
use Throwable;
use App\Services\ElasticsearchService;
use Illuminate\Http\Request;

class LogApiMiddleware {
    protected $elastic;

    public function __construct(ElasticsearchService $elastic) {
        $this->elastic = $elastic;
    }

    public function handle(Request $request, Closure $next) {
        $start = microtime(true);

        try {
            $response = $next($request);
            $status = $response->status();
            $error = null;
        } catch (Throwable $e) {
            $response = response()->json([
                'message' => $e->getMessage(),
                'error' => $e->getTraceAsString(),
            ], 500);

            $status = 500;
            $error = $e->getMessage();
        }

        $latency = microtime(true) - $start;

        $logData = [
            'timestamp' => now()->toDateTimeString(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status' => $status,
            'latency' => number_format($latency, 6),
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'payload' => $request->all(),
            'error' => $error,
        ];

        $this->elastic->logRequest($logData);

        return $response;
    }
}
