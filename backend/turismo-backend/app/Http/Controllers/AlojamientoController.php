<?php

namespace App\Http\Controllers;

use App\Models\Alojamiento;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class AlojamientoController extends Controller
{
    #[OA\Get(
        path: "/api/alojamientos",
        summary: "Obtener todos los alojamientos",
        tags: ["Alojamientos"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de alojamientos",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Alojamiento")
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
        return response()->json(Alojamiento::all());
    }

    #[OA\Get(
        path: "/api/alojamientos/{id}",
        summary: "Obtener un alojamiento por ID",
        tags: ["Alojamientos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del alojamiento",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detalles del alojamiento",
                content: new OA\JsonContent(ref: "#/components/schemas/Alojamiento")
            ),
            new OA\Response(
                response: 404,
                description: "Alojamiento no encontrado"
            )
        ]
    )]
    public function show($id)
    {
        $alojamiento = Alojamiento::find($id);

        if (!$alojamiento) {
            return response()->json(['message' => 'Alojamiento no encontrado'], 404);
        }

        return response()->json($alojamiento);
    }

    #[OA\Post(
        path: "/api/alojamientos",
        summary: "Crear un nuevo alojamiento",
        tags: ["Alojamientos"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/AlojamientoRequest")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Alojamiento creado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/Alojamiento")
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
            'telefono' => 'nullable|string',
            'redesSociales' => 'nullable|string',
            'paginaWeb' => 'nullable|string',
            'mail' => 'nullable|email',
            'mascotas' => 'nullable|boolean',
            'periodoApertura' => 'nullable|string',
            'tipo' => 'required|string',
        ]);

        $alojamiento = Alojamiento::create($request->all());

        return response()->json($alojamiento, 201);
    }

    #[OA\Put(
        path: "/api/alojamientos/{id}",
        summary: "Actualizar un alojamiento por ID",
        tags: ["Alojamientos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del alojamiento",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/AlojamientoUpdateRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Alojamiento actualizado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/Alojamiento")
            ),
            new OA\Response(
                response: 404,
                description: "Alojamiento no encontrado"
            ),
            new OA\Response(
                response: 422,
                description: "Error de validación"
            )
        ]
    )]
    public function update(Request $request, $id)
    {
        $alojamiento = Alojamiento::find($id);

        if (!$alojamiento) {
            return response()->json(['message' => 'Alojamiento no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string',
            'direccion' => 'sometimes|required|string',
            'telefono' => 'nullable|string',
            'redesSociales' => 'nullable|string',
            'paginaWeb' => 'nullable|string',
            'mail' => 'nullable|email',
            'mascotas' => 'nullable|boolean',
            'periodoApertura' => 'nullable|string',
            'tipo' => 'sometimes|required|string',
        ]);

        $alojamiento->update($request->all());

        return response()->json($alojamiento);
    }

    #[OA\Delete(
        path: "/api/alojamientos/{id}",
        summary: "Eliminar un alojamiento por ID",
        tags: ["Alojamientos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del alojamiento",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Alojamiento eliminado correctamente"
            ),
            new OA\Response(
                response: 404,
                description: "Alojamiento no encontrado"
            )
        ]
    )]
    public function destroy($id)
    {
        $alojamiento = Alojamiento::find($id);

        if (!$alojamiento) {
            return response()->json(['message' => 'Alojamiento no encontrado'], 404);
        }

        $alojamiento->delete();

        return response()->json(['message' => 'Alojamiento eliminado correctamente']);
    }
}
