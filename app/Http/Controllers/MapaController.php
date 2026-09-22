<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avistamiento;
use App\Models\Publicacion;
use Inertia\Inertia;

class MapaController extends Controller
{
    public function index()
    {
        $avistamientos = Avistamiento::with('publicacion')
            ->whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->latest()
            ->get()
            ->map(function ($a) {
                return [
                    'id'          => $a->id,
                    'latitud'     => $a->latitud,
                    'longitud'    => $a->longitud,
                    'descripcion' => $a->descripcion,
                    'direccion'   => $a->direccion,
                    'publicacion' => $a->publicacion ? [
                        'id'     => $a->publicacion->id,
                        'titulo' => $a->publicacion->titulo,
                        'imagen' => $a->publicacion->imagen,
                        'estado' => $a->publicacion->estado,
                    ] : null,
                ];
            });

        // Publicaciones que tienen su propia ubicación (lat/lng), para ponerlas
        // en el mapa con el ícono de hueso.
        $publicacionesConUbicacion = Publicacion::whereNotNull('lat')
            ->whereNotNull('lng')
            ->where('estado', '!=', 'resuelto')
            ->latest()
            ->get()
            ->map(function ($p) {
                return [
                    'id'     => $p->id,
                    'titulo' => $p->titulo,
                    'imagen' => $p->imagen,
                    'estado' => $p->estado,
                    'lat'    => $p->lat,
                    'lng'    => $p->lng,
                ];
            });

        $estadisticas = [
            'publicadasActualmente' => Publicacion::where('estado', '!=', 'resuelto')->count(),
            'publicadasHoy'         => Publicacion::whereDate('created_at', now()->toDateString())->count(),
        ];

        return Inertia::render('Mapa', [
            'avistamientos' => $avistamientos,
            'publicaciones' => $publicacionesConUbicacion,
            'estadisticas'  => $estadisticas,
        ]);
    }
}