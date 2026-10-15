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
     * authenticated. Unlike the `auth` middleware this never redirects to a
     * login route. Without a configured guard, any logged-in guard passes.
     */
    public function handle(Request $request, Closure $next, ?string $guard = null): Response
    {
        $guards = $guard ?? config('errorlog.dashboard.guard');

        $guards = $guards ? (array) $guards : array_keys(config('auth.guards', []));

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $next($request);
            }
        }

        abort((int) config('errorlog.dashboard.unauthorized_status', 404));
    }
}
