<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\TranslationHelper;

class LanguageController extends Controller
{
    // Funcion para cambiar el idioma
    public function changeLanguage(Request $request)
    {
        $language = $request->input('lang', 'es');
        
        if (in_array($language, ['es', 'en'])) {
            TranslationHelper::setLanguage($language);
        }
        
        return response()->json([
            'success' => true,
            'language' => $language
        ]);
    }
    
    // Funcion para obtener las traducciones
    public function getTranslations()
    {
        $language = session('locale', 'es');
        TranslationHelper::setLanguage($language);
        
        $translations = include resource_path("lang/{$language}/messages.php");
        
        // Filtrar solo las traducciones que necesitamos para JavaScript
        // (valores dinamicos que vienen de la API de Rick & Morty)
        $jsKeys = [
            // Especies
            'Human', 'Alien', 'Humanoid', 'Robot', 'Animal', 'Cronenberg', 
            'Disease', 'Mythological Creature', 'Poopybutthole', 'unknown', 'Unknown',
            'Superhuman (Ghost trains summoner)',
            
            // Estado
            'Alive', 'Dead',
            
            // Genero
            'Male', 'Female', 'Genderless',
            
            // Ubicaciones
            'Earth', 'Earth (C-137)', 'Earth (Replacement Dimension)', 
            'Citadel of Ricks', 'Interdimensional Cable', 'Worldender\'s lair'
        ];
        
        $jsTranslations = [];
        foreach ($jsKeys as $key) {
            $jsTranslations[$key] = $translations[$key] ?? $key;
        }
        
        return response()->json($jsTranslations);
    }
}
