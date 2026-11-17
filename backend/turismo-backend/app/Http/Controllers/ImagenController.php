<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Imagen;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

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
            'imagen' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'titulo' => 'nullable|string|max:255',
        ]);

        $path = $request->file('imagen')->store('imagenes', 'public');

        $imagen = Imagen::create([
            'url' => Storage::url($path),
            'titulo' => $request->titulo,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Imagen subida correctamente',
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