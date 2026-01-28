<?php

if (!function_exists('trans_with_fallback')) {
    /**
     * Translate the given message with fallback to default locale.
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string|array|null
     */
    function trans_with_fallback($key, array $replace = [], $locale = null)
    {
        $translator = app('translator');
        $locale = $locale ?: app()->getLocale();
        $fallback = config('app.fallback_locale', 'en');

        $translation = $translator->get($key, $replace, $locale);

        // If translation is the same as the key (not found), try fallback
        if ($translation === $key && $locale !== $fallback) {
            $translation = $translator->get($key, $replace, $fallback);
        }

        return $translation;
    }
}
