<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Alojamiento",
    title: "Alojamiento",
    description: "Modelo de un alojamiento",
    properties: [
        new OA\Property(property: "id", type: "integer", format: "int64", description: "ID único del alojamiento"),
        new OA\Property(property: "nombre", type: "string", description: "Nombre del alojamiento"),
        new OA\Property(property: "direccion", type: "string", description: "Dirección del alojamiento"),
        new OA\Property(property: "telefono", type: "string", nullable: true, description: "Número de teléfono"),
        new OA\Property(property: "redesSociales", type: "string", nullable: true, description: "URL de redes sociales"),
        new OA\Property(property: "paginaWeb", type: "string", nullable: true, description: "Página web del alojamiento"),
        new OA\Property(property: "mail", type: "string", format: "email", nullable: true, description: "Correo electrónico de contacto"),
        new OA\Property(property: "mascotas", type: "boolean", description: "Indica si se permiten mascotas"),
        new OA\Property(property: "periodoApertura", type: "string", nullable: true, description: "Período de apertura"),
        new OA\Property(property: "imagen", type: "string", nullable: true),
        new OA\Property(property: "latitud", type: "number", format: "float", nullable: true),
        new OA\Property(property: "longitud", type: "number", format: "float", nullable: true),
        new OA\Property(property: "habilitado", type: "boolean", description: "Indica si el alojamiento está habilitado"),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time"),
        new OA\Property(property: "tiposAlojamiento", type: "array", items: new OA\Items(ref: "#/components/schemas/TipoAlojamiento"), description: "Tipos de alojamiento")
    ]
)]
#[OA\Schema(
    schema: "AlojamientoRequest",
    title: "AlojamientoRequest",
    description: "Esquema para la creación de un alojamiento",
    required: ["nombre", "direccion", "mascotas", "tipos_alojamiento_ids"],
    properties: [
        new OA\Property(property: "nombre", type: "string"),
        new OA\Property(property: "direccion", type: "string"),
        new OA\Property(property: "telefono", type: "string", nullable: true),
        new OA\Property(property: "redesSociales", type: "string", nullable: true),
        new OA\Property(property: "paginaWeb", type: "string", nullable: true),
        new OA\Property(property: "mail", type: "string", format: "email", nullable: true),
        new OA\Property(property: "mascotas", type: "boolean"),
        new OA\Property(property: "periodoApertura", type: "string", nullable: true),
        new OA\Property(property: "imagen", type: "string", nullable: true),
        new OA\Property(property: "latitud", type: "number", format: "float", nullable: true),
        new OA\Property(property: "longitud", type: "number", format: "float", nullable: true),
        new OA\Property(property: "habilitado", type: "boolean", nullable: true),
        new OA\Property(property: "tipos_alojamiento_ids", type: "array", items: new OA\Items(type: "integer"), description: "IDs de los tipos de alojamiento")
    ]
)]
#[OA\Schema(
    schema: "AlojamientoUpdateRequest",
    title: "AlojamientoUpdateRequest",
    description: "Esquema para la actualización de un alojamiento",
    properties: [
        new OA\Property(property: "nombre", type: "string", nullable: true),
        new OA\Property(property: "direccion", type: "string", nullable: true),
        new OA\Property(property: "telefono", type: "string", nullable: true),
        new OA\Property(property: "redesSociales", type: "string", nullable: true),
        new OA\Property(property: "paginaWeb", type: "string", nullable: true),
        new OA\Property(property: "mail", type: "string", format: "email", nullable: true),
        new OA\Property(property: "mascotas", type: "boolean", nullable: true),
        new OA\Property(property: "periodoApertura", type: "string", nullable: true),
        new OA\Property(property: "imagen", type: "string", nullable: true),
        new OA\Property(property: "latitud", type: "number", format: "float", nullable: true),
        new OA\Property(property: "longitud", type: "number", format: "float", nullable: true),
        new OA\Property(property: "habilitado", type: "boolean", nullable: true),
        new OA\Property(property: "tipos_alojamiento_ids", type: "array", items: new OA\Items(type: "integer"), description: "IDs de los tipos de alojamiento")
    ]
)]
class Alojamiento extends Model
{
    use HasFactory;

    protected $table = 'alojamientos';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'redesSociales',
        'paginaWeb',
        'mail',
        'mascotas',
        'periodoApertura',
        'imagen',
        'latitud',
        'longitud',
        'habilitado',
    ];

    protected $casts = [
        'latitud' => 'float',
        'longitud' => 'float',
    ];

    protected function mascotas(): Attribute
    {
        return Attribute::make(
            get: fn($value) => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            set: fn($value) => filter_var($value, FILTER_VALIDATE_BOOLEAN)
        );
    }

    protected function habilitado(): Attribute
    {
        return Attribute::make(
            get: fn($value) => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            set: fn($value) => filter_var($value, FILTER_VALIDATE_BOOLEAN)
        );
    }

    public function tiposAlojamiento()
    {
        return $this->belongsToMany(TipoAlojamiento::class, 'alojamiento_tipo_alojamiento', 'alojamiento_id', 'tipo_alojamiento_id');
    }
}
