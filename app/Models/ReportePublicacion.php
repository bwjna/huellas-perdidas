<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportePublicacion extends Model
{
    protected $table = 'reportes_publicacion';

    protected $fillable = [
        'publicacion_id',
        'ip',
    ];

    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class);
    }
}