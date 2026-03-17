<?php

namespace App\Http\Controllers;

use App\Models\Balneario;
use Illuminate\Http\Request;

class BalnearioController extends Controller
{
    public function index()
    {
        return response()->json(Balneario::all());
    }

    public function show($id)
    {
        $balneario = Balneario::find($id);

        if (!$balneario) {
            return response()->json(['message' => 'Balneario no encontrado'], 404);
        }

        return response()->json($balneario);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'direccion' => 'required|string',
        ]);

        $balneario = Balneario::create($request->all());

        return response()->json($balneario, 201);
    }

    public function update(Request $request, $id)
    {
        $balneario = Balneario::find($id);

        if (!$balneario) {
            return response()->json(['message' => 'Balneario no encontrado'], 404);
        }

        $balneario->update($request->all());

        return response()->json($balneario);
    }

    public function destroy($id)
    {
        $balneario = Balneario::find($id);

        if (!$balneario) {
            return response()->json(['message' => 'Balneario no encontrado'], 404);
        }

        $balneario->delete();

        return response()->json(['message' => 'Balneario eliminado correctamente']);
    }
}
