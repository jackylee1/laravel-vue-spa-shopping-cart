<?php

namespace App\Http\Middleware;

use Closure;

class OnlyAdministration
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (auth()->guard($guard)->check()) {
            if (auth()->guard($guard)->user()->status === 'administration') {
                return $next($request);
            }
        }
        return response()->json(['error' => 'No access rights'], 403);
    }
}
