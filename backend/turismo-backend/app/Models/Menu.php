<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Gastronomico;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Menu",
    title: "Menu",
    description: "Modelo de un tipo de menú",
    properties: [
        new OA\Property(property: "id", type: "integer", format: "int64", description: "ID único del menú"),
        new OA\Property(property: "tipo", type: "string", description: "Tipo de menú (ej. 'Desayuno', 'Almuerzo')"),
        new OA\Property(property: "created_at", type: "string", format: "date-time", description: "Fecha de creación"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", description: "Fecha de última actualización")
    ]
)]
#[OA\Schema(
    schema: "MenuRequest",
    title: "MenuRequest",
    description: "Esquema para la creación de un tipo de menú",
    required: ["tipo"],
    properties: [
        new OA\Property(property: "tipo", type: "string", example: "Desayuno")
    ]
)]
#[OA\Schema(
    schema: "MenuUpdateRequest",
    title: "MenuUpdateRequest",
    description: "Esquema para la actualización de un tipo de menú",
    properties: [
        new OA\Property(property: "tipo", type: "string", nullable: true, example: "Almuerzo")
    ]
)]
class Menu extends Model
{

    protected $fillable = ['tipo'];

    public function gastronomicos()
    {
        return $this->belongsToMany(Gastronomico::class, 'gastronomico_menus', 'menu_id', 'gastronomico_id');
    }
}
