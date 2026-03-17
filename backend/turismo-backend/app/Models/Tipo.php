<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Actividad;
use App\Models\InfoUsuario;

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
