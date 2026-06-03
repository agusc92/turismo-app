<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class TipoController extends Controller
{
    #[OA\Get(
        path: "/api/tipos",
        summary: "Obtener todos los tipos genéricos",
        tags: ["Tipos"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de tipos genéricos",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(type: "string")
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
        return response()->json(Tipo::pluck('tipo'));
    }

    #[OA\Get(
        path: "/api/tipos/{id}",
        summary: "Obtener un tipo genérico por ID",
        tags: ["Tipos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del tipo genérico",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detalles del tipo genérico",
                content: new OA\JsonContent(ref: "#/components/schemas/Tipo")
            ),
            new OA\Response(
                response: 404,
                description: "Tipo genérico no encontrado"
            )
        ]
    )]
    public function show($id)
    {
        $tipo = Tipo::find($id);

        if (!$tipo) {
            return response()->json(['message' => 'Tipo no encontrado'], 404);
        }

        return response()->json($tipo);
    }

    #[OA\Post(
        path: "/api/tipos",
        summary: "Crear un nuevo tipo genérico",
        tags: ["Tipos"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/TipoRequest")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Tipo genérico creado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/Tipo")
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

        $tipo = Tipo::create($request->all());

        return response()->json($tipo, 201);
    }

    #[OA\Put(
        path: "/api/tipos/{id}",
        summary: "Actualizar un tipo genérico por ID",
        tags: ["Tipos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del tipo genérico",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/TipoUpdateRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Tipo genérico actualizado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/Tipo")
            ),
            new OA\Response(
                response: 404,
                description: "Tipo genérico no encontrado"
            ),
            new OA\Response(
                response: 422,
                description: "Error de validación"
            )
        ]
    )]
    public function update(Request $request, $id)
    {
        $tipo = Tipo::find($id);

        if (!$tipo) {
            return response()->json(['message' => 'Tipo no encontrado'], 404);
        }

        $request->validate([
            'tipo' => 'sometimes|required|string',
        ]);

        $tipo->update($request->all());

        return response()->json($tipo);
    }

    #[OA\Delete(
        path: "/api/tipos/{id}",
        summary: "Eliminar un tipo genérico por ID",
        tags: ["Tipos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del tipo genérico",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Tipo genérico eliminado correctamente"
            ),
            new OA\Response(
                response: 404,
                description: "Tipo genérico no encontrado"
            )
        ]
    )]
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
