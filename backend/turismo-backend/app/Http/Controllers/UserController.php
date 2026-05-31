<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    #[OA\Get(
        path: "/api/users",
        summary: "Obtener todos los usuarios con su información de perfil",
        tags: ["Usuarios"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de usuarios",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/User")
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
        return response()->json(User::with('infoUsuario')->get());
    }

    #[OA\Get(
        path: "/api/users/{id}",
        summary: "Obtener un usuario por ID con su información de perfil",
        tags: ["Usuarios"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del usuario",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detalles del usuario",
                content: new OA\JsonContent(ref: "#/components/schemas/User")
            ),
            new OA\Response(
                response: 404,
                description: "Usuario no encontrado"
            )
        ]
    )]
    public function show($id)
    {
        $user = User::with('infoUsuario')->find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json($user);
    }

    #[OA\Put(
        path: "/api/users/{id}",
        summary: "Actualizar un usuario por ID",
        tags: ["Usuarios"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del usuario",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/UserUpdateRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Usuario actualizado exitosamente",
                content: new OA\JsonContent(ref: "#/components/schemas/User")
            ),
            new OA\Response(
                response: 404,
                description: "Usuario no encontrado"
            ),
            new OA\Response(
                response: 422,
                description: "Error de validación"
            )
        ]
    )]
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->update($request->all());

        return response()->json($user);
    }

    #[OA\Delete(
        path: "/api/users/{id}",
        summary: "Eliminar un usuario por ID",
        tags: ["Usuarios"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del usuario",
                schema: new OA\Schema(type: "integer", format: "int64")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Usuario eliminado correctamente"
            ),
            new OA\Response(
                response: 404,
                description: "Usuario no encontrado"
            )
        ]
    )]
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }

    #[OA\Post(
        path: "/api/users",
        summary: "Crear un nuevo usuario (usar /register en su lugar)",
        tags: ["Usuarios"],
        responses: [
            new OA\Response(
                response: 400,
                description: "Solicitud incorrecta. Use /register para crear un usuario."
            )
        ]
    )]
    public function store(Request $request)
    {
        return response()->json(['message' => 'Use /register instead'], 400);
    }
}
