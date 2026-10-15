<?php

namespace Azrain\LaravelErrorLog\Console;

use Azrain\LaravelErrorLog\PayloadBuilder;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use RuntimeException;
use Throwable;

class TestCommand extends Command
{
    protected $signature = 'errorlog:test';

    protected $description = 'Send a test error to the configured ErrorLog endpoint and report the result';

    public function handle(PayloadBuilder $builder): int
    {
        $endpoint = config('errorlog.endpoint');
        $projectCode = config('errorlog.project_code');
        $apiKey = config('errorlog.api_key');

        $missing = collect(['ERRORLOG_ENDPOINT' => $endpoint, 'ERRORLOG_PROJECT_CODE' => $projectCode, 'ERRORLOG_API_KEY' => $apiKey])
            ->filter(fn ($value) => blank($value))
            ->keys();

        if ($missing->isNotEmpty()) {
            $this->error('Missing config: '.$missing->implode(', '));

            return self::FAILURE;
        }

        if (config('errorlog.mode') === 'local') {
            $this->warn('ERRORLOG_MODE is "local"; real errors will not be sent. Set it to "remote" or "both".');
        }

        $this->line("Project: {$projectCode}");
        $this->line("Endpoint: {$endpoint}");

        try {
            $response = (new Client(['timeout' => (int) config('errorlog.timeout', 3) + 5, 'connect_timeout' => 5]))
                ->post($endpoint, [
                    'headers' => [
                        'Accept' => 'application/json',
                        'X-ErrorLog-Key' => (string) $apiKey,
                        'X-ErrorLog-Project' => (string) $projectCode,
                    ],
                    'json' => $builder->build(new RuntimeException('ErrorLog test: integration check from errorlog:test')),
                    'http_errors' => false,
                ]);
        } catch (Throwable $e) {
            $this->error('Request failed: '.$e->getMessage());

            return self::FAILURE;
        }

        $status = $response->getStatusCode();

        if ($status >= 200 && $status < 300) {
            $this->info("Success (HTTP {$status}). Check the Monitor page for the test error.");

            return self::SUCCESS;
        }

        $this->error(match ($status) {
            401 => 'HTTP 401: invalid project code or API key (or project is paused / unknown).',
            422 => 'HTTP 422: payload rejected by the server.',
            default => "HTTP {$status}",
        });
        $this->line(mb_substr((string) $response->getBody(), 0, 500));

        return self::FAILURE;
    }
}
