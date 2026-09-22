<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Publicacion;
use App\Models\ReportePublicacion;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AdminController extends Controller
{
    private const LIMITE_REPORTES = 20;

    public function index()
    {
        $usuarios = Usuario::all();

        $cantidadReportes = Publicacion::whereNotNull('oculta_en')->count();

        return Inertia::render('PanelAdmin', [
            'usuarios' => $usuarios,
            'cantidadReportes' => $cantidadReportes,
        ]);
    }

    public function reportes()
    {
        $publicaciones = Publicacion::with('usuario:id,nombre,telefono')
            ->where('reportes', '>=', self::LIMITE_REPORTES)
            ->orderByDesc('oculta_en')
            ->get();

        return Inertia::render('PanelReportes', [
            'publicaciones' => $publicaciones,
            'limiteReportes' => self::LIMITE_REPORTES,
        ]);
    }

    public function eliminarReportada(Publicacion $publicacion)
    {
        DB::transaction(function () use ($publicacion) {
            $mascota = $publicacion->mascota;
            $publicacion->delete(); // cascada borra imagenes y reportes relacionados
            if ($mascota) {
                $mascota->delete();
            }
        });

        return back()->with('success', 'Publicación eliminada definitivamente.');
    }

    public function restaurarReportada(Publicacion $publicacion)
    {
        DB::transaction(function () use ($publicacion) {
            $publicacion->update([
                'oculta_en' => null,
                'reportes'  => 0,
            ]);

            // Reiniciamos también los reportes individuales, así puede volver
            // a acumular reportes genuinos desde cero si vuelve a haber un problema real.
            ReportePublicacion::where('publicacion_id', $publicacion->id)->delete();
        });

        return back()->with('success', 'Publicación restaurada. Vuelve a estar visible en el sitio.');
    }
}