<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;
use App\Models\TipoGastronomico;

class Gastronomico extends Model
{
    protected $table = 'gastronomicos';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'redesSociales',
        'horario',
        'tiendaOnline',
        'extras',
        'imagen'
    ];

    protected $appends = ['tipo', 'menu'];

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
