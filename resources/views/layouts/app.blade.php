<!DOCTYPE html>
<html lang="es" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huellas Perdidas</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">

    <!-- Tus estilos -->
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">

    <style>
      /* todo tu CSS del navbar se queda igual */
      .navbar .logo,
      .navbar-brand .logo {
        height: 64px;
        max-height: 80px;
        width: auto;
        object-fit: contain;
      }
      .navbar {
        min-height: 64px;
        height: auto;
        overflow: visible !important;
        position: relative;
        z-index: 2000;
      }
      .acciones-nav {
        display: flex;
        gap: 8px;
        align-items: center;
      }
      .btn-tema {
        background: none;
        border: 1px solid rgba(255,255,255,0.12);
        color: #fff;
        border-radius: 6px;
        padding: 6px 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
      }
      #toggle-tema {
        display: inline-flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        pointer-events: auto !important;
        z-index: 3000 !important;
      }
      .navbar-collapse { z-index: 1000; }
      .navbar-brand .brand-text { display: none !important; }
      @media (max-width: 991px) {
        .navbar .logo { height: 56px; }
        .acciones-nav {
          width: 100%;
          justify-content: flex-end;
          margin-top: 8px;
          flex-wrap: wrap;
        }
        .acciones-nav .btn { min-width: 140px; }
        .btn-tema { margin-left: 0.5rem; }
      }
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" data-bs-theme="dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('inicio') }}">
                <img src="{{ asset('img/logo_huellas_perdidas.png') }}" class="logo me-2" alt="Huellas Perdidas">
                <span class="brand-text d-none">HUELLAS PERDIDAS</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('mapa') }}">Mapa</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('publicaciones.index') }}">Publicaciones</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">¿Cómo ayudar?</a></li>
                    @auth
                        @if(auth()->user()->rol === 'admin')
                            <li class="nav-item"><a class="nav-link" href="{{ route('panel') }}">Panel</a></li>
                        @endif
                    @endauth
                    <li class="nav-item"><a class="nav-link" href="#">Sobre Nosotros</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
                </ul>

                <div class="acciones-nav">
                    @auth
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="btn btn-outline-light">Logout ({{ auth()->user()->nombre }})</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light">Iniciar sesión</a>
                        <a href="{{ route('register') }}" class="btn btn-naranja">Registrarse</a>
                    @endauth
                </div>
            </div>
            <button id="toggle-tema" class="btn-tema ms-3" type="button" aria-label="Cambiar tema">🌙</button>
        </div>
    </nav>

    {{-- CONTENIDO INERTIA --}}
    @inertia

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (function () {
            const toggleBtn = document.getElementById('toggle-tema');
            const body = document.body;
            if (!toggleBtn) return;
            if (localStorage.getItem('tema') === 'dark') {
                body.classList.add('dark-mode');
                toggleBtn.textContent = '☀️';
            } else {
                toggleBtn.textContent = '🌙';
            }
            toggleBtn.addEventListener('click', () => {
                const isDark = body.classList.toggle('dark-mode');
                toggleBtn.textContent = isDark ? '☀️' : '🌙';
                localStorage.setItem('tema', isDark ? 'dark' : 'light');
            });
        })();
    </script>

</body>
</html>