<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "TipoAlojamiento",
    title: "TipoAlojamiento",
    description: "Modelo de un tipo de alojamiento",
    properties: [
        new OA\Property(property: "id", type: "integer", format: "int64", description: "ID único del tipo de alojamiento"),
        new OA\Property(property: "tipo", type: "string", description: "Nombre del tipo de alojamiento (ej. 'Hotel', 'Cabaña')"),
        new OA\Property(property: "created_at", type: "string", format: "date-time", description: "Fecha de creación"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", description: "Fecha de última actualización")
    ]
)]
#[OA\Schema(
    schema: "TipoAlojamientoRequest",
    title: "TipoAlojamientoRequest",
    description: "Esquema para la creación de un tipo de alojamiento",
    required: ["tipo"],
    properties: [
        new OA\Property(property: "tipo", type: "string", example: "Hotel")
    ]
)]
#[OA\Schema(
    schema: "TipoAlojamientoUpdateRequest",
    title: "TipoAlojamientoUpdateRequest",
    description: "Esquema para la actualización de un tipo de alojamiento",
    properties: [
        new OA\Property(property: "tipo", type: "string", example: "Cabaña")
    ]
)]
class TipoAlojamiento extends Model
{
    use HasFactory;

    protected $table = 'tipo_alojamientos';

    protected $fillable = ['tipo'];

    public function alojamientos()
    {
        return $this->belongsToMany(Alojamiento::class, 'alojamiento_tipo_alojamiento', 'tipo_alojamiento_id', 'alojamiento_id');
    }
}
