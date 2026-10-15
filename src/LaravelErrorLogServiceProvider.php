<?php

namespace Azrain\LaravelErrorLog;

use Azrain\LaravelErrorLog\Console\TestCommand;
use Azrain\LaravelErrorLog\Http\Middleware\AuthorizeErrorLogDashboard;
use Azrain\LaravelErrorLog\Transports\CompositeTransport;
use Azrain\LaravelErrorLog\Transports\DatabaseTransport;
use Azrain\LaravelErrorLog\Transports\HttpTransport;
use Azrain\LaravelErrorLog\Transports\TransportInterface;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;

class LaravelErrorLogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/errorlog.php', 'errorlog');

        $this->app->singleton(ErrorLog::class);

        $this->app->bind(TransportInterface::class, function () {
            $local = fn () => new DatabaseTransport;
            $remote = fn () => new HttpTransport(
                endpoint: config('errorlog.endpoint'),
                apiKey: config('errorlog.api_key'),
                timeout: (int) config('errorlog.timeout', 3),
                projectCode: config('errorlog.project_code'),
            );

            return match (config('errorlog.mode')) {
                'remote' => $remote(),
                'both' => new CompositeTransport([$local(), $remote()]),
                default => $local(),
            };
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/errorlog.php' => config_path('errorlog.php'),
        ], 'errorlog-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/errorlog'),
        ], 'errorlog-views');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'errorlog-migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([TestCommand::class]);
        }

        if (! config('errorlog.enabled', true)) {
            return;
        }

        $storesLocally = config('errorlog.mode') !== 'remote'; // local + both

        if ($storesLocally && config('errorlog.dashboard.enabled', true)) {
            $this->app['router']->aliasMiddleware('errorlog.auth', AuthorizeErrorLogDashboard::class);
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'errorlog');
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        }

        if ($storesLocally) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        $handler = $this->app->make(ExceptionHandler::class);

        if (method_exists($handler, 'reportable')) {
            $handler->reportable(function (\Throwable $e) {
                $this->app->make(ErrorLog::class)->report($e);
            });
        }
    }
}
