<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use Illuminate\Http\Request;

class ActividadController extends Controller
{
    public function index()
    {
        return response()->json(Actividad::with('tipo')->get());
    }

    public function show($id)
    {
        $actividad = Actividad::with('tipo')->find($id);

        if (!$actividad) {
            return response()->json(['message' => 'Actividad no encontrada'], 404);
        }

        return response()->json($actividad);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'direccion' => 'required|string',
            'tipo_id' => 'required|exists:tipos,id',
        ]);

        $actividad = Actividad::create($request->all());

        return response()->json($actividad, 201);
    }

    public function update(Request $request, $id)
    {
        $actividad = Actividad::find($id);

        if (!$actividad) {
            return response()->json(['message' => 'Actividad no encontrada'], 404);
        }

        $actividad->update($request->all());

        return response()->json($actividad);
    }

    public function destroy($id)
    {
        $actividad = Actividad::find($id);

        if (!$actividad) {
            return response()->json(['message' => 'Actividad no encontrada'], 404);
        }

        $actividad->delete();

        return response()->json(['message' => 'Actividad eliminada correctamente']);
    }
}
