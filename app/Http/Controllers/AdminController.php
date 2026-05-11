<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::all();
        return Inertia::render('PanelAdmin', [
            'usuarios' => $usuarios
        ]);
    }
}