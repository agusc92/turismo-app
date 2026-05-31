<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Tipo;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "InfoUsuario",
    title: "InfoUsuario",
    description: "Modelo de información de perfil de usuario",
    properties: [
        new OA\Property(property: "id", type: "integer", format: "int64", description: "ID único de la información de usuario"),
        new OA\Property(property: "ciudad", type: "string", nullable: true, description: "Ciudad de residencia del usuario"),
        new OA\Property(property: "edad", type: "integer", nullable: true, description: "Edad del usuario"),
        new OA\Property(property: "estadia", type: "string", nullable: true, description: "Duración de la estadía del usuario"),
        new OA\Property(property: "integrantes", type: "integer", nullable: true, description: "Número de integrantes del grupo del usuario"),
        new OA\Property(property: "user_id", type: "integer", format: "int64", description: "ID del usuario asociado"),
        new OA\Property(property: "created_at", type: "string", format: "date-time", description: "Fecha de creación"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", description: "Fecha de última actualización"),
        new OA\Property(property: "intereses", type: "array", items: new OA\Items(ref: "#/components/schemas/Tipo"), description: "Lista de intereses del usuario")
    ]
)]
#[OA\Schema(
    schema: "InfoUsuarioUpdateRequest",
    title: "InfoUsuarioUpdateRequest",
    description: "Esquema para la actualización de la información de perfil de usuario",
    properties: [
        new OA\Property(property: "ciudad", type: "string", nullable: true, example: "Mar del Plata"),
        new OA\Property(property: "edad", type: "integer", nullable: true, example: 30),
        new OA\Property(property: "estadia", type: "string", nullable: true, example: "1 semana"),
        new OA\Property(property: "integrantes", type: "integer", nullable: true, example: 2),
        new OA\Property(property: "intereses", type: "array", items: new OA\Items(type: "integer"), nullable: true, description: "IDs de los intereses a sincronizar", example: [1, 3])
    ]
)]
class InfoUsuario extends Model
{
    protected $table = 'info_usuarios';

    protected $fillable = [
        'ciudad',
        'edad',
        'estadia',
        'integrantes',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function intereses()
    {
        return $this->belongsToMany(Tipo::class, 'usuario_intereses', 'info_usuario_id', 'tipo_id');
    }
}
