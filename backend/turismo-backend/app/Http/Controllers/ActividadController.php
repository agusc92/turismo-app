<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ActividadController extends Controller
{
    #[OA\Get(
        path: "/api/actividades",
        summary: "Obtener todas las actividades habilitadas",
        tags: ["Actividades"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de actividades habilitadas",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Actividad")
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
        return response()->json(Actividad::with('tipo')->where('habilitado', true)->get());
    }

    #[OA\Get(
        path: "/api/actividades/{id}",
        summary: "Obtener una actividad por ID",
        tags: ["Actividades"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID de la actividad",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detalles de la actividad",
                content: new OA\JsonContent(ref: "#/components/schemas/Actividad")
            ),
            new OA\Response(
                response: 404,
                description: "Actividad no encontrada"
            )
        ]
    )]
    public function show($id)
    {
        $actividad = Actividad::with('tipo')->find($id);

        if (!$actividad) {
            return response()->json(['message' => 'Actividad no encontrada'], 404);
        }

        return response()->json($actividad);
    }

    #[OA\Post(
        path: "/api/actividades",
        summary: "Crear una nueva actividad",
        tags: ["Actividades"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/ActividadRequest")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Actividad creada exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/Actividad")
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
            'descripcion' => 'nullable|string',
            'redes_sociales' => 'nullable|string',
            'web' => 'nullable|string',
            'mail' => 'nullable|email',
            'telefono' => 'nullable|string',
            'imagen' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'tipo_id' => 'required|exists:tipos,id',
            'dias_y_horarios' => 'nullable|string',
            'habilitado' => 'nullable|boolean',
        ]);

        $actividad = Actividad::create($request->all());

        return response()->json($actividad, 201);
    }

    #[OA\Put(
        path: "/api/actividades/{id}",
        summary: "Actualizar una actividad por ID",
        tags: ["Actividades"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID de la actividad",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/ActividadUpdateRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Actividad actualizada exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/Actividad")
            ),
            new OA\Response(
                response: 404,
                description: "Actividad no encontrada"
            )
        ]
    )]
    public function update(Request $request, $id)
    {
        $actividad = Actividad::find($id);

        if (!$actividad) {
            return response()->json(['message' => 'Actividad no encontrada'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string',
            'direccion' => 'sometimes|required|string',
            'descripcion' => 'nullable|string',
            'redes_sociales' => 'nullable|string',
            'web' => 'nullable|string',
            'mail' => 'nullable|email',
            'telefono' => 'nullable|string',
            'imagen' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'tipo_id' => 'sometimes|required|exists:tipos,id',
            'dias_y_horarios' => 'nullable|string',
            'habilitado' => 'nullable|boolean',
        ]);

        $actividad->update($request->all());

        return response()->json($actividad);
    }

    #[OA\Delete(
        path: "/api/actividades/{id}",
        summary: "Eliminar una actividad por ID",
        tags: ["Actividades"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID de la actividad",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Actividad eliminada correctamente"
            ),
            new OA\Response(
                response: 404,
                description: "Actividad no encontrada"
            )
        ]
    )]
    public function destroy($id)
    {
        $actividad = Actividad::find($id);

        if (!$actividad) {
            return response()->json(['message' => 'Actividad no encontrada'], 404);
        }

        $actividad->delete();

        return response()->json(['message' => 'Actividad eliminado correctamente']);
    }
}
