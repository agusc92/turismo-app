<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
