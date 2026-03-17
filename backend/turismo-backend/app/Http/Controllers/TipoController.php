<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Illuminate\Http\Request;

class TipoController extends Controller
{
    public function index()
    {
        return response()->json(Tipo::all());
    }

    public function show($id)
    {
        $tipo = Tipo::find($id);

        if (!$tipo) {
            return response()->json(['message' => 'Tipo no encontrado'], 404);
        }

        return response()->json($tipo);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string',
        ]);

        $tipo = Tipo::create($request->all());

        return response()->json($tipo, 201);
    }

    public function update(Request $request, $id)
    {
        $tipo = Tipo::find($id);

        if (!$tipo) {
            return response()->json(['message' => 'Tipo no encontrado'], 404);
        }

        $tipo->update($request->all());

        return response()->json($tipo);
    }

    public function destroy($id)
    {
        $tipo = Tipo::find($id);

        if (!$tipo) {
            return response()->json(['message' => 'Tipo no encontrado'], 404);
        }

        $tipo->delete();

        return response()->json(['message' => 'Tipo eliminado correctamente']);
    }
}
