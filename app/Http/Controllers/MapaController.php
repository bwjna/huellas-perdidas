<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avistamiento;
use Inertia\Inertia;

class MapaController extends Controller
{
    public function index()
    {
        $avistamientos = Avistamiento::with('publicacion')
            ->whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->get()
            ->map(function($a) {
                return [
                    'id'          => $a->id,
                    'latitud'     => $a->latitud,
                    'longitud'    => $a->longitud,
                    'descripcion' => $a->descripcion,
                    'direccion'   => $a->direccion,
                ];
            });

        return Inertia::render('Mapa', [
            'avistamientos' => $avistamientos
        ]);
    }
}