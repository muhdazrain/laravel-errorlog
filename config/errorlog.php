<?php

return [
    'enabled' => env('ERRORLOG_ENABLED', true),

    'mode' => env('ERRORLOG_MODE', 'remote'), // local | remote | both

    // Identifies this app to the monitoring server (e.g. 'mymanager' project code)
    'project_code' => env('ERRORLOG_PROJECT_CODE'),

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
        'layout' => env('ERRORLOG_DASHBOARD_LAYOUT'), // e.g. 'layouts.app' — null = standalone bundled CSS
        'custom_css' => env('ERRORLOG_DASHBOARD_CSS'), // e.g. '/css/errorlog.css' — injected as <link>
    ],
];
