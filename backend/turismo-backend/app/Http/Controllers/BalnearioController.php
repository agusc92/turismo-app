<?php

namespace App\Http\Controllers;

use App\Models\Balneario;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class BalnearioController extends Controller
{
    #[OA\Get(
        path: "/api/balnearios",
        summary: "Obtener todos los balnearios",
        tags: ["Balnearios"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de balnearios",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Balneario")
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
        return response()->json(Balneario::all());
    }

    #[OA\Get(
        path: "/api/balnearios/{id}",
        summary: "Obtener un balneario por ID",
        tags: ["Balnearios"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del balneario",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detalles del balneario",
                content: new OA\JsonContent(ref: "#/components/schemas/Balneario")
            ),
            new OA\Response(
                response: 404,
                description: "Balneario no encontrado"
            )
        ]
    )]
    public function show($id)
    {
        $balneario = Balneario::find($id);

        if (!$balneario) {
            return response()->json(['message' => 'Balneario no encontrado'], 404);
        }

        return response()->json($balneario);
    }

    #[OA\Post(
        path: "/api/balnearios",
        summary: "Crear un nuevo balneario",
        tags: ["Balnearios"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/BalnearioRequest")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Balneario creado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/Balneario")
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
            'nombre' => 'required|string',
            'direccion' => 'required|string',
        ]);

        $balneario = Balneario::create($request->all());

        return response()->json($balneario, 201);
    }

    #[OA\Put(
        path: "/api/balnearios/{id}",
        summary: "Actualizar un balneario por ID",
        tags: ["Balnearios"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del balneario",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/BalnearioUpdateRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Balneario actualizado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/Balneario")
            ),
            new OA\Response(
                response: 404,
                description: "Balneario no encontrado"
            ),
            new OA\Response(
                response: 422,
                description: "Error de validación"
            )
        ]
    )]
    public function update(Request $request, $id)
    {
        $balneario = Balneario::find($id);

        if (!$balneario) {
            return response()->json(['message' => 'Balneario no encontrado'], 404);
        }

        $balneario->update($request->all());

        return response()->json($balneario);
    }

    #[OA\Delete(
        path: "/api/balnearios/{id}",
        summary: "Eliminar un balneario por ID",
        tags: ["Balnearios"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del balneario",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Balneario eliminado correctamente"
            ),
            new OA\Response(
                response: 404,
                description: "Balneario no encontrado"
            )
        ]
    )]
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
