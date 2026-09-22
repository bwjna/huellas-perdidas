<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Crear o actualizar usuario primero
        $user = Usuario::updateOrCreate(
            ['email' => $googleUser->email],
            [
                'nombre' => $googleUser->name,
                'apellido' => '',
                'nombre_usuario' => explode('@', $googleUser->email)[0],
                'google_id' => $googleUser->id,
                'password' => bcrypt(str()->random(24)),
            ]
        );

        // Guardar avatar con nombre fijo basado en user_id
        $filename = 'avatars/'.$user->id.'.jpg';

        try {
            $contents = file_get_contents($googleUser->avatar);
            Storage::disk('public')->put($filename, $contents);
            $user->avatar = 'storage/'.$filename;
        } catch (\Exception $e) {
            $user->avatar = 'img/default_avatar.png';
        }

        $user->save();

        Auth::login($user);

        return redirect()->route('inicio');
    }
}
