<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLogin()
    {
        return Inertia::render('Login');
    }

    // Mostrar formulario de registro
    public function showRegister()
    {
        return Inertia::render('Registro');
    }

    // Procesar login
    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credenciales)) {
            $request->session()->regenerate();
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son correctas.',
        ]);
    }

    // Procesar registro
    public function register(Request $request)
    {
        $request->validate([
            'nombre'         => 'required|string|max:50',
            'apellido'       => 'required|string|max:50',
            'nombre_usuario' => 'required|string|max:50',
            'email'          => 'required|email|unique:usuarios',
            'password'       => 'required|min:6|confirmed',
        ]);

        if (Usuario::where('nombre_usuario', $request->nombre_usuario)->exists()) {
            $sugerencias = [];
            $opciones = [
                $request->nombre_usuario . rand(1, 99),
                $request->nombre_usuario . '_' . rand(1, 99),
                $request->nombre_usuario . '.' . $request->apellido,
            ];

            foreach ($opciones as $opcion) {
                if (!Usuario::where('nombre_usuario', $opcion)->exists()) {
                    $sugerencias[] = $opcion;
                }
            }

            return Inertia::render('Registro', [
                'errors' => ['nombre_usuario' => 'El nombre de usuario ya está en uso.'],
                'sugerencias' => $sugerencias,
            ])->toResponse($request)->setStatusCode(422);
        }

        $usuario = Usuario::create([
            'nombre'         => $request->nombre,
            'apellido'       => $request->apellido,
            'nombre_usuario' => $request->nombre_usuario,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
        ]);

        Auth::login($usuario);
        return redirect('/');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}