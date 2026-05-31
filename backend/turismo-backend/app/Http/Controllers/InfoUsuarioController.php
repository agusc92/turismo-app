<?php

namespace App\Http\Controllers;

use App\Models\InfoUsuario;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class InfoUsuarioController extends Controller
{
    #[OA\Get(
        path: "/api/info-usuarios",
        summary: "Obtener toda la información de perfil de usuario",
        tags: ["InfoUsuario"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de información de perfil de usuario",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/InfoUsuario")
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
        return response()->json(InfoUsuario::with('intereses')->get());
    }

    #[OA\Get(
        path: "/api/info-usuarios/{id}",
        summary: "Obtener información de perfil de usuario por ID",
        tags: ["InfoUsuario"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID de la información de perfil de usuario",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detalles de la información de perfil de usuario",
                content: new OA\JsonContent(ref: "#/components/schemas/InfoUsuario")
            ),
            new OA\Response(
                response: 404,
                description: "InfoUsuario no encontrado"
            )
        ]
    )]
    public function show($id)
    {
        $info = InfoUsuario::with('intereses')->find($id);

        if (!$info) {
            return response()->json(['message' => 'InfoUsuario no encontrado'], 404);
        }

        return response()->json($info);
    }

    #[OA\Put(
        path: "/api/info-usuarios/{id}",
        summary: "Actualizar información de perfil de usuario por ID",
        tags: ["InfoUsuario"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID de la información de perfil de usuario",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/InfoUsuarioUpdateRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "InfoUsuario actualizado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/InfoUsuario")
            ),
            new OA\Response(
                response: 404,
                description: "InfoUsuario no encontrado"
            ),
            new OA\Response(
                response: 422,
                description: "Error de validación"
            )
        ]
    )]
    public function update(Request $request, $id)
    {
        $info = InfoUsuario::find($id);

        if (!$info) {
            return response()->json(['message' => 'InfoUsuario no encontrado'], 404);
        }

        $info->update($request->all());

        // Sync intereses if provided
        if ($request->has('intereses')) {
            $info->intereses()->sync($request->intereses);
        }

        return response()->json($info->load('intereses'));
    }

    #[OA\Post(
        path: "/api/info-usuarios",
        summary: "Crear nueva información de perfil de usuario (se crea automáticamente)",
        tags: ["InfoUsuario"],
        responses: [
            new OA\Response(
                response: 400,
                description: "Solicitud incorrecta. InfoUsuario se crea automáticamente al registrar un usuario."
            )
        ]
    )]
    public function store(Request $request)
    {
        return response()->json(['message' => 'InfoUsuario is created automatically on register'], 400);
    }

    #[OA\Delete(
        path: "/api/info-usuarios/{id}",
        summary: "Eliminar información de perfil de usuario (se elimina automáticamente)",
        tags: ["InfoUsuario"],
        responses: [
            new OA\Response(
                response: 400,
                description: "Solicitud incorrecta. InfoUsuario se elimina automáticamente con el usuario."
            )
        ]
    )]
    public function destroy($id)
    {
        return response()->json(['message' => 'InfoUsuario is deleted automatically with user'], 400);
    }
}
