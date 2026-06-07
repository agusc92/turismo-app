<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;
use App\Models\TipoGastronomico;
use OpenApi\Attributes as OA;
use Illuminate\Database\Eloquent\Factories\HasFactory;
#[OA\Schema(
    schema: "Gastronomico",
    title: "Gastronomico",
    description: "Modelo de un establecimiento gastronómico",
    properties: [
        new OA\Property(property: "id", type: "integer", format: "int64", description: "ID único del establecimiento"),
        new OA\Property(property: "nombre", type: "string", description: "Nombre del establecimiento"),
        new OA\Property(property: "direccion", type: "string", description: "Dirección del establecimiento"),
        new OA\Property(property: "telefono", type: "string", nullable: true, description: "Número de teléfono"),
        new OA\Property(property: "redesSociales", type: "string", nullable: true, description: "URL de redes sociales"),
        new OA\Property(property: "horario", type: "string", nullable: true, description: "Horario de atención"),
        new OA\Property(property: "tiendaOnline", type: "string", nullable: true, description: "URL de la tienda online"),
        new OA\Property(property: "extras", type: "string", nullable: true, description: "Información extra"),
        new OA\Property(property: "imagen", type: "string", nullable: true, description: "URL de la imagen principal"),
        new OA\Property(property: "latitud", type: "number", format: "float", nullable: true, description: "Latitud del establecimiento"),
        new OA\Property(property: "longitud", type: "number", format: "float", nullable: true, description: "Longitud del establecimiento"),
        new OA\Property(property: "created_at", type: "string", format: "date-time", description: "Fecha de creación"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", description: "Fecha de última actualización"),
        new OA\Property(property: "tipo", type: "array", items: new OA\Items(type: "string"), description: "Tipos de gastronomía asociados (solo nombres)"),
        new OA\Property(property: "menu", type: "array", items: new OA\Items(type: "string"), description: "Menús especiales asociados (solo nombres)"),
        new OA\Property(property: "menus", type: "array", items: new OA\Items(ref: "#/components/schemas/Menu"), description: "Lista completa de objetos de menú asociados"),
        new OA\Property(property: "tipos", type: "array", items: new OA\Items(ref: "#/components/schemas/TipoGastronomico"), description: "Lista completa de objetos de tipo gastronómico asociados")
    ]
)]
#[OA\Schema(
    schema: "GastronomicoRequest",
    title: "GastronomicoRequest",
    description: "Esquema para la creación de un establecimiento gastronómico",
    required: ["nombre", "direccion"],
    properties: [
        new OA\Property(property: "nombre", type: "string", example: "Restaurante Nuevo"),
        new OA\Property(property: "direccion", type: "string", example: "Av. Principal 789"),
        new OA\Property(property: "telefono", type: "string", nullable: true, example: "1122334455"),
        new OA\Property(property: "redesSociales", type: "string", nullable: true, example: "http://instagram.com/nuevo"),
        new OA\Property(property: "tiendaOnline", type: "string", nullable: true, example: "http://nuevo.com/tienda"),
        new OA\Property(property: "extras", type: "string", nullable: true, example: "Zona de juegos para niños"),
        new OA\Property(property: "horario", type: "string", nullable: true, example: "L-V 08:00-20:00"),
        new OA\Property(property: "imagen", type: "string", nullable: true, example: "http://imagen.com/nuevo.jpg"),
        new OA\Property(property: "latitud", type: "number", format: "float", nullable: true, example: -38.555),
        new OA\Property(property: "longitud", type: "number", format: "float", nullable: true, example: -58.777),
        new OA\Property(property: "tipo_ids", type: "array", items: new OA\Items(type: "integer"), nullable: true, description: "IDs de los tipos de gastronomía a asociar", example: [1, 3]),
        new OA\Property(property: "menu_ids", type: "array", items: new OA\Items(type: "integer"), nullable: true, description: "IDs de los menús a asociar", example: [2])
    ]
)]
#[OA\Schema(
    schema: "GastronomicoUpdateRequest",
    title: "GastronomicoUpdateRequest",
    description: "Esquema para la actualización de un establecimiento gastronómico",
    properties: [
        new OA\Property(property: "nombre", type: "string", nullable: true, example: "Restaurante Actualizado"),
        new OA\Property(property: "direccion", type: "string", nullable: true, example: "Nueva Dirección 456"),
        new OA\Property(property: "telefono", type: "string", nullable: true, example: "987654321"),
        new OA\Property(property: "redesSociales", type: "string", nullable: true, example: "http://facebook.com/ejemplo-actualizado"),
        new OA\Property(property: "tiendaOnline", type: "string", nullable: true, example: "http://tienda.com/ejemplo-actualizado"),
        new OA\Property(property: "extras", type: "string", nullable: true, example: "Terraza exterior"),
        new OA\Property(property: "horario", type: "string", nullable: true, example: "M-S 10:00-23:00"),
        new OA\Property(property: "imagen", type: "string", nullable: true, example: "http://imagen.com/ejemplo-actualizado.jpg"),
        new OA\Property(property: "latitud", type: "number", format: "float", nullable: true, example: -38.666),
        new OA\Property(property: "longitud", type: "number", format: "float", nullable: true, example: -58.888),
        new OA\Property(property: "tipo_ids", type: "array", items: new OA\Items(type: "integer"), nullable: true, description: "IDs de los tipos de gastronomía a sincronizar", example: [1]),
        new OA\Property(property: "menu_ids", type: "array", items: new OA\Items(type: "integer"), nullable: true, description: "IDs de los menús a sincronizar", example: [2])
    ]
)]
class Gastronomico extends Model
{
    use HasFactory;
    protected $table = 'gastronomicos';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'redesSociales',
        'horario',
        'tiendaOnline',
        'extras',
        'imagen',
        'latitud',
        'longitud'
    ];

    protected $appends = ['tipo', 'menu'];

    protected $casts = [
        'latitud' => 'float',
        'longitud' => 'float',
    ];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'gastronomico_menus', 'gastronomico_id', 'menu_id');
    }

    public function tipos()
    {
        return $this->belongsToMany(TipoGastronomico::class, 'gastronomico_tipo_gastronomico', 'gastronomico_id', 'tipo_gastronomico_id');
    }

    /**
     * Accesor para obtener los tipos de gastronomía como un array de strings.
     */
    public function getTipoAttribute()
    {
        if (!$this->relationLoaded('tipos')) {
            $this->load('tipos');
        }
        return $this->tipos->pluck('tipo')->toArray();
    }

    /**
     * Accesor para obtener los menús como un array de strings.
     */
    public function getMenuAttribute()
    {
        if (!$this->relationLoaded('menus')) {
            $this->load('menus');
        }
        return $this->menus->pluck('tipo')->toArray();
    }
}
