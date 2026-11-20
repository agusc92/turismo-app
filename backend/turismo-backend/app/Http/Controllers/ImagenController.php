<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Imagen;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ImagenController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Imagen::first()
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'actividades' => 'nullable|url|max:255',
            'gastronomicos' => 'nullable|url|max:255',
            'eventos' => 'nullable|url|max:255',
            'alojamientos' => 'nullable|url|max:255',
            'complejos' => 'nullable|url|max:255',
            'balnearios' => 'nullable|url|max:255',
        ]);

        $imagen = Imagen::updateOrCreate(
            ['id' => Imagen::value('id') ?? 1],
            $request->only([
            'actividades', 'gastronomicos', 'eventos',
            'alojamientos', 'complejos', 'balnearios',
            ])
        );

        return response()->json([
            'success' => true,
            'message' => 'Imagen agregada correctamente',
            'data' => $imagen
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $imagen = Imagen::find($id);
        if (!$imagen) {
            return response()->json([
                'success' => false, 
                'message' => 'Imagen no encontrada.'
            ], 404);
        }

        $imagen->delete();

        return response()->json([
            'success' => true,
            'message' => 'Imagen eliminada correctamente'
        ]);
    }
}
