<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tipo;

class Actividad extends Model
{
    protected $table = 'actividades';

    protected $fillable = [
        'nombre',
        'direccion',
        'descripcion',
        'redes_sociales',
        'web',
        'mail',
        'telefono',
        'imagen',
        'tipo_id',
        'dias_y_horarios'
    ];

    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'tipo_id');
    }
}
