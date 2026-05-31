<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class EventoController extends Controller
{
    #[OA\Get(
        path: "/api/eventos",
        summary: "Obtener todos los eventos",
        tags: ["Eventos"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de eventos",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Evento")
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
        return response()->json(Evento::all());
    }

    #[OA\Get(
        path: "/api/eventos/{id}",
        summary: "Obtener un evento por ID",
        tags: ["Eventos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del evento",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detalles del evento",
                content: new OA\JsonContent(ref: "#/components/schemas/Evento")
            ),
            new OA\Response(
                response: 404,
                description: "Evento no encontrado"
            )
        ]
    )]
    public function show($id)
    {
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['message' => 'Evento no encontrado'], 404);
        }

        return response()->json($evento);
    }

    #[OA\Get(
        path: "/api/eventos/destacados",
        summary: "Obtener eventos destacados",
        tags: ["Eventos"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de eventos destacados",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Evento")
                )
            ),
            new OA\Response(
                response: "default",
                description: "Ha ocurrido un error."
            )
        ]
    )]
    public function destacados()
    {
        $destacados = Evento::where('destacado', true)->get();
        return response()->json($destacados);
    }

    #[OA\Post(
        path: "/api/eventos",
        summary: "Crear un nuevo evento",
        tags: ["Eventos"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/EventoRequest")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Evento creado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/Evento")
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
            'fecha' => 'required|date',
            'local' => 'required|string',
        ]);

        $evento = Evento::create($request->all());

        return response()->json($evento, 201);
    }

    #[OA\Put(
        path: "/api/eventos/{id}",
        summary: "Actualizar un evento por ID",
        tags: ["Eventos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del evento",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/EventoUpdateRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Evento actualizado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/Evento")
            ),
            new OA\Response(
                response: 404,
                description: "Evento no encontrado"
            ),
            new OA\Response(
                response: 422,
                description: "Error de validación"
            )
        ]
    )]
    public function update(Request $request, $id)
    {
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['message' => 'Evento no encontrado'], 404);
        }

        $evento->update($request->all());

        return response()->json($evento);
    }

    #[OA\Delete(
        path: "/api/eventos/{id}",
        summary: "Eliminar un evento por ID",
        tags: ["Eventos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del evento",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Evento eliminado correctamente"
            ),
            new OA\Response(
                response: 404,
                description: "Evento no encontrado"
            )
        ]
    )]
    public function destroy($id)
    {
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['message' => 'Evento no encontrado'], 404);
        }

        $evento->delete();

        return response()->json(['message' => 'Evento eliminado correctamente']);
    }
}
