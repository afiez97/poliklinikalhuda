<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\App;

class LanguageSwitcher extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $currentLocale = App::getLocale();
        $availableLocales = config('app.available_locales');

        return view('components.language-switcher', [
            'currentLocale' => $currentLocale,
            'availableLocales' => $availableLocales
        ]);
    }
}
