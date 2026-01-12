<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Spatie\RouteAttributes\Attributes\Get;

class LocaleController extends Controller
{
    /**
     * Change the application locale
     *
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    #[Get('/locale/{locale}', name: 'locale.change')]
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

        // Check if there's a referer header
        $referer = request()->headers->get('referer');

        if ($referer) {
            return redirect()->back();
        }

        // If no referer, redirect to home
        return redirect()->route('portal.home');
    }
}
