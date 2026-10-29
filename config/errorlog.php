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

    // Capture POST/JSON request input (non-GET requests) with the error
    'capture_input' => env('ERRORLOG_CAPTURE_INPUT', true),
    'input_max_length' => 1000,
    // Input keys containing any of these (case-insensitive) have their value replaced with ***
    'redact' => [
        'password', 'token', 'secret', 'api_key', 'apikey', 'authorization',
        'card', 'cvv', 'cvc', 'otp', 'ssn',
    ],

    // Local mode dashboard
    'dashboard' => [
        'enabled' => env('ERRORLOG_DASHBOARD', true),
        'path' => env('ERRORLOG_DASHBOARD_PATH', 'errorlog-viewer'),
        'layout' => env('ERRORLOG_DASHBOARD_LAYOUT'), // e.g. 'layouts.app' — null = standalone bundled CSS
        'custom_css' => env('ERRORLOG_DASHBOARD_CSS'), // e.g. '/css/errorlog.css' — injected as <link>
    ],
];
