<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Complejo",
    title: "Complejo",
    description: "Modelo de un complejo",
    properties: [
        new OA\Property(property: "id", type: "integer", format: "int64", description: "ID único del complejo"),
        new OA\Property(property: "nombre", type: "string", description: "Nombre del complejo"),
        new OA\Property(property: "direccion", type: "string", description: "Dirección del complejo"),
        new OA\Property(property: "mail", type: "string", format: "email", nullable: true, description: "Correo electrónico de contacto"),
        new OA\Property(property: "redesSociales", type: "string", nullable: true, description: "URL de redes sociales"),
        new OA\Property(property: "telefono", type: "string", nullable: true, description: "Número de teléfono de contacto"),
        new OA\Property(property: "servicio", type: "string", nullable: true, description: "Descripción de los servicios ofrecidos"),
        new OA\Property(property: "adicional", type: "string", nullable: true, description: "Información adicional del complejo"),
        new OA\Property(property: "imagen", type: "string", nullable: true, description: "URL de la imagen del complejo"),
        new OA\Property(property: "latitud", type: "number", format: "float", nullable: true, description: "Latitud del complejo"),
        new OA\Property(property: "longitud", type: "number", format: "float", nullable: true, description: "Longitud del complejo"),
        new OA\Property(property: "habilitado", type: "boolean", description: "Indica si el complejo está habilitado/visible", example: true),
        new OA\Property(property: "created_at", type: "string", format: "date-time", description: "Fecha de creación"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", description: "Fecha de última actualización")
    ]
)]
#[OA\Schema(
    schema: "ComplejoRequest",
    title: "ComplejoRequest",
    description: "Esquema para la creación de un complejo",
    required: ["nombre", "direccion"],
    properties: [
        new OA\Property(property: "nombre", type: "string", example: "Complejo Las Dunas"),
        new OA\Property(property: "direccion", type: "string", example: "Av. Costanera 1000"),
        new OA\Property(property: "mail", type: "string", format: "email", nullable: true, example: "info@lasdunas.com"),
        new OA\Property(property: "redesSociales", type: "string", nullable: true, example: "http://instagram.com/lasdunas"),
        new OA\Property(property: "telefono", type: "string", nullable: true, example: "2262123456"),
        new OA\Property(property: "servicio", type: "string", nullable: true, example: "Piscina, Gimnasio"),
        new OA\Property(property: "adicional", type: "string", nullable: true, example: "Estacionamiento"),
        new OA\Property(property: "imagen", type: "string", nullable: true, example: "http://imagen.com/complejo.jpg"),
        new OA\Property(property: "latitud", type: "number", format: "float", nullable: true, example: -38.555),
        new OA\Property(property: "longitud", type: "number", format: "float", nullable: true, example: -58.777),
        new OA\Property(property: "habilitado", type: "boolean", nullable: true, example: true)

    ]
)]
#[OA\Schema(
    schema: "ComplejoUpdateRequest",
    title: "ComplejoUpdateRequest",
    description: "Esquema para la actualización de un complejo",
    properties: [
        new OA\Property(property: "nombre", type: "string", nullable: true, example: "Complejo El Faro"),
        new OA\Property(property: "direccion", type: "string", nullable: true, example: "Av. 79 N° 1200"),
        new OA\Property(property: "mail", type: "string", format: "email", nullable: true, example: "contacto@elfaro.com"),
        new OA\Property(property: "redesSociales", type: "string", nullable: true, example: "http://facebook.com/elfaro"),
        new OA\Property(property: "telefono", type: "string", nullable: true, example: "2262987654"),
        new OA\Property(property: "servicio", type: "string", nullable: true, example: "Spa, gimnasio"),
        new OA\Property(property: "adicional", type: "string", nullable: true, example: "Eventos corporativos"),
        new OA\Property(property: "imagen", type: "string", nullable: true, example: "http://imagen.com/complejo_update.jpg"),
        new OA\Property(property: "latitud", type: "number", format: "float", nullable: true, example: -38.666),
        new OA\Property(property: "longitud", type: "number", format: "float", nullable: true, example: -58.888),
        new OA\Property(property: "habilitado", type: "boolean", nullable: true, example: false)
    ]
)]
class Complejo extends Model
{
    use HasFactory;

    protected $table = 'complejos';

    protected $fillable = [
        'nombre',
        'direccion',
        'mail',
        'redesSociales',
        'telefono',
        'servicio',
        'adicional',
        'imagen',
        'latitud',
        'longitud',
        'habilitado'
    ];

    protected $casts = [
        'latitud' => 'float',
        'longitud' => 'float',
    ];
    protected function habilitado(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            set: fn ($value) => filter_var($value, FILTER_VALIDATE_BOOLEAN)
        );
    }
}
