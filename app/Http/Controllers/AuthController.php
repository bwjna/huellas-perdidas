<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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

    // Procesar login (¡Limpio de Turnstile!)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credenciales = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credenciales)) {
            $request->session()->regenerate();
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son correctas.',
        ])->withInput();
    }

    // Procesar registro
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'nombre_usuario' => 'required|string|max:50',
            'email' => 'required|email|unique:usuarios',
            'telefono' => 'required|string|min:8|max:30',
            'password' => 'required|min:6|confirmed',
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
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'nombre_usuario' => $request->nombre_usuario,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($usuario);
        $request->session()->regenerate();

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

    // Mostrar formulario de editar perfil (para usuarios que ya tienen cuenta)
    public function showEditarPerfil()
    {
        return Inertia::render('EditarPerfil', [
            'usuario' => Auth::user()->only(['nombre', 'apellido', 'nombre_usuario', 'email', 'telefono']),
        ]);
    }

    // Guardar cambios del perfil
    public function actualizarPerfil(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'telefono' => 'required|string|min:8|max:30',
        ]);

        Auth::user()->update([
            'nombre'   => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
        ]);

        return back()->with('success', '¡Perfil actualizado!');
    }

    // ─── Recuperar contraseña ──────────────────────────────────────────

    public function showOlvidePassword()
    {
        return Inertia::render('OlvidePassword');
    }

    public function enviarLinkReset(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Te mandamos un link a tu email para que puedas cambiar tu contraseña.');
        }

        // Por seguridad, no confirmamos ni negamos si el email existe en la base:
        // mostramos siempre el mismo mensaje de éxito, exista o no la cuenta.
        return back()->with('success', 'Si ese email está registrado, te va a llegar un link para cambiar tu contraseña.');
    }

    public function showResetPassword(Request $request, string $token)
    {
        return Inertia::render('ResetPassword', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Usuario $usuario, string $password) {
                $usuario->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect('/login')->with('success', '¡Contraseña actualizada! Ya podés iniciar sesión.');
        }

        return back()->withErrors([
            'email' => 'Ese link ya no es válido o expiró. Pedí uno nuevo.',
        ]);
    }
    // Mostrar la vista obligatoria para quienes no tienen teléfono (OAuth)
    public function showCompletarPerfil()
    {
        // Asegurate de crear este archivo en tu carpeta de Pages de React/Vue
        return Inertia::render('CompletarPerfil'); 
    }

    // Recibir, validar y guardar el dato
    public function guardarTelefono(Request $request)
    {
        $request->validate([
            'telefono' => 'required|string|min:8|max:30',
        ], [
            'telefono.required' => 'El teléfono es indispensable para que te avisen si encuentran a tu mascota.',
        ]);

        // Actualizamos el registro del usuario autenticado
        Auth::user()->update([
            'telefono' => $request->telefono,
        ]);

        // Lo liberamos del bloqueo y lo mandamos al inicio
        return redirect()->route('inicio')->with('success', '¡Teléfono guardado con éxito!');
    }
}