@extends('layouts.app')

@section('title', 'Registrarse')

@push('styles')
<style>
/* ===== Sugerencias registro ===== */
.sugerencias-container {
    padding-left: 0;
    margin-left: 0;
    width: 100%;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.sugerencia {
    margin: 0;
}
.alert {
    overflow: hidden;
}

/* Estilos locales mínimos: no tocar navbar aquí */
</style>
@endpush

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4 text-center">Crear cuenta</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('sugerencias'))
    <div class="alert alert-warning" style="padding: 12px;">
        <p class="mb-2">El nombre de usuario ya existe. Sugerencias:</p>
        <div class="sugerencias-container">
            @foreach(session('sugerencias') as $s)
                <button type="button" class="btn btn-sm btn-outline-secondary sugerencia">{{ $s }}</button>
            @endforeach
        </div>
    </div>
    @endif

    <form action="/registro" method="POST" novalidate>
        @csrf

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Apellido</label>
            <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido') }}" required>
            @error('apellido') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre de usuario</label>
            <input type="text" name="nombre_usuario" class="form-control @error('nombre_usuario') is-invalid @enderror" value="{{ old('nombre_usuario') }}" required>
            @error('nombre_usuario') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-naranja w-100">Registrarse</button>
        <p class="text-center mt-3">¿Ya tenés cuenta? <a href="{{ route('login') }}">Iniciá sesión</a></p>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Sugerencias para nombre de usuario
    document.querySelectorAll('.sugerencia').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.querySelector('input[name="nombre_usuario"]');
            if (!input) return;
            input.value = btn.textContent.trim();
            input.focus();
        });
    });
});
</script>
@endpush
