<?php

namespace Azrain\LaravelErrorLog;

use Throwable;

class PayloadBuilder
{
    /**
     * @return array<string, mixed>
     */
    public function build(Throwable $e): array
    {
        $isHttp = ! app()->runningInConsole();

        return [
            'fingerprint' => md5(get_class($e).'|'.$e->getFile().'|'.$e->getLine()),
            'exception_class' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => array_map(fn (array $frame): array => [
                'file' => $frame['file'] ?? null,
                'line' => $frame['line'] ?? null,
                'function' => ($frame['class'] ?? '').($frame['type'] ?? '').($frame['function'] ?? ''),
            ], array_slice($e->getTrace(), 0, 50)),
            'url' => $isHttp ? $this->resolveUrl() : null,
            'method' => $isHttp ? request()->method() : null,
            'ip' => $isHttp ? request()->ip() : null,
            'user_agent' => $isHttp ? request()->userAgent() : null,
            'user_id' => $isHttp ? auth()->id() : null,
            'project_code' => config('errorlog.project_code'),
            'app_name' => config('app.name'),
            'environment' => config('app.env'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'occurred_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Livewire sends component updates to a generic endpoint (e.g. /livewire/update),
     * so use the page the user was actually on instead.
     */
    private function resolveUrl(): string
    {
        $request = request();

        if ($request->headers->has('X-Livewire') || $request->is('livewire/*', 'livewire-*/*', '*/livewire/update', '*/livewire-*/update')) {
            if (class_exists(\Livewire\Livewire::class) && method_exists(\Livewire\Livewire::class, 'originalUrl')) {
                $url = \Livewire\Livewire::originalUrl();

                if (is_string($url) && $url !== '') {
                    return $url;
                }
            }

            $referer = $request->headers->get('referer');

            if (is_string($referer) && $referer !== '') {
                return $referer;
            }
        }

        return $request->fullUrl();
    }
}
