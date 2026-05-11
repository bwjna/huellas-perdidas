@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="container mt-5" style="max-width: 450px;">
    <h2 class="mb-4 text-center">Iniciar Sesión</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="/login" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-naranja w-100">Iniciar Sesión</button>
        <p class="text-center mt-3">¿No tenés cuenta? <a href="/registro">Registrate</a></p>
    </form>
</div>
@endsection