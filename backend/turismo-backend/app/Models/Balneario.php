<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Balneario",
    title: "Balneario",
    description: "Modelo de un balneario",
    properties: [
        new OA\Property(property: "id", type: "integer", format: "int64", description: "ID único del balneario"),
        new OA\Property(property: "nombre", type: "string", description: "Nombre del balneario"),
        new OA\Property(property: "direccion", type: "string", description: "Dirección del balneario"),
        new OA\Property(property: "telefono", type: "string", nullable: true, description: "Número de teléfono"),
        new OA\Property(property: "redesSociales", type: "string", nullable: true, description: "URL de redes sociales"),
        new OA\Property(property: "servicios", type: "string", nullable: true, description: "Servicios ofrecidos"),
        new OA\Property(property: "mail", type: "string", format: "email", nullable: true, description: "Correo electrónico de contacto"),
        new OA\Property(property: "accesibilidad", type: "string", nullable: true, description: "Información de accesibilidad"),
        new OA\Property(property: "fecha_desde_hasta", type: "string", nullable: true, description: "Fechas de operación (ej. 'Diciembre a Marzo')"),
        new OA\Property(property: "imagen", type: "string", nullable: true, description: "URL de la imagen del balneario"),
        new OA\Property(property: "created_at", type: "string", format: "date-time", description: "Fecha de creación"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", description: "Fecha de última actualización")
    ]
)]
#[OA\Schema(
    schema: "BalnearioRequest",
    title: "BalnearioRequest",
    description: "Esquema para la creación de un balneario",
    required: ["nombre", "direccion"],
    properties: [
        new OA\Property(property: "nombre", type: "string", example: "Balneario Neptuno"),
        new OA\Property(property: "direccion", type: "string", example: "Av. 2 y Calle 87"),
        new OA\Property(property: "telefono", type: "string", nullable: true, example: "2262112233"),
        new OA\Property(property: "redesSociales", type: "string", nullable: true, example: "http://instagram.com/neptuno"),
        new OA\Property(property: "servicios", type: "string", nullable: true, example: "Carpa, sombrillas, restaurante"),
        new OA\Property(property: "mail", type: "string", format: "email", nullable: true, example: "info@neptuno.com"),
        new OA\Property(property: "accesibilidad", type: "string", nullable: true, example: "Rampas de acceso"),
        new OA\Property(property: "fecha_desde_hasta", type: "string", nullable: true, example: "Diciembre a Marzo"),
        new OA\Property(property: "imagen", type: "string", nullable: true, example: "http://imagen.com/neptuno.jpg")
    ]
)]
#[OA\Schema(
    schema: "BalnearioUpdateRequest",
    title: "BalnearioUpdateRequest",
    description: "Esquema para la actualización de un balneario",
    properties: [
        new OA\Property(property: "nombre", type: "string", nullable: true, example: "Balneario Poseidon"),
        new OA\Property(property: "direccion", type: "string", nullable: true, example: "Av. 2 y Calle 90"),
        new OA\Property(property: "telefono", type: "string", nullable: true, example: "2262445566"),
        new OA\Property(property: "redesSociales", type: "string", nullable: true, example: "http://facebook.com/poseidon"),
        new OA\Property(property: "servicios", type: "string", nullable: true, example: "Pileta, spa"),
        new OA\Property(property: "mail", type: "string", format: "email", nullable: true, example: "contacto@poseidon.com"),
        new OA\Property(property: "accesibilidad", type: "string", nullable: true, example: "Sillas anfibias"),
        new OA\Property(property: "fecha_desde_hasta", type: "string", nullable: true, example: "Noviembre a Abril"),
        new OA\Property(property: "imagen", type: "string", nullable: true, example: "http://imagen.com/poseidon.jpg")
    ]
)]
class Balneario extends Model
{
    protected $table = 'balnearios';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'redesSociales',
        'servicios',
        'mail',
        'accesibilidad',
        'fecha_desde_hasta',
        'imagen'
    ];
}
