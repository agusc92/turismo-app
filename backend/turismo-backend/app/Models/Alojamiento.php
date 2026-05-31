<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        new OA\Property(property: "periodoApertura", type: "string", nullable: true, description: "Período de apertura (ej. 'Todo el año', 'Temporada alta')"),
        new OA\Property(property: "tipo", type: "string", description: "Tipo de alojamiento (ej. 'Hotel', 'Cabaña')"),
        new OA\Property(property: "created_at", type: "string", format: "date-time", description: "Fecha de creación"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", description: "Fecha de última actualización")
    ]
)]
#[OA\Schema(
    schema: "AlojamientoRequest",
    title: "AlojamientoRequest",
    description: "Esquema para la creación de un alojamiento",
    required: ["nombre", "direccion", "mascotas", "tipo"],
    properties: [
        new OA\Property(property: "nombre", type: "string", example: "Hotel Central"),
        new OA\Property(property: "direccion", type: "string", example: "Calle 10 N° 500"),
        new OA\Property(property: "telefono", type: "string", nullable: true, example: "2262554433"),
        new OA\Property(property: "redesSociales", type: "string", nullable: true, example: "http://instagram.com/hotelcentral"),
        new OA\Property(property: "paginaWeb", type: "string", nullable: true, example: "http://hotelcentral.com"),
        new OA\Property(property: "mail", type: "string", format: "email", nullable: true, example: "reservas@hotelcentral.com"),
        new OA\Property(property: "mascotas", type: "boolean", example: false),
        new OA\Property(property: "periodoApertura", type: "string", nullable: true, example: "Todo el año"),
        new OA\Property(property: "tipo", type: "string", example: "Hotel")
    ]
)]
#[OA\Schema(
    schema: "AlojamientoUpdateRequest",
    title: "AlojamientoUpdateRequest",
    description: "Esquema para la actualización de un alojamiento",
    properties: [
        new OA\Property(property: "nombre", type: "string", nullable: true, example: "Hotel del Parque"),
        new OA\Property(property: "direccion", type: "string", nullable: true, example: "Av. 79 N° 1200"),
        new OA\Property(property: "telefono", type: "string", nullable: true, example: "2262778899"),
        new OA\Property(property: "redesSociales", type: "string", nullable: true, example: "http://facebook.com/hoteldelparque"),
        new OA\Property(property: "paginaWeb", type: "string", nullable: true, example: "http://hoteldelparque.com"),
        new OA\Property(property: "mail", type: "string", format: "email", nullable: true, example: "info@hoteldelparque.com"),
        new OA\Property(property: "mascotas", type: "boolean", nullable: true, example: true),
        new OA\Property(property: "periodoApertura", type: "string", nullable: true, example: "Temporada alta"),
        new OA\Property(property: "tipo", type: "string", nullable: true, example: "Cabaña")
    ]
)]
class Alojamiento extends Model
{
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
        'tipo'
    ];
}
