<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VistaPublicacion extends Model
{
    protected $table = 'vistas_publicacion';

    protected $fillable = [
        'publicacion_id',
        'ip',
    ];

    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class);
    }
}