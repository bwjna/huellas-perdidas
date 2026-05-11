<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Mascota;
use App\Models\Publicacion;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PublicacionController extends Controller
{
    public function index()
    {
        $publicaciones = Publicacion::with('mascota')->latest()->get();
        return Inertia::render('Publicaciones/Index', [
            'publicaciones' => $publicaciones
        ]);
    }

    public function create()
    {
        return Inertia::render('Publicaciones/Crear');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:50',
            'especie'     => 'required|string|max:50',
            'raza'        => 'nullable|string|max:50',
            'color'       => 'nullable|string|max:50',
            'tamano'      => 'nullable|in:chico,mediano,grande',
            'edad'        => 'nullable|integer',
            'titulo'      => 'required|string|max:100',
            'zona'        => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'fecha_evento'=> 'nullable|date',
            'imagen'      => 'nullable|image|max:2048',
        ]);

        $mascota = Mascota::create([
            'nombre'     => $request->nombre,
            'especie'    => $request->especie,
            'raza'       => $request->raza,
            'color'      => $request->color,
            'tamano'     => $request->tamano,
            'edad'       => $request->edad,
            'usuario_id' => Auth::id(),
        ]);

       $imagenPath = null;
if ($request->hasFile('imagen')) {
    $nombreArchivo = uniqid() . '.webp';
    $ruta = storage_path('app/public/publicaciones/' . $nombreArchivo);

    if (!file_exists(storage_path('app/public/publicaciones'))) {
        mkdir(storage_path('app/public/publicaciones'), 0755, true);
    }

    $manager = new ImageManager(new Driver());
    $manager->read($request->file('imagen')->getRealPath())
        ->toWebp(80)
        ->save($ruta);

    $imagenPath = 'publicaciones/' . $nombreArchivo;
}

        Publicacion::create([
            'titulo'       => $request->titulo,
            'zona'         => $request->zona,
            'descripcion'  => $request->descripcion,
            'fecha_evento' => $request->fecha_evento,
            'imagen'       => $imagenPath,
            'estado'       => 'perdido',
            'usuario_id'   => Auth::id(),
            'mascota_id'   => $mascota->id,
        ]);

        return redirect('/publicaciones')->with('success', '¡Publicación creada exitosamente!');
    }
}