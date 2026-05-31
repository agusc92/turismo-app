<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tipo;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Actividad",
    title: "Actividad",
    description: "Modelo de una actividad",
    properties: [
        new OA\Property(property: "id", type: "integer", format: "int64", description: "ID único de la actividad"),
        new OA\Property(property: "nombre", type: "string", description: "Nombre de la actividad"),
        new OA\Property(property: "direccion", type: "string", description: "Dirección de la actividad"),
        new OA\Property(property: "descripcion", type: "string", nullable: true, description: "Descripción de la actividad"),
        new OA\Property(property: "redes_sociales", type: "string", nullable: true, description: "URL de redes sociales"),
        new OA\Property(property: "web", type: "string", nullable: true, description: "Sitio web de la actividad"),
        new OA\Property(property: "mail", type: "string", format: "email", nullable: true, description: "Correo electrónico de contacto"),
        new OA\Property(property: "telefono", type: "string", nullable: true, description: "Número de teléfono de contacto"),
        new OA\Property(property: "imagen", type: "string", nullable: true, description: "URL de la imagen de la actividad"),
        new OA\Property(property: "tipo_id", type: "integer", format: "int64", description: "ID del tipo de actividad"),
        new OA\Property(property: "dias_y_horarios", type: "string", nullable: true, description: "Días y horarios de la actividad"),
        new OA\Property(property: "created_at", type: "string", format: "date-time", description: "Fecha de creación"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", description: "Fecha de última actualización"),
        new OA\Property(property: "tipo", ref: "#/components/schemas/Tipo", description: "Objeto Tipo asociado a la actividad")
    ]
)]
#[OA\Schema(
    schema: "ActividadRequest",
    title: "ActividadRequest",
    description: "Esquema para la creación de una actividad",
    required: ["nombre", "direccion", "tipo_id"],
    properties: [
        new OA\Property(property: "nombre", type: "string", example: "Clases de Surf"),
        new OA\Property(property: "direccion", type: "string", example: "Playa Grande"),
        new OA\Property(property: "descripcion", type: "string", nullable: true, example: "Clases para todos los niveles"),
        new OA\Property(property: "redes_sociales", type: "string", nullable: true, example: "http://instagram.com/surf"),
        new OA\Property(property: "web", type: "string", nullable: true, example: "http://surfnecochea.com"),
        new OA\Property(property: "mail", type: "string", format: "email", nullable: true, example: "info@surfnecochea.com"),
        new OA\Property(property: "telefono", type: "string", nullable: true, example: "2262123456"),
        new OA\Property(property: "imagen", type: "string", nullable: true, example: "http://imagen.com/surf.jpg"),
        new OA\Property(property: "tipo_id", type: "integer", example: 1),
        new OA\Property(property: "dias_y_horarios", type: "string", nullable: true, example: "L-V 10:00-18:00")
    ]
)]
#[OA\Schema(
    schema: "ActividadUpdateRequest",
    title: "ActividadUpdateRequest",
    description: "Esquema para la actualización de una actividad",
    properties: [
        new OA\Property(property: "nombre", type: "string", nullable: true, example: "Clases de Kitesurf"),
        new OA\Property(property: "direccion", type: "string", nullable: true, example: "Playa del Medio"),
        new OA\Property(property: "descripcion", type: "string", nullable: true, example: "Clases avanzadas"),
        new OA\Property(property: "redes_sociales", type: "string", nullable: true, example: "http://instagram.com/kitesurf"),
        new OA\Property(property: "web", type: "string", nullable: true, example: "http://kitesurfnecochea.com"),
        new OA\Property(property: "mail", type: "string", format: "email", nullable: true, example: "contacto@kitesurfnecochea.com"),
        new OA\Property(property: "telefono", type: "string", nullable: true, example: "2262987654"),
        new OA\Property(property: "imagen", type: "string", nullable: true, example: "http://imagen.com/kitesurf.jpg"),
        new OA\Property(property: "tipo_id", type: "integer", nullable: true, example: 2),
        new OA\Property(property: "dias_y_horarios", type: "string", nullable: true, example: "S-D 11:00-19:00")
    ]
)]
class Actividad extends Model
{
    protected $table = 'actividades';

    protected $fillable = [
        'nombre',
        'direccion',
        'descripcion',
        'redes_sociales',
        'web',
        'mail',
        'telefono',
        'imagen',
        'tipo_id',
        'dias_y_horarios'
    ];

    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'tipo_id');
    }
}
