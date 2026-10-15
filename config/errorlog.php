<?php

return [
    'enabled' => env('ERRORLOG_ENABLED', true),

    'mode' => env('ERRORLOG_MODE', 'local'), // local | remote | both

    // Remote mode only (also used by 'both')
    'endpoint' => env('ERRORLOG_ENDPOINT'),
    'api_key' => env('ERRORLOG_API_KEY'),
    'timeout' => env('ERRORLOG_TIMEOUT', 3),

    // Exception classes that are never reported
    'ignore' => [
        Illuminate\Validation\ValidationException::class,
        Illuminate\Auth\AuthenticationException::class,
        Illuminate\Auth\Access\AuthorizationException::class,
        Illuminate\Session\TokenMismatchException::class,
        Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
    ],

    // Local mode dashboard
    'dashboard' => [
        'enabled' => env('ERRORLOG_DASHBOARD', true),
        'path' => env('ERRORLOG_DASHBOARD_PATH', 'errorlog-viewer'),
        // 'errorlog.auth' aborts (default 404) for guests instead of redirecting
        // to a login route. Append a guard: 'errorlog.auth:admin'.
        'middleware' => ['web', 'errorlog.auth'],
        'guard' => env('ERRORLOG_DASHBOARD_GUARD'), // e.g. 'admin' — null = default guard
        'unauthorized_status' => (int) env('ERRORLOG_DASHBOARD_UNAUTHORIZED', 404), // 404 | 403 | 401
        'layout' => env('ERRORLOG_DASHBOARD_LAYOUT'), // e.g. 'layouts.app' — null = standalone bundled CSS
        'custom_css' => env('ERRORLOG_DASHBOARD_CSS'), // e.g. '/css/errorlog.css' — injected as <link>
    ],
];
