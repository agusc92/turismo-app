<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alojamiento extends Model
{
    protected $table = 'alojamientos';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'redesSociales',
        'paginaWeb',
        'mail',
        'mascotas',
        'periodoApertura',
        'tipo'
    ];
}
