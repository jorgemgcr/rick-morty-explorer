<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\TranslationHelper;

class LanguageController extends Controller
{
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
    
    public function getTranslations()
    {
        $language = session('locale', 'es');
        TranslationHelper::setLanguage($language);
        
        $translations = include resource_path("lang/{$language}/messages.php");
        
        // Filtrar solo las traducciones que necesitamos para JavaScript
        // (valores dinámicos que vienen de la API de Rick & Morty)
        $jsKeys = [
            // Species
            'Human', 'Alien', 'Humanoid', 'Robot', 'Animal', 'Cronenberg', 
            'Disease', 'Mythological Creature', 'Poopybutthole', 'unknown', 'Unknown',
            'Superhuman (Ghost trains summoner)',
            
            // Status
            'Alive', 'Dead',
            
            // Gender
            'Male', 'Female', 'Genderless',
            
            // Locations
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
