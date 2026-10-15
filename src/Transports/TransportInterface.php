<?php

namespace Azrain\LaravelErrorLog\Transports;

interface TransportInterface
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function send(array $payload): void;
}
