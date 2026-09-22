<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class FacebookAuthController extends Controller
{
    public function handleCallback(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
        ]);

        try {
            $fbUser = Socialite::driver('facebook')->userFromToken($request->access_token);


            $fullName = $fbUser->getName() ?? 'Usuario Facebook';
            $nameParts = explode(' ', $fullName, 2);
            $nombre = $nameParts[0];
            $apellido = $nameParts[1] ?? '';
            $email = $fbUser->getEmail();

            // 1. Buscamos si el usuario ya existe por facebook_id o por email
            $user = Usuario::where('facebook_id', $fbUser->getId())
                ->orWhere('email', $email)
                ->first();

            if ($user) {
                // Si ya existe, actualizamos sus datos de Facebook sin tocar su nombre_usuario actual
                $user->update([
                    'facebook_id' => $fbUser->getId(),
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'avatar' => $fbUser->getAvatar(),
                ]);
            } else {
                // Si es un usuario nuevo, generamos un nombre_usuario único asegurándonos que no exista en la BD
                do {
                    $nombreUsuario = 'fb_' . Str::random(8);
                } while (Usuario::where('nombre_usuario', $nombreUsuario)->exists());

                // Creamos el nuevo usuario
                $user = Usuario::create([
                    'facebook_id' => $fbUser->getId(),
                    'email' => $email,
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'nombre_usuario' => $nombreUsuario,
                    'avatar' => $fbUser->getAvatar(),
                    'password' => bcrypt(Str::random(16))
                ]);
            }

            Auth::login($user);

            return redirect()->intended('/');

        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Ocurrió un error al autenticar con Facebook: ' . $e->getMessage()
            ]);
        }
    }
}