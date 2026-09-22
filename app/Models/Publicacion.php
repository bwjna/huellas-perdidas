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
        'lat',
        'lng',
        'descripcion',
        'usuario_id',
        'mascota_id',
        'fecha_encontrada',
        'ubicacion_encontrada',
        'contacto',
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

    public function vistasRegistradas()
    {
    return $this->hasMany(VistaPublicacion::class);
    }

    public function imagenes()
    {
        return $this->hasMany(Imagen::class)->orderBy('orden');
    }

}