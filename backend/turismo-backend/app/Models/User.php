<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\InfoUsuario;
use Laravel\Sanctum\HasApiTokens;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "User",
    title: "User",
    description: "Modelo de usuario",
    properties: [
        new OA\Property(property: "id", type: "integer", format: "int64", description: "ID único del usuario"),
        new OA\Property(property: "name", type: "string", description: "Nombre del usuario"),
        new OA\Property(property: "email", type: "string", format: "email", description: "Correo electrónico del usuario"),
        new OA\Property(property: "rol", type: "string", description: "Rol del usuario (ej. 'admin', 'turista')", example: "turista"),
        new OA\Property(property: "created_at", type: "string", format: "date-time", description: "Fecha de creación"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", description: "Fecha de última actualización"),
        new OA\Property(property: "info_usuario", ref: "#/components/schemas/InfoUsuario", description: "Información de perfil del usuario")
    ]
)]
#[OA\Schema(
    schema: "UserRegisterRequest",
    title: "UserRegisterRequest",
    description: "Esquema para el registro de un nuevo usuario",
    required: ["name", "email", "password"],
    properties: [
        new OA\Property(property: "name", type: "string", example: "John Doe"),
        new OA\Property(property: "email", type: "string", format: "email", example: "john.doe@example.com"),
        new OA\Property(property: "password", type: "string", format: "password", example: "password123")
    ]
)]
#[OA\Schema(
    schema: "UserLoginRequest",
    title: "UserLoginRequest",
    description: "Esquema para el inicio de sesión de usuario",
    required: ["email", "password"],
    properties: [
        new OA\Property(property: "email", type: "string", format: "email", example: "john.doe@example.com"),
        new OA\Property(property: "password", type: "string", format: "password", example: "password123")
    ]
)]
#[OA\Schema(
    schema: "UserUpdateRequest",
    title: "UserUpdateRequest",
    description: "Esquema para la actualización de un usuario",
    properties: [
        new OA\Property(property: "name", type: "string", nullable: true, example: "Jane Doe"),
        new OA\Property(property: "email", type: "string", format: "email", nullable: true, example: "jane.doe@example.com"),
        new OA\Property(property: "password", type: "string", format: "password", nullable: true, example: "newpassword456"),
        new OA\Property(property: "rol", type: "string", nullable: true, example: "admin")
    ]
)]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function infoUsuario()
    {
        return $this->hasOne(InfoUsuario::class);
    }
}
