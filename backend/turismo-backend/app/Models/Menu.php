<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Gastronomico;

class Menu extends Model
{

    protected $fillable = ['tipo'];

    public function gastronomicos()
    {
        return $this->belongsToMany(Gastronomico::class, 'gastronomico_menus', 'menu_id', 'gastronomico_id');
    }
}
