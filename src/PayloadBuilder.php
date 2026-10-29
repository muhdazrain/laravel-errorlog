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
            'input' => $isHttp ? $this->resolveInput() : null,
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
     * Request input (POST/JSON body, query string excluded) with sensitive values redacted.
     *
     * @return array<string, mixed>|null
     */
    private function resolveInput(): ?array
    {
        $request = request();

        if (! config('errorlog.capture_input', true) || $request->isMethodSafe()) {
            return null;
        }

        $input = $request->request->all() + $request->json()->all();

        if ($request->headers->has('X-Livewire')) {
            $input = ['livewire' => collect($input['components'] ?? [])->map(fn ($c): array => [
                'calls' => $c['calls'] ?? [],
                'updates' => $c['updates'] ?? [],
            ])->all()];
        }

        $input = $this->sanitize($input);

        return $input === [] ? null : $input;
    }

    /**
     * @param  array<array-key, mixed>  $data
     * @return array<array-key, mixed>
     */
    private function sanitize(array $data, int $depth = 0): array
    {
        $redact = array_map('strtolower', config('errorlog.redact', []));
        $max = (int) config('errorlog.input_max_length', 1000);
        $clean = [];

        foreach (array_slice($data, 0, 100, true) as $key => $value) {
            $lower = strtolower((string) $key);

            if (array_filter($redact, fn (string $r): bool => $r !== '' && str_contains($lower, $r))) {
                $clean[$key] = '***';
            } elseif (is_array($value)) {
                $clean[$key] = $depth >= 5 ? '[max depth]' : $this->sanitize($value, $depth + 1);
            } elseif ($value instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
                $clean[$key] = '[file: '.$value->getClientOriginalName().']';
            } elseif (is_string($value) && mb_strlen($value) > $max) {
                $clean[$key] = mb_substr($value, 0, $max).'… [truncated]';
            } elseif (is_scalar($value) || $value === null) {
                $clean[$key] = $value;
            } else {
                $clean[$key] = '['.get_debug_type($value).']';
            }
        }

        return $clean;
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
