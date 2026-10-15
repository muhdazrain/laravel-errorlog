<?php

namespace Azrain\LaravelErrorLog\Transports;

use GuzzleHttp\Client;
use Throwable;

class HttpTransport implements TransportInterface
{
    public function __construct(
        protected ?string $endpoint,
        protected ?string $apiKey,
        protected int $timeout = 3,
    ) {}

    public function send(array $payload): void
    {
        if (! $this->endpoint) {
            return;
        }

        try {
            (new Client([
                'timeout' => $this->timeout,
                'connect_timeout' => 2,
            ]))->post($this->endpoint, [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-ErrorLog-Key' => (string) $this->apiKey,
                ],
                'json' => $payload,
                'http_errors' => false,
            ]);
        } catch (Throwable) {
            // Never let reporting break the host app.
        }
    }
}
