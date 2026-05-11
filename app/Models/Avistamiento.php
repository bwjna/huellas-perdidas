<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avistamiento extends Model
{
    protected $fillable = [
        'descripcion',
        'direccion',
        'latitud',
        'longitud',
        'usuario_id',
        'publicacion_id',
    ];

    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}