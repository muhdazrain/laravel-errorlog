<?php

namespace Azrain\LaravelErrorLog;

use Azrain\LaravelErrorLog\Transports\TransportInterface;
use Throwable;

class ErrorLog
{
    public function report(Throwable $e): void
    {
        try {
            if ($this->shouldIgnore($e)) {
                return;
            }

            $payload = app(PayloadBuilder::class)->build($e);

            app(TransportInterface::class)->send($payload);
        } catch (Throwable) {
            // Monitoring must never break the host app.
        }
    }

    protected function shouldIgnore(Throwable $e): bool
    {
        foreach (config('errorlog.ignore', []) as $class) {
            if ($e instanceof $class) {
                return true;
            }
        }

        return false;
    }
}
