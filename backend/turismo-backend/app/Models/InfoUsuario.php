<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Tipo;

class InfoUsuario extends Model
{
    protected $table = 'info_usuarios';

    protected $fillable = [
        'ciudad',
        'edad',
        'estadia',
        'integrantes',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function intereses()
    {
        return $this->belongsToMany(Tipo::class, 'usuario_intereses', 'info_usuario_id', 'tipo_id');
    }
}
