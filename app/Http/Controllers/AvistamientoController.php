<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avistamiento;
use App\Models\Publicacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class AvistamientoController extends Controller
{
    // Anti-spam: máximo de reportes por IP en la ventana de tiempo definida abajo.
    private const LIMITE_REPORTES        = 5;
    private const VENTANA_MINUTOS        = 60;

    public function store(Request $request, Publicacion $publicacion)
    {
        $clave = 'avistamientos:' . $request->ip();

        if (RateLimiter::tooManyAttempts($clave, self::LIMITE_REPORTES)) {
            $segundosRestantes = RateLimiter::availableIn($clave);
            $minutosRestantes  = (int) ceil($segundosRestantes / 60);

            return back()->withErrors([
                'general' => "Reportaste demasiados avistamientos en poco tiempo. "
                    . "Probá de nuevo en {$minutosRestantes} minuto" . ($minutosRestantes === 1 ? '' : 's') . ".",
            ]);
        }

        $request->validate([
            'latitud'     => 'required|numeric|between:-90,90',
            'longitud'    => 'required|numeric|between:-180,180',
            'descripcion' => 'nullable|string|max:500',
            'direccion'   => 'nullable|string|max:200',
        ]);

        $dentroDeZona = $request->latitud  >= config('zonamapa.sur')
            && $request->latitud  <= config('zonamapa.norte')
            && $request->longitud >= config('zonamapa.oeste')
            && $request->longitud <= config('zonamapa.este');

        if (!$dentroDeZona) {
            return back()->withErrors([
                'latitud' => 'La ubicación tiene que estar dentro de la zona habilitada (Saladillo).',
            ]);
        }

        // Recién contamos el intento una vez pasadas las validaciones anteriores,
        // para no gastarle el cupo a alguien por un error de tipeo o de zona.
        RateLimiter::hit($clave, self::VENTANA_MINUTOS * 60);

        Avistamiento::create([
            'publicacion_id' => $publicacion->id,
            'usuario_id'     => Auth::id(), // null si no está logueado, no pasa nada
            'latitud'        => $request->latitud,
            'longitud'       => $request->longitud,
            'descripcion'    => $request->descripcion,
            'direccion'      => $request->direccion,
        ]);

        return back()->with('success', '¡Gracias! Tu reporte fue registrado.');
    }
}