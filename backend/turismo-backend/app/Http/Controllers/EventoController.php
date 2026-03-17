<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    // GET /api/eventos
    public function index()
    {
        return response()->json(Evento::all());
    }

    // GET /api/eventos/{id}
    public function show($id)
    {
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['message' => 'Evento no encontrado'], 404);
        }

        return response()->json($evento);
    }

    // POST /api/eventos
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'direccion' => 'required|string',
            'fecha' => 'required|date',
            'local' => 'required|string',
        ]);

        $evento = Evento::create($request->all());

        return response()->json($evento, 201);
    }

    // PUT /api/eventos/{id}
    public function update(Request $request, $id)
    {
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['message' => 'Evento no encontrado'], 404);
        }

        $evento->update($request->all());

        return response()->json($evento);
    }

    // DELETE /api/eventos/{id}
    public function destroy($id)
    {
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['message' => 'Evento no encontrado'], 404);
        }

        $evento->delete();

        return response()->json(['message' => 'Evento eliminado correctamente']);
    }
}
