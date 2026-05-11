<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model
{
    protected $table = 'publicaciones';

    protected $fillable = [
        'titulo',
        'imagen',
        'estado',
        'fecha_evento',
        'zona',
        'descripcion',
        'usuario_id',
        'mascota_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function mascota()
    {
        return $this->belongsTo(Mascota::class);
    }

    public function avistamientos()
    {
        return $this->hasMany(Avistamiento::class);
    }
}