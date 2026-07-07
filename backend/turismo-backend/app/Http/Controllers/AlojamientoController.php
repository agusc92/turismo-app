<?php

namespace App\Http\Controllers;

use App\Models\Alojamiento;
use App\Models\TipoAlojamiento;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class AlojamientoController extends Controller
{
    #[OA\Get(
        path: "/api/alojamientos",
        summary: "Obtener todos los alojamientos habilitados",
        tags: ["Alojamientos"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de alojamientos habilitados",
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
        return response()->json(Alojamiento::with('tiposAlojamiento')->where('habilitado', true)->get());
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
        $alojamiento = Alojamiento::with('tiposAlojamiento')->find($id);

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
            'tipos_alojamiento_ids' => 'required|array',
            'tipos_alojamiento_ids.*' => 'exists:tipo_alojamientos,id',
            'imagen' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'habilitado' => 'nullable|boolean',
        ]);

        $alojamiento = Alojamiento::create($request->except('tipos_alojamiento_ids'));
        $alojamiento->tiposAlojamiento()->attach($request->input('tipos_alojamiento_ids'));

        return response()->json($alojamiento->load('tiposAlojamiento'), 201);
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
            'telefono' => 'sometimes|nullable|string',
            'redesSociales' => 'nullable|string',
            'paginaWeb' => 'nullable|string',
            'mail' => 'nullable|email',
            'mascotas' => 'nullable|boolean',
            'periodoApertura' => 'nullable|string',
            'tipos_alojamiento_ids' => 'sometimes|required|array',
            'tipos_alojamiento_ids.*' => 'exists:tipo_alojamientos,id',
            'imagen' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'habilitado' => 'nullable|boolean',
        ]);

        $alojamiento->update($request->except('tipos_alojamiento_ids'));

        if ($request->has('tipos_alojamiento_ids')) {
            $alojamiento->tiposAlojamiento()->sync($request->input('tipos_alojamiento_ids'));
        }

        return response()->json($alojamiento->load('tiposAlojamiento'));
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

    #[OA\Get(
        path: "/api/alojamientos/{id}/tipos",
        summary: "Obtener los tipos de alojamiento asociados a un alojamiento",
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
                description: "Lista de tipos de alojamiento",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/TipoAlojamiento")
                )
            ),
            new OA\Response(
                response: 404,
                description: "Alojamiento no encontrado"
            )
        ]
    )]
    public function tipos($id)
    {
        $alojamiento = Alojamiento::with('tiposAlojamiento')->find($id);

        if (!$alojamiento) {
            return response()->json(['message' => 'Alojamiento no encontrado'], 404);
        }

        return response()->json($alojamiento->tiposAlojamiento);
    }

    #[OA\Post(
        path: "/api/alojamientos/{id}/tipos",
        summary: "Asociar un tipo de alojamiento a un alojamiento",
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
            content: new OA\JsonContent(
                required: ["tipo_alojamiento_id"],
                properties: [
                    new OA\Property(property: "tipo_alojamiento_id", type: "integer", example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Tipo de alojamiento asociado exitosamente",
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
    public function addTipo(Request $request, $id)
    {
        $alojamiento = Alojamiento::find($id);

        if (!$alojamiento) {
            return response()->json(['message' => 'Alojamiento no encontrado'], 404);
        }

        $request->validate([
            'tipo_alojamiento_id' => 'required|exists:tipo_alojamientos,id',
        ]);

        $alojamiento->tiposAlojamiento()->attach($request->tipo_alojamiento_id);

        return response()->json($alojamiento->load('tiposAlojamiento'), 201);
    }

    #[OA\Delete(
        path: "/api/alojamientos/{id}/tipos/{tipoId}",
        summary: "Desasociar un tipo de alojamiento de un alojamiento",
        tags: ["Alojamientos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del alojamiento",
                schema: new OA\Schema(type: "integer", format: "int64")
            ),
            new OA\Parameter(
                name: "tipoId",
                in: "path",
                required: true,
                description: "ID del tipo de alojamiento a desasociar",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Tipo de alojamiento desasociado correctamente"
            ),
            new OA\Response(
                response: 404,
                description: "Alojamiento no encontrado"
            )
        ]
    )]
    public function removeTipo($id, $tipoId)
    {
        $alojamiento = Alojamiento::find($id);

        if (!$alojamiento) {
            return response()->json(['message' => 'Alojamiento no encontrado'], 404);
        }

        $alojamiento->tiposAlojamiento()->detach($tipoId);

        return response()->json(['message' => 'Tipo de alojamiento desasociado correctamente']);
    }
}
