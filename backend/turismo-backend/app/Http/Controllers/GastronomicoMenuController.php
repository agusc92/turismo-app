<?php

namespace App\Http\Controllers;

use App\Models\Gastronomico;
use App\Models\Menu;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class GastronomicoMenuController extends Controller
{
    #[OA\Get(
        path: "/api/gastronomicos/{gastronomicoId}/menus",
        summary: "Obtener los menús asociados a un establecimiento gastronómico",
        tags: ["Gastronomicos"],
        parameters: [
            new OA\Parameter(
                name: "gastronomicoId",
                in: "path",
                required: true,
                description: "ID del establecimiento gastronómico",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de menús asociados",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Menu")
                )
            ),
            new OA\Response(
                response: 404,
                description: "Gastronómico no encontrado"
            )
        ]
    )]
    public function index($gastronomicoId)
    {
        $gastronomico = Gastronomico::with('menus')->find($gastronomicoId);

        if (!$gastronomico) {
            return response()->json(['message' => 'Gastronomico no encontrado'], 404);
        }

        return response()->json($gastronomico->menus);
    }

    #[OA\Post(
        path: "/api/gastronomicos/{gastronomicoId}/menus",
        summary: "Asociar un menú a un establecimiento gastronómico",
        tags: ["Gastronomicos"],
        parameters: [
            new OA\Parameter(
                name: "gastronomicoId",
                in: "path",
                required: true,
                description: "ID del establecimiento gastronómico",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["menu_id"],
                properties: [
                    new OA\Property(property: "menu_id", type: "integer", example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Menú asociado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/Gastronomico")
            ),
            new OA\Response(
                response: 404,
                description: "Gastronómico no encontrado"
            ),
            new OA\Response(
                response: 422,
                description: "Error de validación"
            )
        ]
    )]
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

    #[OA\Delete(
        path: "/api/gastronomicos/{gastronomicoId}/menus/{menuId}",
        summary: "Desasociar un menú de un establecimiento gastronómico",
        tags: ["Gastronomicos"],
        parameters: [
            new OA\Parameter(
                name: "gastronomicoId",
                in: "path",
                required: true,
                description: "ID del establecimiento gastronómico",
                schema: new OA\Schema(type: "integer", format: "int64")
            ),
            new OA\Parameter(
                name: "menuId",
                in: "path",
                required: true,
                description: "ID del menú a desasociar",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Menú eliminado del gastronómico"
            ),
            new OA\Response(
                response: 404,
                description: "Gastronómico o Menú no encontrado"
            )
        ]
    )]
    public function destroy($gastronomicoId, $menuId)
    {
        $gastronomico = Gastronomico::find($gastronomicoId);

        if (!$gastronomico) {
            return response()->json(['message' => 'Gastronomico no encontrado'], 404);
        }

        $menu = Menu::find($menuId);
        if (!$menu) {
            return response()->json(['message' => 'Menu no encontrado'], 404);
        }

        $gastronomico->menus()->detach($menuId);

        return response()->json(['message' => 'Menu eliminado del gastronomico']);
    }
}
