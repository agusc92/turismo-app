<?php

namespace App\Http\Controllers;

use App\Models\Alojamiento;
use Illuminate\Http\Request;

class AlojamientoController extends Controller
{
    public function index()
    {
        return response()->json(Alojamiento::all());
    }

    public function show($id)
    {
        $alojamiento = Alojamiento::find($id);

        if (!$alojamiento) {
            return response()->json(['message' => 'Alojamiento no encontrado'], 404);
        }

        return response()->json($alojamiento);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'direccion' => 'required|string',
        ]);

        $alojamiento = Alojamiento::create($request->all());

        return response()->json($alojamiento, 201);
    }

    public function update(Request $request, $id)
    {
        $alojamiento = Alojamiento::find($id);

        if (!$alojamiento) {
            return response()->json(['message' => 'Alojamiento no encontrado'], 404);
        }

        $alojamiento->update($request->all());

        return response()->json($alojamiento);
    }

    public function destroy($id)
    {
        $alojamiento = Alojamiento::find($id);

        if (!$alojamiento) {
            return response()->json(['message' => 'Alojamiento no encontrado'], 404);
        }

        $alojamiento->delete();

        return response()->json(['message' => 'Alojamiento eliminado correctamente']);
    }
}
