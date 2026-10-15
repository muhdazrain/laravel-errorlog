<?php

namespace Azrain\LaravelErrorLog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void report(\Throwable $e)
 *
 * @see \Azrain\LaravelErrorLog\ErrorLog
 */
class ErrorLog extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Azrain\LaravelErrorLog\ErrorLog::class;
    }
}
