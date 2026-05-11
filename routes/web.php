<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MapaController;
use App\Http\Controllers\PublicacionController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Inicio');
})->name('inicio');

Route::get('/mapa', [MapaController::class, 'index'])->name('mapa');

Route::get('/publicaciones/crear', [PublicacionController::class, 'create'])->middleware('auth')->name('publicaciones.crear');
Route::post('/publicaciones', [PublicacionController::class, 'store'])->middleware('auth')->name('publicaciones.store');

Route::get('/publicaciones', [PublicacionController::class, 'index'])->name('publicaciones.index');

Route::get('/panel', [AdminController::class, 'index'])
    ->middleware(['auth', 'esadmin'])
    ->name('panel');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
Route::post('/registro', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');