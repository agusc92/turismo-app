<?php

namespace App\Http\Controllers;

use App\Models\Gastronomico;
use Illuminate\Http\Request;

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
}
