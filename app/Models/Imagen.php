<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    protected $table = 'imagenes';

    protected $fillable = [
        'publicacion_id',
        'url',
        'orden',
    ];

    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class);
    }
}