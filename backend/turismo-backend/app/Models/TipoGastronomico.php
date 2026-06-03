<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Gastronomico;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "TipoGastronomico",
    title: "TipoGastronomico",
    description: "Modelo de un tipo de gastronomía",
    properties: [
        new OA\Property(property: "id", type: "integer", format: "int64", description: "ID único del tipo de gastronomía"),
        new OA\Property(property: "tipo", type: "string", description: "Nombre del tipo de gastronomía (ej. 'Restaurante', 'Bar')"),
        new OA\Property(property: "created_at", type: "string", format: "date-time", description: "Fecha de creación"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", description: "Fecha de última actualización")
    ]
)]
#[OA\Schema(
    schema: "TipoGastronomicoRequest",
    title: "TipoGastronomicoRequest",
    description: "Esquema para la creación de un tipo de gastronomía",
    required: ["tipo"],
    properties: [
        new OA\Property(property: "tipo", type: "string", example: "Restaurante")
    ]
)]
#[OA\Schema(
    schema: "TipoGastronomicoUpdateRequest",
    title: "TipoGastronomicoUpdateRequest",
    description: "Esquema para la actualización de un tipo de gastronomía",
    properties: [
        new OA\Property(property: "tipo", type: "string", nullable: true, example: "Bar")
    ]
)]
class TipoGastronomico extends Model
{
    use HasFactory;

    protected $table = 'tipo_gastronomicos';

    protected $fillable = ['tipo'];

    public function gastronomicos()
    {
        return $this->belongsToMany(Gastronomico::class, 'gastronomico_tipo_gastronomico', 'tipo_gastronomico_id', 'gastronomico_id');
    }
}
