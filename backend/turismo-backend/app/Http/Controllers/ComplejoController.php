<?php

namespace App\Http\Controllers;

use App\Models\Complejo;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ComplejoController extends Controller
{
    #[OA\Get(
        path: "/api/complejos",
        summary: "Obtener todos los complejos",
        tags: ["Complejos"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de complejos",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Complejo")
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
        return response()->json(Complejo::all());
    }

    #[OA\Get(
        path: "/api/complejos/{id}",
        summary: "Obtener un complejo por ID",
        tags: ["Complejos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del complejo",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detalles del complejo",
                content: new OA\JsonContent(ref: "#/components/schemas/Complejo")
            ),
            new OA\Response(
                response: 404,
                description: "Complejo no encontrado"
            )
        ]
    )]
    public function show($id)
    {
        $complejo = Complejo::find($id);

        if (!$complejo) {
            return response()->json(['message' => 'Complejo no encontrado'], 404);
        }

        return response()->json($complejo);
    }

    #[OA\Post(
        path: "/api/complejos",
        summary: "Crear un nuevo complejo",
        tags: ["Complejos"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/ComplejoRequest")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Complejo creado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/Complejo")
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
            'mail' => 'nullable|email',
            'redesSociales' => 'nullable|string',
            'telefono' => 'nullable|string',
            'servicio' => 'nullable|string',
            'adicional' => 'nullable|string',
        ]);

        $complejo = Complejo::create($request->all());

        return response()->json($complejo, 201);
    }

    #[OA\Put(
        path: "/api/complejos/{id}",
        summary: "Actualizar un complejo por ID",
        tags: ["Complejos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del complejo",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/ComplejoUpdateRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Complejo actualizado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/Complejo")
            ),
            new OA\Response(
                response: 404,
                description: "Complejo no encontrado"
            ),
            new OA\Response(
                response: 422,
                description: "Error de validación"
            )
        ]
    )]
    public function update(Request $request, $id)
    {
        $complejo = Complejo::find($id);

        if (!$complejo) {
            return response()->json(['message' => 'Complejo no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string',
            'direccion' => 'sometimes|required|string',
            'mail' => 'nullable|email',
            'redesSociales' => 'nullable|string',
            'telefono' => 'nullable|string',
            'servicio' => 'nullable|string',
            'adicional' => 'nullable|string',
        ]);

        $complejo->update($request->all());

        return response()->json($complejo);
    }

    #[OA\Delete(
        path: "/api/complejos/{id}",
        summary: "Eliminar un complejo por ID",
        tags: ["Complejos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del complejo",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Complejo eliminado correctamente"
            ),
            new OA\Response(
                response: 404,
                description: "Complejo no encontrado"
            )
        ]
    )]
    public function destroy($id)
    {
        $complejo = Complejo::find($id);

        if (!$complejo) {
            return response()->json(['message' => 'Complejo no encontrado'], 404);
        }

        $complejo->delete();

        return response()->json(['message' => 'Complejo eliminado correctamente']);
    }
}
