<?php

namespace App\Http\Controllers;

use App\Models\Gastronomico;
use Illuminate\Http\Request;
use App\Models\TipoGastronomico;

class GastronomicoController extends Controller
{
    public function index()
    {
        return response()->json(Gastronomico::with('menus')->get());
    }

    public function show($id)
    {
        $gastronomico = Gastronomico::with('menus')->find($id);

        if (!$gastronomico) {
            return response()->json(['message' => 'Gastronomico no encontrado'], 404);
        }

        return response()->json($gastronomico);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'direccion' => 'required|string',
        ]);

        $gastronomico = Gastronomico::create($request->all());

        return response()->json($gastronomico, 201);
    }

    public function update(Request $request, $id)
    {
        $gastronomico = Gastronomico::find($id);

        if (!$gastronomico) {
            return response()->json(['message' => 'Gastronomico no encontrado'], 404);
        }

        $gastronomico->update($request->all());

        return response()->json($gastronomico);
    }

    public function destroy($id)
    {
        $gastronomico = Gastronomico::find($id);

        if (!$gastronomico) {
            return response()->json(['message' => 'Gastronomico no encontrado'], 404);
        }

        $gastronomico->delete();

        return response()->json(['message' => 'Gastronomico eliminado correctamente']);
    }

    // GET /api/gastronomicos/{id}/tipos
    public function tipos($id)
{
    $gastronomico = Gastronomico::with('tipos')->find($id);

    if (!$gastronomico) {
        return response()->json(['message' => 'Gastronomico no encontrado'], 404);
    }

    return response()->json($gastronomico->tipos->pluck('tipo'));
}

    // POST /api/gastronomicos/{id}/tipos
    public function addTipo(Request $request, $id)
    {
        $gastronomico = Gastronomico::find($id);

        if (!$gastronomico) {
            return response()->json(['message' => 'Gastronomico no encontrado'], 404);
        }

        $request->validate([
            'tipo_gastronomico_id' => 'required|exists:tipo_gastronomicos,id',
        ]);

        $gastronomico->tipos()->attach($request->tipo_gastronomico_id);

        return response()->json($gastronomico->load('tipos'), 201);
    }

    // DELETE /api/gastronomicos/{id}/tipos/{tipoId}
    public function removeTipo($id, $tipoId)
    {
        $gastronomico = Gastronomico::find($id);

        if (!$gastronomico) {
            return response()->json(['message' => 'Gastronomico no encontrado'], 404);
        }

        $gastronomico->tipos()->detach($tipoId);

        return response()->json(['message' => 'Tipo eliminado del gastronomico']);
    }
}
