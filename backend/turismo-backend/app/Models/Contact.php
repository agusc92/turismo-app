<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['Telefono', 'Whatsapp', 'Email', 'Web', 'Instagram', 'Facebook', 'Youtube', 'Tiktok', 'Direccion'];
}
