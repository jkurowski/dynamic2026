<?php

namespace App\Http\Middleware;

use App\Models\File;
use Closure;
use Illuminate\Http\Request;

class CheckForFileOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $file = File::find($request->file_catalog);
        if($file->user_id == auth()->id()){
            return $next($request);
        } else {
            abort(403);
        }
    }
}
