<?php

namespace App\Http\Middleware;

use App\Models\Greylist;
use Closure;

class IpMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $restrictIps = Greylist::all(['address'])->pluck('address')->toArray();
        if (in_array($request->ip(), $restrictIps)) {
            return abort(404);
        }
        return $next($request);
    }
}
