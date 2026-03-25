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
        'tiendaOnline',
        'extras',
        'horario',
        'tipo'
    ];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'gastronomico_menus', 'gastronomico_id', 'menu_id');
    }

    public function tipos()
    {
        return $this->belongsToMany(TipoGastronomico::class, 'gastronomico_tipo_gastronomico', 'gastronomico_id', 'tipo_gastronomico_id');
    }
}
