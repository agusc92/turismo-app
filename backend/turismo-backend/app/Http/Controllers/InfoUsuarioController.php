<?php

namespace App\Http\Controllers;

use App\Models\InfoUsuario;
use Illuminate\Http\Request;

class InfoUsuarioController extends Controller
{
    public function index()
    {
        return response()->json(InfoUsuario::with('intereses')->get());
    }

    public function show($id)
    {
        $info = InfoUsuario::with('intereses')->find($id);

        if (!$info) {
            return response()->json(['message' => 'InfoUsuario no encontrado'], 404);
        }

        return response()->json($info);
    }

    public function update(Request $request, $id)
    {
        $info = InfoUsuario::find($id);

        if (!$info) {
            return response()->json(['message' => 'InfoUsuario no encontrado'], 404);
        }

        $info->update($request->all());

        // Sync intereses if provided
        if ($request->has('intereses')) {
            $info->intereses()->sync($request->intereses);
        }

        return response()->json($info->load('intereses'));
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'InfoUsuario is created automatically on register'], 400);
    }

    public function destroy($id)
    {
        return response()->json(['message' => 'InfoUsuario is deleted automatically with user'], 400);
    }
}
