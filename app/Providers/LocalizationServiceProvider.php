<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class LocalizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register helper function for translation with fallback
        if (!function_exists('trans_with_fallback')) {
            /**
             * Translate the given message with fallback support.
             *
             * @param  string  $key
             * @param  array   $replace
             * @param  string|null  $locale
             * @return string
             */
            function trans_with_fallback($key, $replace = [], $locale = null)
            {
                $translation = trans($key, $replace, $locale);

                // If translation key is returned (not found), try fallback locale
                if ($translation === $key) {
                    $fallbackLocale = config('app.fallback_locale');
                    if ($locale !== $fallbackLocale) {
                        $translation = trans($key, $replace, $fallbackLocale);
                    }
                }

                return $translation;
            }
        }

        // Register Blade directive for easier translation
        Blade::directive('lang', function ($expression) {
            return "<?php echo trans_with_fallback($expression); ?>";
        });

        // Register Blade directive for language switcher
        Blade::directive('languageSwitcher', function () {
            return "<?php echo view('components.language-switcher'); ?>";
        });
    }
}
