<?php

namespace App\Http\Controllers;

use App\Models\TipoGastronomico;
use Illuminate\Http\Request;

class TipoGastronomicoController extends Controller
{
    public function index()
    {
        return response()->json(TipoGastronomico::pluck('tipo'));
    }

    public function show($id)
    {
        $tipo = TipoGastronomico::find($id);

        if (!$tipo) {
            return response()->json(['message' => 'Tipo gastronomico no encontrado'], 404);
        }

        return response()->json($tipo);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string',
        ]);

        $tipo = TipoGastronomico::create($request->all());

        return response()->json($tipo, 201);
    }

    public function update(Request $request, $id)
    {
        $tipo = TipoGastronomico::find($id);

        if (!$tipo) {
            return response()->json(['message' => 'Tipo gastronomico no encontrado'], 404);
        }

        $tipo->update($request->all());

        return response()->json($tipo);
    }

    public function destroy($id)
    {
        $tipo = TipoGastronomico::find($id);

        if (!$tipo) {
            return response()->json(['message' => 'Tipo gastronomico no encontrado'], 404);
        }

        $tipo->delete();

        return response()->json(['message' => 'Tipo gastronomico eliminado correctamente']);
    }
}
