<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ==========================================
// IMPORTACIÓN DE CONTROLADORES Y MIDDLEWARES
// ==========================================
use App\Http\Controllers\PruebaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MapaController;
use App\Http\Controllers\AvistamientoController;
use App\Http\Controllers\PublicacionController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\FacebookAuthController;
use App\Http\Middleware\VerificarTelefono;

// ==========================================
// RUTAS DE EXCEPCIÓN (Solo requieren 'auth')
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/completar-perfil', [AuthController::class, 'showCompletarPerfil'])->name('perfil.completar');
    Route::post('/completar-perfil', [AuthController::class, 'guardarTelefono'])->name('perfil.completar.guardar');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ==========================================
// RUTAS BLOQUEADAS (Requieren 'auth' Y 'telefono')
// ==========================================
Route::middleware(['auth', VerificarTelefono::class])->group(function () {
    Route::get('/publicaciones/crear', [PublicacionController::class, 'create'])->name('publicaciones.crear');
    Route::post('/publicaciones', [PublicacionController::class, 'store'])->name('publicaciones.store');
    
    Route::get('/publicaciones/mias', [PublicacionController::class, 'misPublicaciones'])->name('publicaciones.mias');
    Route::get('/publicaciones/{publicacion}/editar', [PublicacionController::class, 'edit'])->name('publicaciones.edit');
    Route::put('/publicaciones/{publicacion}', [PublicacionController::class, 'update'])->name('publicaciones.update');
    Route::delete('/publicaciones/{publicacion}', [PublicacionController::class, 'destroy'])->name('publicaciones.destroy');
    Route::patch('/publicaciones/{publicacion}/resuelto', [PublicacionController::class, 'marcarResuelto'])->name('publicaciones.marcarResuelto');

    Route::get('/perfil/editar', [AuthController::class, 'showEditarPerfil'])->name('perfil.editar');
    Route::put('/perfil', [AuthController::class, 'actualizarPerfil'])->name('perfil.actualizar');
});

// ==========================================
// RUTAS DE ADMINISTRADOR
// ==========================================
Route::middleware(['auth', VerificarTelefono::class, 'esadmin'])->group(function () {
    Route::get('/panel', [AdminController::class, 'index'])->name('panel');
    Route::get('/panel/reportes', [AdminController::class, 'reportes'])->name('panel.reportes');
    Route::delete('/panel/reportes/{publicacion}', [AdminController::class, 'eliminarReportada'])->name('panel.reportes.eliminar');
    Route::patch('/panel/reportes/{publicacion}/restaurar', [AdminController::class, 'restaurarReportada'])->name('panel.reportes.restaurar');
});

// ==========================================
// RUTAS PÚBLICAS
// ==========================================
Route::get('/', fn () => Inertia::render('Inicio'))->name('inicio');
Route::get('/mapa', [MapaController::class, 'index'])->name('mapa');
Route::get('/sobre-nosotros', fn() => inertia('SobreNosotros'))->name('sobre.nosotros');
Route::get('/como-ayudar', fn () => Inertia::render('ComoAyudar'))->name('como.ayudar');
Route::get('/contacto', fn() => Inertia::render('Contacto'))->name('contacto');
Route::get('/colaborar', fn() => Inertia::render('Donacion/Colaborar'))->name('colaborar');

Route::get('/mascotas-perdidas', [PublicacionController::class, 'index'])->name('publicaciones.index');
Route::get('/mascotas-encontradas', [PublicacionController::class, 'indexEncontradas'])->name('mascotas.encontradas');

// Las dinámicas van siempre debajo de las estáticas
Route::get('/publicaciones/{publicacion}/cartel', [PublicacionController::class, 'cartel'])->name('publicaciones.cartel');
Route::get('/publicaciones/{publicacion}', [PublicacionController::class, 'show'])->name('publicaciones.show');

Route::post('/publicaciones/{publicacion}/avistamientos', [AvistamientoController::class, 'store'])->name('avistamientos.store');
Route::post('/publicaciones/{publicacion}/reporte', [PublicacionController::class, 'reportar'])->name('publicaciones.reportar');
Route::post('/subir', [PruebaController::class, 'subir']);

// ==========================================
// RUTAS DE INVITADO (Login, Registro, Password)
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
Route::post('/registro', [AuthController::class, 'register']);
Route::get('/olvide-password', [AuthController::class, 'showOlvidePassword'])->name('password.request');
Route::post('/olvide-password', [AuthController::class, 'enviarLinkReset'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Login Redes
Route::get('/auth/google', [GoogleController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);
Route::post('/auth/facebook/callback', [FacebookAuthController::class, 'handleCallback'])
    ->name('auth.facebook.callback');