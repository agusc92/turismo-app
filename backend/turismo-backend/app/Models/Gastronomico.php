<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;

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
}
