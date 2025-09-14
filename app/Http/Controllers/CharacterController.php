<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CharacterController extends Controller
{
    
    // Funcion para obtener los personajes de la API
    public function fetch(): JsonResponse
    {
        try {
            $response = Http::get('https://rickandmortyapi.com/api/character');
            
            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch data from Rick & Morty API'
                ], 500);
            }

            $data = $response->json();
            $characters = $data['results'] ?? [];
            $savedCount = 0;

            foreach ($characters as $characterData) {
                // Verificar si el personaje ya existe
                $existingCharacter = Character::where('url', $characterData['url'])->first();
                
                if (!$existingCharacter) {
                    Character::create([
                        'name' => $characterData['name'],
                        'status' => $characterData['status'],
                        'species' => $characterData['species'],
                        'type' => $characterData['type'] ?? null,
                        'gender' => $characterData['gender'],
                        'origin_name' => $characterData['origin']['name'] ?? null,
                        'origin_url' => $characterData['origin']['url'] ?? null,
                        'location_name' => $characterData['location']['name'] ?? null,
                        'location_url' => $characterData['location']['url'] ?? null,
                        'image_url' => $characterData['image'],
                        'episode_urls' => $characterData['episode'] ?? [],
                        'url' => $characterData['url'],
                        'created_at_api' => $characterData['created'],
                    ]);
                    $savedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully fetched and saved {$savedCount} new characters",
                'total_fetched' => count($characters),
                'new_saved' => $savedCount
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching characters from API: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching characters'
            ], 500);
        }
    }

    // Funcion para obtener los personajes de la base de datos
    public function index(Request $request): JsonResponse
    {
        $query = Character::query();

        // Funcion de busqueda
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('species', 'like', "%{$searchTerm}%")
                  ->orWhere('status', 'like', "%{$searchTerm}%")
                  ->orWhere('gender', 'like', "%{$searchTerm}%");
            });
        }

        // Filtro por status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filtro por especie
        if ($request->has('species') && !empty($request->species)) {
            $query->where('species', $request->species);
        }

        // Paginacion
        $perPage = $request->get('per_page', 15);
        $characters = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $characters->items(),
            'pagination' => [
                'current_page' => $characters->currentPage(),
                'last_page' => $characters->lastPage(),
                'per_page' => $characters->perPage(),
                'total' => $characters->total(),
                'from' => $characters->firstItem(),
                'to' => $characters->lastItem(),
            ]
        ]);
    }

    // funcion para obtener los detalles de un personaje
    public function show($id): JsonResponse
    {
        try {
            $character = Character::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $character
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Character not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching character details: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching character details'
            ], 500);
        }
    }
}
