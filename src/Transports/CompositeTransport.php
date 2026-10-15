<?php

namespace Azrain\LaravelErrorLog\Transports;

use Throwable;

class CompositeTransport implements TransportInterface
{
    /**
     * @param  TransportInterface[]  $transports
     */
    public function __construct(
        protected array $transports,
    ) {}

    public function send(array $payload): void
    {
        foreach ($this->transports as $transport) {
            try {
                $transport->send($payload);
            } catch (Throwable) {
                // One transport failing must not stop the others.
            }
        }
    }
}
