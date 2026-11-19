<?php

namespace App\Http\Controllers\Api;

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
            'data' => Imagen::all()
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|url|max:255',
            'titulo' => 'nullable|string|max:255',
        ]);

        $imagen = Imagen::create([
            'url' => $request->url,
            'titulo' => $request->titulo,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Imagen agregada correctamente',
            'data' => $imagen
        ], 201);
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
