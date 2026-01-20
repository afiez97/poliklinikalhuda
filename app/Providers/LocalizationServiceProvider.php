<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
        // Register Blade directive for easier translation with fallback
        Blade::directive('langf', function ($expression) {
            return "<?php echo app('translator')->get($expression, [], app()->getLocale()) ?: app('translator')->get($expression, [], config('app.fallback_locale')); ?>";
        });

        // Register Blade directive for translation with parameters
        Blade::directive('lang', function ($expression) {
            return "<?php echo trans_with_fallback($expression); ?>";
        });

        // Register Blade directive for language switcher
        Blade::directive('languageSwitcher', function () {
            return "<?php echo view('components.language-switcher'); ?>";
        });
    }
}
