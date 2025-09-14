<?php

namespace App\Helpers;

class TranslationHelper
{
    private static $translations = [];
    private static $currentLanguage = 'es';
    
    // Funcion para inicializar las traducciones
    public static function init()
    {
        self::$currentLanguage = session('locale', 'es');
        self::loadTranslations();
    }
    
    // Funcion para cambiar el idioma
    public static function setLanguage($language)
    {
        self::$currentLanguage = $language;
        session(['locale' => $language]);
        self::loadTranslations();
    }
    
    // Funcion para obtener el idioma
    public static function getLanguage()
    {
        if (session()->has('locale')) {
            self::$currentLanguage = session('locale', 'es');
        }
        return self::$currentLanguage;
    }
    
    // Funcion para obtener la traduccion
    public static function t($key, $default = null)
    {
        if (empty(self::$translations)) {
            self::init();
        }
        
        return self::$translations[$key] ?? $default ?? $key;
    }
    
    // Funcion para cargar las traducciones
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

// Funcion global para obtener la traduccion
if (!function_exists('t')) {
    function t($key, $default = null)
    {
        return \App\Helpers\TranslationHelper::t($key, $default);
    }
}
