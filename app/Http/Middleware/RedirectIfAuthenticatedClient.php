<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class RedirectIfAuthenticatedClient
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('client')->check() && $request->is('client/login')) {
            return redirect('/client/area');
        }

        return $next($request);
    }
}
