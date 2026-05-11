<template>
  <!-- HERO -->
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

  <!-- CARDS -->
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
</template>

<script setup>
// por ahora no necesitás nada acá
</script>