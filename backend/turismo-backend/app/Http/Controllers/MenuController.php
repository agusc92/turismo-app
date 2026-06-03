<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class MenuController extends Controller
{
    #[OA\Get(
        path: "/api/menus",
        summary: "Obtener todos los tipos de menú",
        tags: ["Menus"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de tipos de menú",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Menu")
                )
            ),
            new OA\Response(
                response: "default",
                description: "Ha ocurrido un error."
            )
        ]
    )]
    public function index()
    {
        return response()->json(Menu::all());
    }

    #[OA\Get(
        path: "/api/menus/{id}",
        summary: "Obtener un tipo de menú por ID",
        tags: ["Menus"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del tipo de menú",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detalles del tipo de menú",
                content: new OA\JsonContent(ref: "#/components/schemas/Menu")
            ),
            new OA\Response(
                response: 404,
                description: "Tipo de menú no encontrado"
            )
        ]
    )]
    public function show($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json(['message' => 'Menu no encontrado'], 404);
        }

        return response()->json($menu);
    }

    #[OA\Post(
        path: "/api/menus",
        summary: "Crear un nuevo tipo de menú",
        tags: ["Menus"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/MenuRequest")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Tipo de menú creado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/Menu")
            ),
            new OA\Response(
                response: 422,
                description: "Error de validación"
            )
        ]
    )]
    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string',
        ]);

        $menu = Menu::create($request->all());

        return response()->json($menu, 201);
    }

    #[OA\Put(
        path: "/api/menus/{id}",
        summary: "Actualizar un tipo de menú por ID",
        tags: ["Menus"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del tipo de menú",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/MenuUpdateRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Tipo de menú actualizado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/Menu")
            ),
            new OA\Response(
                response: 404,
                description: "Tipo de menú no encontrado"
            ),
            new OA\Response(
                response: 422,
                description: "Error de validación"
            )
        ]
    )]
    public function update(Request $request, $id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json(['message' => 'Menu no encontrado'], 404);
        }

        $request->validate([
            'tipo' => 'sometimes|required|string',
        ]);

        $menu->update($request->all());

        return response()->json($menu);
    }

    #[OA\Delete(
        path: "/api/menus/{id}",
        summary: "Eliminar un tipo de menú por ID",
        tags: ["Menus"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del tipo de menú",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Tipo de menú eliminado correctamente"
            ),
            new OA\Response(
                response: 404,
                description: "Tipo de menú no encontrado"
            )
        ]
    )]
    public function destroy($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json(['message' => 'Menu no encontrado'], 404);
        }

        $menu->delete();

        return response()->json(['message' => 'Menu eliminado correctamente']);
    }
}
