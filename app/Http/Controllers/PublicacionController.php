<?php

namespace App\Http\Controllers;

use App\Models\VistaPublicacion;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Mascota;
use App\Models\Publicacion;
use App\Models\ReportePublicacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PublicacionController extends Controller
{
    public function index()
    {
        // Listado de mascotas PERDIDAS (excluye encontradas, resueltas, ocultas por reportes,
        // y las que se publicaron desde el mapa — esas quedan solo ahí, como el hueso).
        // Solo traemos id/nombre/telefono del dueño, nunca el resto de sus datos (email, password, etc.)
        $publicaciones = Publicacion::with(['mascota', 'usuario:id,nombre,telefono'])
            ->where('estado', 'perdido')
            ->whereNull('oculta_en')
            ->whereNull('lat')
            ->latest()
            ->get();

        return Inertia::render('Publicaciones/Perdidas', [
            'publicaciones' => $publicaciones,
        ]);
    }

    public function indexEncontradas()
    {
        // Listado de mascotas ENCONTRADAS (mismas exclusiones que index()).
        $publicaciones = Publicacion::with(['mascota', 'usuario:id,nombre,telefono'])
            ->where('estado', 'encontrado')
            ->whereNull('oculta_en')
            ->whereNull('lat')
            ->latest()
            ->get();

        return Inertia::render('Publicaciones/Encontradas', [
            'publicaciones' => $publicaciones,
        ]);
    }

    public function create()
    {
        return Inertia::render('Publicaciones/Crear');
    }

    public function show(Publicacion $publicacion)
    {
        $this->registrarVista($publicacion);

        $publicacion->load('mascota', 'imagenes', 'usuario:id,nombre,telefono', 'avistamientos');

        return Inertia::render('Publicaciones/Show', [
            'publicacion' => $publicacion,
        ]);
    }

    // Página independiente (NO Inertia, sin navbar) solo con el cartel para imprimir.
    // Al ser una página aparte, no hay nada más en la hoja que se pueda "colar"
    // a una segunda página al imprimir.
    public function cartel(Publicacion $publicacion)
    {
        $publicacion->load('mascota', 'usuario:id,nombre,telefono');

        // Misma optimización de imagen que usamos en el resto del sitio (WebP/AVIF + calidad automática)
        $imagen = $publicacion->imagen;
        if ($imagen && str_contains($imagen, '/upload/')) {
            $imagen = str_replace('/upload/', '/upload/w_900,h_900,c_fill,q_auto,f_auto/', $imagen);
        }

        return view('cartel', [
            'publicacion'    => $publicacion,
            'imagen'         => $imagen,
            'urlPublicacion' => url("/publicaciones/{$publicacion->id}"),
        ]);
    }

    public function store(Request $request)
    {
        $esEncontrado    = $request->input('estado') === 'encontrado';
        $vieneDelMapa    = $request->filled('lat') && $request->filled('lng');
        $nombreOpcional  = $esEncontrado || $vieneDelMapa;

        $request->validate([
            'estado'       => ['required', Rule::in(['perdido', 'encontrado'])],
            'nombre'       => $nombreOpcional ? 'nullable|string|max:50' : 'required|string|max:50',
            'especie'      => 'required|string|max:50',
            'raza'         => 'nullable|string|max:50',
            'color'        => 'nullable|string|max:50',
            'tamano'       => 'nullable|in:pequeño,mediano,grande',
            'sexo'         => 'nullable|in:macho,hembra,desconocido',
            'titulo'       => 'required|string|max:100',
            'descripcion'  => 'nullable|string',
            'fecha_evento' => 'nullable|date',
            'zona'         => 'nullable|string|max:100',
            'lat'          => 'nullable|numeric|between:-90,90',
            'lng'          => 'nullable|numeric|between:-180,180',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'imagenes'     => 'nullable|array',
            'imagenes.*'   => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Si el usuario seleccionó "encontrado", exigimos los datos específicos de ese caso.
        if ($esEncontrado) {
            $request->validate([
                'fecha_encontrada'     => 'required|date',
                'ubicacion_encontrada' => 'required|string|max:200',
                'contacto'             => 'required|string|max:200',
            ]);
        }

        $imagenPath = null;

        if ($request->hasFile('imagen')) {
            try {
                $path = $request->file('imagen')->store('mascotas', 'cloudinary');
                $imagenPath = Storage::disk('cloudinary')->url($path);
            } catch (\Exception $e) {
                \Log::error('Error subiendo a Cloudinary: ' . $e->getMessage());
                $imagenPath = null;
            }
        }

        $imagenesExtra = [];

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $foto) {
                try {
                    $path = $foto->store('mascotas', 'cloudinary');
                    $imagenesExtra[] = Storage::disk('cloudinary')->url($path);
                } catch (\Exception $e) {
                    \Log::error('Error subiendo foto extra a Cloudinary: ' . $e->getMessage());
                }
            }
        }

        try {
            DB::transaction(function () use ($request, $esEncontrado, $imagenPath, $imagenesExtra) {
                $mascota = Mascota::create([
                    'nombre'     => $request->nombre,
                    'especie'    => $request->especie,
                    'raza'       => $request->raza,
                    'color'      => $request->color,
                    'tamano'     => $request->tamano,
                    'sexo'       => $request->sexo,
                    'usuario_id' => Auth::id(),
                ]);

                $datosPublicacion = [
                    'titulo'       => $request->titulo,
                    'descripcion'  => $request->descripcion,
                    'fecha_evento' => $request->fecha_evento,
                    'zona'         => $request->zona,
                    'lat'          => $this->latDentroDeZona($request->lat, $request->lng) ? $request->lat : null,
                    'lng'          => $this->latDentroDeZona($request->lat, $request->lng) ? $request->lng : null,
                    'imagen'       => $imagenPath,
                    'estado'       => $request->estado,
                    'usuario_id'   => Auth::id(),
                    'mascota_id'   => $mascota->id,
                ];

                if ($esEncontrado) {
                    $datosPublicacion['fecha_encontrada']     = $request->fecha_encontrada;
                    $datosPublicacion['ubicacion_encontrada'] = $request->ubicacion_encontrada;
                    $datosPublicacion['contacto']             = $request->contacto;
                }

                $publicacion = Publicacion::create($datosPublicacion);

                foreach ($imagenesExtra as $orden => $url) {
                    $publicacion->imagenes()->create([
                        'url'   => $url,
                        'orden' => $orden,
                    ]);
                }
            });
        } catch (\Exception $e) {
            \Log::error('Error guardando publicación: ' . $e->getMessage());
            return back()->withErrors([
                'general' => 'Hubo un error al guardar la publicación. Intentá de nuevo.',
            ]);
        }

        return redirect()->route('publicaciones.index')
            ->with('success', '¡Publicación creada exitosamente!');
    }

    public function misPublicaciones()
    {
        $publicaciones = Publicacion::with('mascota')
            ->where('usuario_id', Auth::id())
            ->latest()
            ->get();

        return Inertia::render('Publicaciones/Mias', [
            'publicaciones' => $publicaciones,
        ]);
    }

    public function edit(Publicacion $publicacion)
    {
        if ($publicacion->usuario_id !== Auth::id()) {
            abort(403);
        }

        $publicacion->load('mascota', 'imagenes');

        return Inertia::render('Publicaciones/Editar', [
            'publicacion' => $publicacion,
        ]);
    }

    public function update(Request $request, Publicacion $publicacion)
    {
        if ($publicacion->usuario_id !== Auth::id()) {
            abort(403);
        }

        $esEncontrado = $request->input('estado') === 'encontrado';

        $request->validate([
            'estado'       => ['required', Rule::in(['perdido', 'encontrado', 'resuelto'])],
            'nombre'       => $esEncontrado ? 'nullable|string|max:50' : 'required|string|max:50',
            'especie'      => 'required|string|max:50',
            'raza'         => 'nullable|string|max:50',
            'color'        => 'nullable|string|max:50',
            'tamano'       => 'nullable|in:pequeño,mediano,grande',
            'sexo'         => 'nullable|in:macho,hembra,desconocido',
            'titulo'       => 'required|string|max:100',
            'descripcion'  => 'nullable|string',
            'fecha_evento' => 'nullable|date',
            'zona'         => 'nullable|string|max:100',
            'lat'          => 'nullable|numeric|between:-90,90',
            'lng'          => 'nullable|numeric|between:-180,180',
            'imagen'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'imagenes'   => 'nullable|array',
            'imagenes.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        if ($esEncontrado) {
            $request->validate([
                'fecha_encontrada'     => 'required|date',
                'ubicacion_encontrada' => 'required|string|max:200',
                'contacto'             => 'required|string|max:200',
            ]);
        }

        $imagenPath = $publicacion->imagen;

        if ($request->hasFile('imagen')) {
            try {
                $path = $request->file('imagen')->store('mascotas', 'cloudinary');
                $imagenPath = Storage::disk('cloudinary')->url($path);
            } catch (\Exception $e) {
                \Log::error('Error subiendo a Cloudinary: ' . $e->getMessage());
            }
        }

        $imagenesExtra = [];

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $foto) {
                try {
                    $path = $foto->store('mascotas', 'cloudinary');
                    $imagenesExtra[] = Storage::disk('cloudinary')->url($path);
                } catch (\Exception $e) {
                    \Log::error('Error subiendo foto extra a Cloudinary: ' . $e->getMessage());
                }
            }
        }

        try {
            DB::transaction(function () use ($request, $publicacion, $esEncontrado, $imagenPath, $imagenesExtra) {
                $publicacion->mascota->update([
                    'nombre'  => $request->nombre,
                    'especie' => $request->especie,
                    'raza'    => $request->raza,
                    'color'   => $request->color,
                    'tamano'  => $request->tamano,
                    'sexo'    => $request->sexo,
                ]);

                $datosPublicacion = [
                    'titulo'       => $request->titulo,
                    'descripcion'  => $request->descripcion,
                    'fecha_evento' => $request->fecha_evento,
                    'zona'         => $request->zona,
                    'lat'          => $this->latDentroDeZona($request->lat, $request->lng) ? $request->lat : null,
                    'lng'          => $this->latDentroDeZona($request->lat, $request->lng) ? $request->lng : null,
                    'imagen'       => $imagenPath,
                    'estado'       => $request->estado,
                ];

                if ($esEncontrado) {
                    $datosPublicacion['fecha_encontrada']     = $request->fecha_encontrada;
                    $datosPublicacion['ubicacion_encontrada'] = $request->ubicacion_encontrada;
                    $datosPublicacion['contacto']             = $request->contacto;
                }

                $publicacion->update($datosPublicacion);

                $ordenBase = ($publicacion->imagenes()->max('orden') ?? -1) + 1;

                foreach ($imagenesExtra as $i => $url) {
                    $publicacion->imagenes()->create([
                        'url'   => $url,
                        'orden' => $ordenBase + $i,
                    ]);
                }
            });
        } catch (\Exception $e) {
            \Log::error('Error actualizando publicación: ' . $e->getMessage());
            return back()->withErrors([
                'general' => 'Hubo un error al guardar los cambios. Intentá de nuevo.',
            ]);
        }

        return redirect()->route('publicaciones.mias')
            ->with('success', '¡Publicación actualizada correctamente!');
    }

    public function destroy(Publicacion $publicacion)
    {
        if ($publicacion->usuario_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($publicacion) {
                $mascota = $publicacion->mascota;
                $publicacion->delete();

                if ($mascota) {
                    $mascota->delete();
                }
            });
        } catch (\Exception $e) {
            \Log::error('Error eliminando publicación: ' . $e->getMessage());
            return back()->withErrors([
                'general' => 'Hubo un error al eliminar la publicación.',
            ]);
        }

        return redirect()->route('publicaciones.mias')
            ->with('success', 'Publicación eliminada.');
    }

    public function marcarResuelto(Publicacion $publicacion)
    {
        if ($publicacion->usuario_id !== Auth::id()) {
            abort(403);
        }

        $publicacion->update(['estado' => 'resuelto']);

        return back()->with('success', '¡Genial! Marcamos la publicación como resuelta.');
    }

    // Si lat/lng vienen vacíos o fuera de zona (ver config/zonamapa.php), no se guardan.
    private function latDentroDeZona($lat, $lng): bool
    {
        if ($lat === null || $lng === null) {
            return false;
        }

        return $lat >= config('zonamapa.sur') && $lat <= config('zonamapa.norte')
            && $lng >= config('zonamapa.oeste') && $lng <= config('zonamapa.este');
    }

    // Cuántos reportes distintos hacen falta para ocultar una publicación automáticamente.
    private const REPORTES_PARA_OCULTAR = 20;

    public function reportar(Request $request, Publicacion $publicacion)
    {
        $ip = $request->ip();

        $yaReporto = ReportePublicacion::where('publicacion_id', $publicacion->id)
            ->where('ip', $ip)
            ->exists();

        if ($yaReporto) {
            return back()->withErrors([
                'general' => 'Ya reportaste esta publicación anteriormente.',
            ]);
        }

        ReportePublicacion::create([
            'publicacion_id' => $publicacion->id,
            'ip'             => $ip,
        ]);

        $totalReportes = ReportePublicacion::where('publicacion_id', $publicacion->id)->count();

        $publicacion->reportes = $totalReportes;

        // Al llegar al límite, se oculta de los listados públicos (no se borra:
        // así queda registro por si hay que revisarla más adelante).
        if ($totalReportes >= self::REPORTES_PARA_OCULTAR && !$publicacion->oculta_en) {
            $publicacion->oculta_en = now();
        }

        $publicacion->save();

        return back()->with('success', 'Gracias por tu reporte. Lo vamos a revisar.');
    }

    // Suma una vista si el visitante (por IP) todavía no vio esta publicación,
    // y nunca si es el propio dueño mirando su publicación.
    private function registrarVista(Publicacion $publicacion): void
    {
        if (Auth::check() && $publicacion->usuario_id === Auth::id()) {
            return;
        }

        $ip = request()->ip();

        $yaVio = VistaPublicacion::where('publicacion_id', $publicacion->id)
            ->where('ip', $ip)
            ->exists();

        if ($yaVio) {
            return;
        }

        try {
            VistaPublicacion::create([
                'publicacion_id' => $publicacion->id,
                'ip'              => $ip,
            ]);

            $publicacion->increment('vistas');
        } catch (\Illuminate\Database\QueryException $e) {
            // Dos requests casi simultáneos del mismo IP: el unique constraint
            // ya frenó el duplicado, no hace falta sumar de nuevo.
        }
    }
}