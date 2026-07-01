<?php

namespace App\Http\Controllers;

use App\Models\Gastronomico;
use Illuminate\Http\Request;
use App\Models\TipoGastronomico;
use OpenApi\Attributes as OA;

class GastronomicoController extends Controller
{
    #[OA\Get(
        path: "/api/gastronomicos",
        summary: "Obtener todos los establecimientos gastronómicos habilitados",
        tags: ["Gastronomicos"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de establecimientos gastronómicos habilitados",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Gastronomico")
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
        return response()->json(Gastronomico::with(['menus', 'tipos'])->where('habilitado', true)->get());
    }

    #[OA\Get(
        path: "/api/gastronomicos/{id}",
        summary: "Obtener un establecimiento gastronómico por ID",
        tags: ["Gastronomicos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del establecimiento gastronómico",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detalles del establecimiento gastronómico",
                content: new OA\JsonContent(ref: "#/components/schemas/Gastronomico")
            ),
            new OA\Response(
                response: 404,
                description: "Gastronómico no encontrado"
            )
        ]
    )]
    public function show($id)
    {
        $gastronomico = Gastronomico::with(['menus', 'tipos'])->find($id);

        if (!$gastronomico) {
            return response()->json(['message' => 'Gastronomico no encontrado'], 404);
        }

        return response()->json($gastronomico);
    }

    #[OA\Post(
        path: "/api/gastronomicos",
        summary: "Crear un nuevo establecimiento gastronómico",
        tags: ["Gastronomicos"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/GastronomicoRequest")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Establecimiento gastronómico creado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/Gastronomico")
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
            'tiendaOnline' => 'nullable|string',
            'extras' => 'nullable|string',
            'horario' => 'nullable|string',
            'imagen' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'habilitado' => 'nullable|boolean',
            'tipo_ids' => 'nullable|array',
            'tipo_ids.*' => 'exists:tipo_gastronomicos,id',
            'menu_ids' => 'nullable|array',
            'menu_ids.*' => 'exists:menus,id',
        ]);

        $gastronomico = Gastronomico::create($request->all());

        if ($request->has('tipo_ids')) {
            $gastronomico->tipos()->attach($request->tipo_ids);
        }
        if ($request->has('menu_ids')) {
            $gastronomico->menus()->attach($request->menu_ids);
        }

        return response()->json($gastronomico->load(['menus', 'tipos']), 201);
    }

    #[OA\Put(
        path: "/api/gastronomicos/{id}",
        summary: "Actualizar un establecimiento gastronómico por ID",
        tags: ["Gastronomicos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del establecimiento gastronómico",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/GastronomicoUpdateRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Establecimiento gastronómico actualizado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/Gastronomico")
            ),
            new OA\Response(
                response: 404,
                description: "Gastronómico no encontrado"
            ),
            new OA\Response(
                response: 422,
                description: "Error de validación"
            )
        ]
    )]
    public function update(Request $request, $id)
    {
        $gastronomico = Gastronomico::find($id);

        if (!$gastronomico) {
            return response()->json(['message' => 'Gastronomico no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string',
            'direccion' => 'sometimes|required|string',
            'telefono' => 'nullable|string',
            'redesSociales' => 'nullable|string',
            'tiendaOnline' => 'nullable|string',
            'extras' => 'nullable|string',
            'horario' => 'nullable|string',
            'imagen' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'habilitado' => 'nullable|boolean',
            'tipo_ids' => 'nullable|array',
            'tipo_ids.*' => 'exists:tipo_gastronomicos,id',
            'menu_ids' => 'nullable|array',
            'menu_ids.*' => 'exists:menus,id',
        ]);

        $gastronomico->update($request->all());

        if ($request->has('tipo_ids')) {
            $gastronomico->tipos()->sync($request->tipo_ids);
        }
        if ($request->has('menu_ids')) {
            $gastronomico->menus()->sync($request->menu_ids);
        }

        return response()->json($gastronomico->load(['menus', 'tipos']));
    }

    #[OA\Delete(
        path: "/api/gastronomicos/{id}",
        summary: "Eliminar un establecimiento gastronómico por ID",
        tags: ["Gastronomicos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del establecimiento gastronómico",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Establecimiento gastronómico eliminado correctamente"
            ),
            new OA\Response(
                response: 404,
                description: "Gastronómico no encontrado"
            )
        ]
    )]
    public function destroy($id)
    {
        $gastronomico = Gastronomico::find($id);

        if (!$gastronomico) {
            return response()->json(['message' => 'Gastronomico no encontrado'], 404);
        }

        $gastronomico->delete();

        return response()->json(['message' => 'Gastronomico eliminado correctamente']);
    }

    #[OA\Get(
        path: "/api/gastronomicos/{id}/tipos",
        summary: "Obtener los tipos de gastronomía asociados a un establecimiento",
        tags: ["Gastronomicos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del establecimiento gastronómico",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
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
                response: 404,
                description: "Gastronómico no encontrado"
            )
        ]
    )]
    public function tipos($id)
    {
        $gastronomico = Gastronomico::with('tipos')->find($id);

        if (!$gastronomico) {
            return response()->json(['message' => 'Gastronomico no encontrado'], 404);
        }

        return response()->json($gastronomico->tipos->pluck('tipo'));
    }

    #[OA\Post(
        path: "/api/gastronomicos/{id}/tipos",
        summary: "Asociar un tipo de gastronomía a un establecimiento",
        tags: ["Gastronomicos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del establecimiento gastronómico",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["tipo_gastronomico_id"],
                properties: [
                    new OA\Property(property: "tipo_gastronomico_id", type: "integer", example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Tipo de gastronomía asociado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/Gastronomico")
            ),
            new OA\Response(
                response: 404,
                description: "Gastronómico no encontrado"
            ),
            new OA\Response(
                response: 422,
                description: "Error de validación"
            )
        ]
    )]
    public function addTipo(Request $request, $id)
    {
        $gastronomico = Gastronomico::find($id);

        if (!$gastronomico) {
            return response()->json(['message' => 'Gastronomico no encontrado'], 404);
        }

        $request->validate([
            'tipo_gastronomico_id' => 'required|exists:tipo_gastronomicos,id',
        ]);

        $gastronomico->tipos()->attach($request->tipo_gastronomico_id);

        return response()->json($gastronomico->load(['menus', 'tipos']), 201);
    }

    #[OA\Delete(
        path: "/api/gastronomicos/{id}/tipos/{tipoId}",
        summary: "Desasociar un tipo de gastronomía de un establecimiento",
        tags: ["Gastronomicos"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del establecimiento gastronómico",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Tipo de gastronomía eliminado del gastronómico"
            ),
            new OA\Response(
                response: 404,
                description: "Gastronómico no encontrado"
            )
        ]
    )]
    public function removeTipo($id, $tipoId)
    {
        $gastronomico = Gastronomico::find($id);

        if (!$gastronomico) {
            return response()->json(['message' => 'Gastronomico no encontrado'], 404);
        }

        $gastronomico->tipos()->detach($tipoId);

        return response()->json(['message' => 'Tipo eliminado del gastronomico']);
    }
}
