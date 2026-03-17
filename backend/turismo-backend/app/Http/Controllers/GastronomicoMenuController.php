<?php

namespace App\Http\Controllers;

use App\Models\Gastronomico;
use Illuminate\Http\Request;

class GastronomicoMenuController extends Controller
{
    // GET /api/gastronomicos/{id}/menus
    public function index($gastronomicoId)
    {
        $gastronomico = Gastronomico::with('menus')->find($gastronomicoId);

        if (!$gastronomico) {
            return response()->json(['message' => 'Gastronomico no encontrado'], 404);
        }

        return response()->json($gastronomico->menus);
    }

    // POST /api/gastronomicos/{id}/menus
    public function store(Request $request, $gastronomicoId)
    {
        $gastronomico = Gastronomico::find($gastronomicoId);

        if (!$gastronomico) {
            return response()->json(['message' => 'Gastronomico no encontrado'], 404);
        }

        $request->validate([
            'menu_id' => 'required|exists:menus,id',
        ]);

        $gastronomico->menus()->attach($request->menu_id);

        return response()->json($gastronomico->load('menus'), 201);
    }

    // DELETE /api/gastronomicos/{id}/menus/{menuId}
    public function destroy($gastronomicoId, $menuId)
    {
        $gastronomico = Gastronomico::find($gastronomicoId);

        if (!$gastronomico) {
            return response()->json(['message' => 'Gastronomico no encontrado'], 404);
        }

        $gastronomico->menus()->detach($menuId);

        return response()->json(['message' => 'Menu eliminado del gastronomico']);
    }
}
