<?php
include '../modelo/conexion.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huellas Perdidas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/global.css">
</head>
<body>
    <nav class="navbar bg-dark navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
     <img src="../styles/logo_huellas_perdidas.png" class="logo">
    </a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item">
          <a class="nav-link" href="mapa.php">Mapa</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Publicaciones</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="como-ayudar.php">¿Como ayudar?</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="sobre-nosotros.php">Sobre Nosotros</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contacto.php">Contacto</a>
        </li>
      </ul>
        <div class="ms-5">
        <a href="acciones/login.php" class="btn btn-outline-light">Iniciar Sesión</a>
        <button class="btn btn-naranja">Registrarse</button>
        </div>
    </div>
    <button id="toggle-tema" class="btn-tema">🌙</button>
  </div>
</nav>
</nav>
<div class="fondo">
    <div class="container">
        <p class="texto">
        Una plataforma pensada para ayudar a reencontrar mascotas perdidas con sus dueños,
        conectando a la comunidad de forma rápida y sencilla.
        Un espacio donde la comunidad se une para ayudar a que cada mascota perdida vuelva a casa.
        Publicá, buscá y ayudá a encontrar mascotas perdidas en tu zona.
        </p>
    </div>
    <div class="wave-bottom">
    <svg viewBox="0 0 1440 150" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,0 Q720,150 1440,0 L1440,150 L0,150 Z" fill="white"/>
    </svg>
</div>
</div>
<section class="acciones">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card-accion">
                    <div class="card-icono">🔍</div>
                    <h3>Perdí mi mascota</h3>
                    <p>Publicá para que la comunidad te ayude a encontrarla</p>
                    <a href="#" class="btn-card">Publicar</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-accion">
                    <div class="card-icono">🐾</div>
                    <h3>Encontré una mascota</h3>
                    <p>Avisá para reunirla con su dueño</p>
                    <a href="#" class="btn-card">Publicar</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-accion">
                    <div class="card-icono">🗺️</div>
                    <h3>Quiero ayudar</h3>
                    <p>Ver el mapa de mascotas perdidas en tu zona</p>
                    <a href="#" class="btn-card">Ver mapa</a>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
<script src="../javascript/script-claro-oscuro.js"></script>
</body>
</html>