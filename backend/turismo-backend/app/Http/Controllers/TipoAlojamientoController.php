<?php

namespace App\Http\Controllers;

use App\Models\TipoAlojamiento;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class TipoAlojamientoController extends Controller
{
    #[OA\Get(
        path: "/api/tipos-alojamiento",
        summary: "Obtener todos los tipos de alojamiento",
        tags: ["Tipos de Alojamiento"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de tipos de alojamiento",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/TipoAlojamiento")
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
        return response()->json(TipoAlojamiento::all());
    }

    #[OA\Get(
        path: "/api/tipos-alojamiento/{id}",
        summary: "Obtener un tipo de alojamiento por ID",
        tags: ["Tipos de Alojamiento"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del tipo de alojamiento",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detalles del tipo de alojamiento",
                content: new OA\JsonContent(ref: "#/components/schemas/TipoAlojamiento")
            ),
            new OA\Response(
                response: 404,
                description: "Tipo de alojamiento no encontrado"
            )
        ]
    )]
    public function show($id)
    {
        $tipoAlojamiento = TipoAlojamiento::find($id);

        if (!$tipoAlojamiento) {
            return response()->json(['message' => 'Tipo de alojamiento no encontrado'], 404);
        }

        return response()->json($tipoAlojamiento);
    }

    #[OA\Post(
        path: "/api/tipos-alojamiento",
        summary: "Crear un nuevo tipo de alojamiento",
        tags: ["Tipos de Alojamiento"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/TipoAlojamientoRequest")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Tipo de alojamiento creado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/TipoAlojamiento")
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
            'tipo' => 'required|string|unique:tipo_alojamientos,tipo',
        ]);

        $tipoAlojamiento = TipoAlojamiento::create($request->all());

        return response()->json($tipoAlojamiento, 201);
    }

    #[OA\Put(
        path: "/api/tipos-alojamiento/{id}",
        summary: "Actualizar un tipo de alojamiento por ID",
        tags: ["Tipos de Alojamiento"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del tipo de alojamiento",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/TipoAlojamientoUpdateRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Tipo de alojamiento actualizado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/TipoAlojamiento")
            ),
            new OA\Response(
                response: 404,
                description: "Tipo de alojamiento no encontrado"
            )
        ]
    )]
    public function update(Request $request, $id)
    {
        $tipoAlojamiento = TipoAlojamiento::find($id);

        if (!$tipoAlojamiento) {
            return response()->json(['message' => 'Tipo de alojamiento no encontrado'], 404);
        }

        $request->validate([
            'tipo' => 'sometimes|required|string|unique:tipo_alojamientos,tipo,' . $id,
        ]);

        $tipoAlojamiento->update($request->all());

        return response()->json($tipoAlojamiento);
    }

    #[OA\Delete(
        path: "/api/tipos-alojamiento/{id}",
        summary: "Eliminar un tipo de alojamiento por ID",
        tags: ["Tipos de Alojamiento"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del tipo de alojamiento",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Tipo de alojamiento eliminado correctamente"
            ),
            new OA\Response(
                response: 404,
                description: "Tipo de alojamiento no encontrado"
            )
        ]
    )]
    public function destroy($id)
    {
        $tipoAlojamiento = TipoAlojamiento::find($id);

        if (!$tipoAlojamiento) {
            return response()->json(['message' => 'Tipo de alojamiento no encontrado'], 404);
        }

        $tipoAlojamiento->delete();

        return response()->json(['message' => 'Tipo de alojamiento eliminado correctamente']);
    }
}
