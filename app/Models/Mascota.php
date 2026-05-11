<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    protected $fillable = [
        'nombre',
        'edad',
        'color',
        'tamano',
        'especie',
        'raza',
        'usuario_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class);
    }
}