<?php

namespace App\Http\Controllers;

use App\Models\TipoGastronomico;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class TipoGastronomicoController extends Controller
{
    #[OA\Get(
        path: "/api/tipo-gastronomicos",
        summary: "Obtener todos los tipos de gastronomía",
        tags: ["Tipos de Gastronomia"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de tipos de gastronomía",
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
        return response()->json(TipoGastronomico::pluck('tipo'));
    }

    #[OA\Get(
        path: "/api/tipo-gastronomicos/{id}",
        summary: "Obtener un tipo de gastronomía por ID",
        tags: ["Tipos de Gastronomia"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del tipo de gastronomía",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detalles del tipo de gastronomía",
                content: new OA\JsonContent(ref: "#/components/schemas/TipoGastronomico")
            ),
            new OA\Response(
                response: 404,
                description: "Tipo de gastronomía no encontrado"
            )
        ]
    )]
    public function show($id)
    {
        $tipo = TipoGastronomico::find($id);

        if (!$tipo) {
            return response()->json(['message' => 'Tipo gastronomico no encontrado'], 404);
        }

        return response()->json($tipo);
    }

    #[OA\Post(
        path: "/api/tipo-gastronomicos",
        summary: "Crear un nuevo tipo de gastronomía",
        tags: ["Tipos de Gastronomia"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/TipoGastronomicoRequest")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Tipo de gastronomía creado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/TipoGastronomico")
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

        $tipo = TipoGastronomico::create($request->all());

        return response()->json($tipo, 201);
    }

    #[OA\Put(
        path: "/api/tipo-gastronomicos/{id}",
        summary: "Actualizar un tipo de gastronomía por ID",
        tags: ["Tipos de Gastronomia"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del tipo de gastronomía",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/TipoGastronomicoUpdateRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Tipo de gastronomía actualizado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/TipoGastronomico")
            ),
            new OA\Response(
                response: 404,
                description: "Tipo de gastronomía no encontrado"
            ),
            new OA\Response(
                response: 422,
                description: "Error de validación"
            )
        ]
    )]
    public function update(Request $request, $id)
    {
        $tipo = TipoGastronomico::find($id);

        if (!$tipo) {
            return response()->json(['message' => 'Tipo gastronomico no encontrado'], 404);
        }

        $tipo->update($request->all());

        return response()->json($tipo);
    }

    #[OA\Delete(
        path: "/api/tipo-gastronomicos/{id}",
        summary: "Eliminar un tipo de gastronomía por ID",
        tags: ["Tipos de Gastronomia"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del tipo de gastronomía",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Tipo de gastronomía eliminado correctamente"
            ),
            new OA\Response(
                response: 404,
                description: "Tipo de gastronomía no encontrado"
            )
        ]
    )]
    public function destroy($id)
    {
        $tipo = TipoGastronomico::find($id);

        if (!$tipo) {
            return response()->json(['message' => 'Tipo gastronomico no encontrado'], 404);
        }

        $tipo->delete();

        return response()->json(['message' => 'Tipo gastronomico eliminado correctamente']);
    }
}
