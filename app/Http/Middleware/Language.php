<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;

class Language
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
        // Safely retrieve the 'locale' parameter from the route, with a default of null
        $locale = $request->route('locale');

        // Check if the locale exists in the available locales
        if ($locale && array_key_exists($locale, config('app.available_locales'))) {
            // Set the locale in session and for the application
            session()->put('locale', $locale);
            app()->setLocale($locale);
            URL::defaults(['locale' => $locale]);
        } else {
            // If the locale is invalid but provided, redirect to the current locale
            if ($locale) {
                return redirect('/' . app()->getLocale());
            }
        }

        return $next($request);
    }
}
