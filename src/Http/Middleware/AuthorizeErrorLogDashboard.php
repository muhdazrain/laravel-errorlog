<?php

namespace Azrain\LaravelErrorLog\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeErrorLogDashboard
{
    /**
     * Abort with the configured status (default 404) when the visitor is not
     * authenticated on the given (or configured/default) guard. Unlike the
     * `auth` middleware this never redirects to a login route.
     */
    public function handle(Request $request, Closure $next, ?string $guard = null): Response
    {
        $guard ??= config('errorlog.dashboard.guard');

        if (! Auth::guard($guard)->check()) {
            abort((int) config('errorlog.dashboard.unauthorized_status', 404));
        }

        return $next($request);
    }
}
