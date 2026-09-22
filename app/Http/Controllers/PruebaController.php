<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PruebaController extends Controller
{
    public function subir(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image'
        ]);

        $url = Cloudinary::upload(
            $request->file('imagen')->getRealPath()
        )->getSecurePath();

        return response()->json([
            'ok' => true,
            'url' => $url
        ]);
    }
}