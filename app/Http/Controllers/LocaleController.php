<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class LocaleController extends Controller
{
    /**
     * Change the application locale
     *
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeLocale($locale)
    {
        // Validate if the locale is supported
        $availableLocales = config('app.available_locales');

        if (in_array($locale, array_keys($availableLocales))) {
            // Store in session
            Session::put('locale', $locale);
            Session::save(); // Force save session

            // Also store in cookie as fallback
            Cookie::queue('locale', $locale, 60 * 24 * 365); // 1 year
        }

        return redirect()->route('portal.home');
    }
}
