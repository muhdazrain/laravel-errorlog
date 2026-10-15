<?php

namespace Azrain\LaravelErrorLog\Transports;

use Azrain\LaravelErrorLog\Models\ErrorLogEvent;

class DatabaseTransport implements TransportInterface
{
    public function send(array $payload): void
    {
        ErrorLogEvent::create([
            'fingerprint' => $payload['fingerprint'],
            'exception_class' => $payload['exception_class'],
            'message' => $payload['message'],
            'file' => $payload['file'],
            'line' => $payload['line'],
            'payload' => $payload,
            'user_id' => $payload['user_id'],
            'environment' => $payload['environment'],
            'occurred_at' => $payload['occurred_at'],
        ]);
    }
}
