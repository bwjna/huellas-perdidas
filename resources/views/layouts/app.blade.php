<!DOCTYPE html>
<html lang="es" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huellas Perdidas</title>
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="manifest" href="/build/manifest.webmanifest">
    <meta name="theme-color" content="#212529">
    <link rel="apple-touch-icon" href="/img/pwa-192.png">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Tus estilos -->
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">

    <link rel="icon" type="image/jpeg" href="{{ asset('img/logo_fava.png') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />

    <style>
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
      }
      .acciones-nav {
        display: flex;
        gap: 8px;
        align-items: center;
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
        .acciones-nav .btn {
          width: 100%;
          min-width: 0;
        }

        /* El "nav-slider" (franja naranja) está pensado para hover de mouse.
           En mobile el toque lo deja "pegado" tapando texto, así que lo ocultamos. */
        #nav-slider {
          display: none !important;
        }
      }

      /* Eliminar la flechita de Bootstrap */
      .dropdown-toggle::after {
        display: none !important;
      }

      /* Quitar el remarcado al hacer click */
      .nav-item.dropdown .nav-link:focus,
      .nav-item.dropdown .nav-link:active,
      .nav-item.dropdown .nav-link.show,
      .nav-item.dropdown .nav-link[aria-expanded="true"] {
        color: rgba(255, 255, 255, 0.75) !important;
        background: none !important;
        box-shadow: none !important;
        outline: none !important;
      }

      /* Animación de la flecha */
      .flecha-animada {
        display: inline-block;
        transition: transform 0.25s ease;
        transform-origin: center;
      }
      .dropdown-toggle[aria-expanded="true"] .flecha-animada {
        transform: rotate(180deg);
      }

      /* ✅ Estilos del botón avatar */
      .btn-avatar {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        border-radius: 50%;
      }
      .btn-avatar:focus {
        outline: none;
        box-shadow: none;
      }
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky top-0 z-[2000]">
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
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('mapa') }}">Mapa</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="mascotasDropdown"
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-paw me-1"></i>Mascotas
                            <i class="fa fa-chevron-down ms-1 flecha-animada" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="mascotasDropdown">
                            <li><a class="dropdown-item" href="{{ route('publicaciones.index') }}">Perdidas</a></li>
                            <li><a class="dropdown-item" href="{{ route('mascotas.encontradas') }}">Encontradas</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('como.ayudar') }}">¿Cómo ayudar?</a>
                    </li>
                    @auth
                        @if(auth()->user()->rol === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('panel') }}">Panel</a>
                            </li>
                        @endif
                    @endauth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="sobreDropdown"
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Sobre Nosotros
                            <i class="fa fa-chevron-down ms-1 flecha-animada" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="sobreDropdown">
                            <li><a class="dropdown-item" href="{{ route('sobre.nosotros') }}">Sobre Nosotros</a></li>
                            <li><a class="dropdown-item" href="{{ route('contacto') }}">Contacto</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('colaborar') }}">Colaborar</a>
                    </li>
                    <span class="nav-slider" id="nav-slider"></span>
                </ul>

                <div class="acciones-nav">
                    @auth
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

                        {{-- ✅ El data-bs-toggle va en el button, NO en el img --}}
                        <div class="dropdown">
                            <button
                                class="btn-avatar"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                            <img 
                                src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nombre . '+' . auth()->user()->apellido) . '&background=ea580c&color=fff' }}"
                                onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nombre . '+' . auth()->user()->apellido) }}&background=ea580c&color=fff';"
                                width="40"
                                height="40"
                                style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; flex-shrink: 0; aspect-ratio: 1/1; border: 2px solid rgba(255,255,255,0.3);"
                                alt="Perfil de {{ auth()->user()->nombre }}"
                            >
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                                <li>
                                    <span class="dropdown-item-text fw-semibold">
                                        {{ auth()->user()->nombre }}
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('publicaciones.mias') }}">
                                        Mis publicaciones
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('perfil.editar') }}">
                                        Editar perfil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a href="#" class="dropdown-item text-danger"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-1"></i>Cerrar sesión
                                    </a>
                                </li>
                            </ul>
                        </div>

                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light">
                            <i class="bi bi-person me-1"></i>Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-naranja">
                            <i class="bi bi-person-plus me-1"></i>Registrarse
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- CONTENIDO INERTIA --}}
    @inertia

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (function () {
            const links = document.querySelectorAll('.navbar-nav .nav-link');
            const slider = document.getElementById('nav-slider');
            if (!slider || links.length === 0) return;

            function moveSlider(el) {
                const navUl = document.querySelector('.navbar-nav');
                const ulRect = navUl.getBoundingClientRect();
                const elRect = el.getBoundingClientRect();
                slider.style.left = (elRect.left - ulRect.left) + 'px';
                slider.style.width = elRect.width + 'px';
                slider.style.opacity = '1';
            }

            links.forEach(link => {
                link.addEventListener('mouseenter', () => moveSlider(link));
            });

            document.querySelector('.navbar-nav').addEventListener('mouseleave', () => {
                slider.style.opacity = '0';
            });
        })();
    </script>

</body>
</html>