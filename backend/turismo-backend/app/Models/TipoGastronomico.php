<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Gastronomico;

class TipoGastronomico extends Model
{
    protected $table = 'tipo_gastronomicos';

    protected $fillable = ['tipo'];

    public function gastronomicos()
    {
        return $this->belongsToMany(Gastronomico::class, 'gastronomico_tipo_gastronomico', 'tipo_gastronomico_id', 'gastronomico_id');
    }
}
