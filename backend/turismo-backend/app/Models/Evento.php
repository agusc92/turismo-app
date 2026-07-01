<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Evento",
    title: "Evento",
    description: "Modelo de un evento",
    properties: [
        new OA\Property(property: "id", type: "integer", format: "int64", description: "ID único del evento"),
        new OA\Property(property: "nombre", type: "string", description: "Nombre del evento"),
        new OA\Property(property: "direccion", type: "string", description: "Dirección del evento"),
        new OA\Property(property: "descripcion", type: "string", nullable: true, description: "Descripción del evento"),
        new OA\Property(property: "fecha", type: "string", format: "date", description: "Fecha del evento (YYYY-MM-DD)"),
        new OA\Property(property: "lugar", type: "string", description: "Lugar donde se realiza el evento"),
        new OA\Property(property: "imagen", type: "string", nullable: true, description: "URL de la imagen del evento"),
        new OA\Property(property: "destacado", type: "boolean", description: "Indica si el evento es destacado"),
        new OA\Property(property: "latitud", type: "number", format: "float", nullable: true, description: "Latitud del evento"),
        new OA\Property(property: "longitud", type: "number", format: "float", nullable: true, description: "Longitud del evento"),
        new OA\Property(property: "habilitado", type: "boolean", description: "Indica si el evento está habilitado/visible", example: true),
        new OA\Property(property: "created_at", type: "string", format: "date-time", description: "Fecha de creación"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", description: "Fecha de última actualización")
    ]
)]
#[OA\Schema(
    schema: "EventoRequest",
    title: "EventoRequest",
    description: "Esquema para la creación de un evento",
    required: ["nombre", "direccion", "fecha", "lugar"],
    properties: [
        new OA\Property(property: "nombre", type: "string", example: "Concierto de Verano"),
        new OA\Property(property: "direccion", type: "string", example: "Parque Miguel Lillo"),
        new OA\Property(property: "descripcion", type: "string", nullable: true, example: "Música en vivo al aire libre"),
        new OA\Property(property: "fecha", type: "string", format: "date", example: "2024-12-31"),
        new OA\Property(property: "lugar", type: "string", example: "Anfiteatro"),
        new OA\Property(property: "imagen", type: "string", nullable: true, example: "http://imagen.com/concierto.jpg"),
        new OA\Property(property: "destacado", type: "boolean", example: false),
        new OA\Property(property: "latitud", type: "number", format: "float", nullable: true, example: -38.555),
        new OA\Property(property: "longitud", type: "number", format: "float", nullable: true, example: -58.777),
        new OA\Property(property: "habilitado", type: "boolean", nullable: true, example: true)
    ]
)]
#[OA\Schema(
    schema: "EventoUpdateRequest",
    title: "EventoUpdateRequest",
    description: "Esquema para la actualización de un evento",
    properties: [
        new OA\Property(property: "nombre", type: "string", nullable: true, example: "Festival de Jazz"),
        new OA\Property(property: "direccion", type: "string", nullable: true, example: "Centro Cultural"),
        new OA\Property(property: "descripcion", type: "string", nullable: true, example: "Noche de jazz y blues"),
        new OA\Property(property: "fecha", type: "string", format: "date", nullable: true, example: "2025-01-15"),
        new OA\Property(property: "lugar", type: "string", nullable: true, example: "Teatro Municipal"),
        new OA\Property(property: "imagen", type: "string", nullable: true, example: "http://imagen.com/jazz.jpg"),
        new OA\Property(property: "destacado", type: "boolean", nullable: true, example: true),
        new OA\Property(property: "latitud", type: "number", format: "float", nullable: true, example: -38.666),
        new OA\Property(property: "longitud", type: "number", format: "float", nullable: true, example: -58.888),
        new OA\Property(property: "habilitado", type: "boolean", nullable: true, example: false)
    ]
)]
class Evento extends Model
{
    use HasFactory;

    protected $table = 'eventos';

    protected $fillable = [
        'nombre',
        'direccion',
        'descripcion',
        'fecha',
        'lugar',
        'imagen',
        'destacado',
        'latitud',
        'longitud',
        'habilitado'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'latitud' => 'float',
        'longitud' => 'float',
    ];
    protected function habilitado(): Attribute
    {
        return Attribute::make(
            get: fn($value) => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            set: fn($value) => filter_var($value, FILTER_VALIDATE_BOOLEAN)
        );
    }
    protected function destacado(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            set: fn ($value) => filter_var($value, FILTER_VALIDATE_BOOLEAN)
        );
    }
}
