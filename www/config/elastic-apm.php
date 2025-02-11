<?php

return [
    'app' => [
        'active' => env('ELASTIC_APM_ENABLED', true),
        'appName' => env('ELASTIC_APM_SERVICE_NAME', 'LumenApp'),
        'appVersion' => env('ELASTIC_APM_SERVICE_VERSION', '1.0.0'),
    ],
    'server' => [
        'serverUrl' => env('ELASTIC_APM_SERVER_URL', 'http://localhost:8200'),
        'secretToken' => env('ELASTIC_APM_SECRET_TOKEN', ''),
        'stackTraceLimit' => env('ELASTIC_APM_STACK_TRACE_LIMIT', 0),
    ],
    'transactions' => [
        'querylog' => env('APM_QUERYLOG', true),
        'threshold' => env('APM_THRESHOLD', 200),
    ],
];
