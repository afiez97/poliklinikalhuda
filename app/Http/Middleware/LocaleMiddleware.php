<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = null;

        // Priority 1: Check if locale is provided in the URL
        if ($request->has('locale')) {
            $locale = $request->get('locale');

            // Validate and store if supported
            if (in_array($locale, array_keys(config('app.available_locales')))) {
                Session::put('locale', $locale);
                App::setLocale($locale);
            }
        }
        // Priority 2: Check if locale is stored in session
        elseif (Session::has('locale')) {
            $locale = Session::get('locale');

            // Validate if the locale is supported
            if (in_array($locale, array_keys(config('app.available_locales')))) {
                App::setLocale($locale);
            }
        }
        // Priority 3: Check cookie
        elseif (Cookie::get('locale')) {
            $locale = Cookie::get('locale');

            // Validate if the locale is supported
            if (in_array($locale, array_keys(config('app.available_locales')))) {
                Session::put('locale', $locale); // Store in session for next request
                App::setLocale($locale);
            }
        }
        // Priority 4: Use default locale
        else {
            App::setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
