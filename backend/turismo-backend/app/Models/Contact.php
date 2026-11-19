<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'telefono',
        'whatsapp',
        'email',
        'web',
        'instagram',
        'facebook',
        'youtube',
        'tiktok',
        'direccion',
    ];
}
