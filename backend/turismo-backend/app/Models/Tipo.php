<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Actividad;
use App\Models\InfoUsuario;
use OpenApi\Attributes as OA; // Añadimos esta línea para usar atributos

#[OA\Schema(
    schema: "Tipo",
    title: "Tipo",
    description: "Modelo de un tipo genérico",
    properties: [
        new OA\Property(property: "id", type: "integer", format: "int64", description: "ID único del tipo"),
        new OA\Property(property: "tipo", type: "string", description: "Nombre del tipo (ej. 'Aventura', 'Cultural')"),
        new OA\Property(property: "created_at", type: "string", format: "date-time", description: "Fecha de creación"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", description: "Fecha de última actualización")
    ]
)]
#[OA\Schema(
    schema: "TipoRequest",
    title: "TipoRequest",
    description: "Esquema para la creación de un tipo genérico",
    required: ["tipo"],
    properties: [
        new OA\Property(property: "tipo", type: "string", example: "Aventura")
    ]
)]
#[OA\Schema(
    schema: "TipoUpdateRequest",
    title: "TipoUpdateRequest",
    description: "Esquema para la actualización de un tipo genérico",
    properties: [
        new OA\Property(property: "tipo", type: "string", example: "Cultural")
    ]
)]
class Tipo extends Model
{

    protected $fillable = ['tipo'];

    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'tipo_id');
    }

    public function usuarios()
    {
        return $this->belongsToMany(InfoUsuario::class, 'usuario_intereses', 'tipo_id', 'info_usuario_id');
    }
}
