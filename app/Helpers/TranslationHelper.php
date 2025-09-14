<?php

namespace App\Helpers;

class TranslationHelper
{
    private static $translations = [];
    private static $currentLanguage = 'es';
    
    public static function init()
    {
        self::$currentLanguage = session('locale', 'es');
        self::loadTranslations();
    }
    
    public static function setLanguage($language)
    {
        self::$currentLanguage = $language;
        session(['locale' => $language]);
        self::loadTranslations();
    }
    
    public static function getLanguage()
    {
        if (session()->has('locale')) {
            self::$currentLanguage = session('locale', 'es');
        }
        return self::$currentLanguage;
    }
    
    public static function t($key, $default = null)
    {
        if (empty(self::$translations)) {
            self::init();
        }
        
        return self::$translations[$key] ?? $default ?? $key;
    }
    
    private static function loadTranslations()
    {
        $language = self::$currentLanguage;
        $file = resource_path("lang/{$language}/messages.php");
        
        if (file_exists($file)) {
            self::$translations = include $file;
        } else {
            self::$translations = [];
        }
    }
}

// Global helper function
if (!function_exists('t')) {
    function t($key, $default = null)
    {
        return \App\Helpers\TranslationHelper::t($key, $default);
    }
}
