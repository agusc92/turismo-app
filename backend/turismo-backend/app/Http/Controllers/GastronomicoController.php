<?php

namespace App\Http\Controllers;

use App\Models\Gastronomico;
use Illuminate\Http\Request;
use App\Models\TipoGastronomico;

class GastronomicoController extends Controller
{
    public function index()
    {
        return response()->json(Gastronomico::with(['menus', 'tipos'])->get());
    }

    public function show($id)
    {
        $gastronomico = Gastronomico::with(['menus', 'tipos'])->find($id);

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
            'telefono' => 'nullable|string',
            'redesSociales' => 'nullable|string',
            'tiendaOnline' => 'nullable|string',
            'extras' => 'nullable|string',
            'horario' => 'nullable|string',
            'imagen' => 'nullable|string',
            'tipo_ids' => 'nullable|array',
            'tipo_ids.*' => 'exists:tipo_gastronomicos,id',
            'menu_ids' => 'nullable|array',
            'menu_ids.*' => 'exists:menus,id',
        ]);

        $gastronomico = Gastronomico::create($request->all());

        if ($request->has('tipo_ids')) {
            $gastronomico->tipos()->attach($request->tipo_ids);
        }
        if ($request->has('menu_ids')) {
            $gastronomico->menus()->attach($request->menu_ids);
        }

        return response()->json($gastronomico->load(['menus', 'tipos']), 201);
    }

    public function update(Request $request, $id)
    {
        $gastronomico = Gastronomico::find($id);

        if (!$gastronomico) {
            return response()->json(['message' => 'Gastronomico no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string',
            'direccion' => 'sometimes|required|string',
            'telefono' => 'nullable|string',
            'redesSociales' => 'nullable|string',
            'tiendaOnline' => 'nullable|string',
            'extras' => 'nullable|string',
            'horario' => 'nullable|string',
            'imagen' => 'nullable|string',
            'tipo_ids' => 'nullable|array',
            'tipo_ids.*' => 'exists:tipo_gastronomicos,id',
            'menu_ids' => 'nullable|array',
            'menu_ids.*' => 'exists:menus,id',
        ]);

        $gastronomico->update($request->all());

        if ($request->has('tipo_ids')) {
            $gastronomico->tipos()->sync($request->tipo_ids);
        }
        if ($request->has('menu_ids')) {
            $gastronomico->menus()->sync($request->menu_ids);
        }

        return response()->json($gastronomico->load(['menus', 'tipos']));
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

        return response()->json($gastronomico->load(['menus', 'tipos']), 201);
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
