<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarTelefono
{
    public function handle(Request $request, Closure $next)
    {
        // Si el usuario está logueado, NO tiene teléfono y NO está intentando acceder a las rutas de completar perfil
        if (auth()->check() && empty(auth()->user()->telefono) && !$request->routeIs('perfil.completar*')) {
            // Lo pateamos a la pantalla obligatoria
            return redirect()->route('perfil.completar');
        }

        return $next($request);
    }
}