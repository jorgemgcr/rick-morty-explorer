<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Create an alias for the TranslationHelper
        $this->app->alias(\App\Helpers\TranslationHelper::class, 'translation');
        
        // Register a global helper function
        if (!function_exists('t')) {
            function t($key, $default = null) {
                return \App\Helpers\TranslationHelper::t($key, $default);
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // For Laravel 12, we need to explicitly load translation files
        $this->loadTranslations();
    }

    /**
     * Load translation files for all available locales
     */
    protected function loadTranslations(): void
    {
        $translator = $this->app['translator'];
        
        // Get available locales
        $locales = ['es', 'en'];
        
        foreach ($locales as $locale) {
            $translationPath = resource_path("lang/{$locale}");
            if (is_dir($translationPath)) {
                $translator->addNamespace('*', $translationPath);
            }
        }
    }
}
