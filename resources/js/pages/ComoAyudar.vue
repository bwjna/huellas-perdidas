<template>
  <section class="app-root">
    <!-- HERO -->
    <header class="hero">
      <h1>¿Cómo ayudar? 🐾</h1>
      <p>
        Cada publicación compartida puede ayudar a que una mascota vuelva con su familia.
        Estas son algunas formas simples en las que podés colaborar dentro de la comunidad.
      </p>
    </header>

    <!-- TIMELINE -->
    <div class="timeline">
      <!-- ITEM 1 -->
      <div class="timeline-item left">
        <div class="timeline-icon">
          <i class="bi bi-megaphone-fill"></i>
        </div>
        <div class="timeline-content">
          <h2>Publicá correctamente</h2>
          <p>
            Intentá subir fotos claras, ubicación aproximada y una descripción detallada
            para que sea más fácil identificar a la mascota.
          </p>
        </div>
      </div>

      <!-- ITEM 2 -->
      <div class="timeline-item right">
        <div class="timeline-icon">
          <i class="bi bi-share-fill"></i>
        </div>
        <div class="timeline-content">
          <h2>Compartí publicaciones</h2>
          <p>
            Compartir una publicación en redes sociales o grupos barriales puede marcar
            la diferencia y ayudar a encontrar más rápido a una mascota perdida.
          </p>
        </div>
      </div>

      <!-- ITEM 3 -->
      <div class="timeline-item left">
        <div class="timeline-icon">
          <i class="bi bi-geo-alt-fill"></i>
        </div>
        <div class="timeline-content">
          <h2>Revisá el mapa</h2>
          <p>
            El mapa permite ver reportes cerca de tu zona para que puedas ayudar a identificar
            mascotas o brindar información útil.
          </p>
        </div>
      </div>

      <!-- ITEM 4 -->
      <div class="timeline-item right">
        <div class="timeline-icon">
          <i class="bi bi-heart-fill"></i>
        </div>
        <div class="timeline-content">
          <h2>Ayudá responsablemente</h2>
          <p>
            Si encontrás una mascota, acercate con cuidado, revisá si tiene identificación
            y tratá de mantenerla segura mientras se contacta al dueño.
          </p>
        </div>
      </div>

      <!-- ITEM 5 -->
      <div class="timeline-item left">
        <div class="timeline-icon">
          <i class="bi bi-chat-dots-fill"></i>
        </div>
        <div class="timeline-content">
          <h2>Contactanos</h2>
          <p>
            También podés escribirnos si encontrás errores, querés colaborar con el proyecto
            o tenés ideas para mejorar la plataforma.
          </p>
        </div>
      </div>
    </div>

    <!-- BOTONES -->
    <div class="action-buttons">
      <a href="/colaborar" class="action-btn">Colabora</a>
      <a href="/mapa" class="action-btn">Abrir mapa</a>
      <a href="/contacto" class="action-btn">Contactanos</a>
    </div>

    <div class="glow-circle" aria-hidden="true"></div>
  </section>
</template>

<script setup>
// Si usas Inertia Link o Ziggy, importa Link y reemplaza los <a> por <Link :href="route('...')">.
// import { Link } from '@inertiajs/inertia-vue3'
</script>

<script>
export default {
  name: 'ComoAyudar'
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family:'Poppins',sans-serif;
}

/* Aplicado al contenedor del componente en lugar de body */
.app-root{
  background:#f7f4ef;
  overflow-x:hidden;
  min-height:100vh;
  padding-bottom:80px;
}

/* HERO */
.hero{
  padding:100px 20px 70px;
  text-align:center;
}

.hero h1{
  font-size:70px;
  font-weight:700;
  color:#111;
  margin-bottom:20px;
}

.hero p{
  max-width:850px;
  margin:auto;
  color:#555;
  font-size:18px;
  line-height:1.8;
}

/* TIMELINE */
.timeline-content{
  position:relative;
}

.timeline-content::before{
  content:'';
  position:absolute;
  inset:10px;
  background:#ffffff;
  border-radius:28px;
  z-index:-1;
  opacity:.12;
}

.timeline{
  position:relative;
  max-width:1200px;
  margin:40px auto 100px;
  padding:0 20px;
}
.timeline::before{
  content:'';
  position:absolute;
  width:6px;
  background:#ff7b00;
  top:7%;
  height:80%;
  left:50%;
  transform:translateX(-50%);
  border-radius:999px;
}

/* ITEMS */
.timeline-item{
  width:50%;
  padding:20px 40px;
  position:relative;
}

.timeline-item.left{
  left:0;
}

.timeline-item.right{
  left:50%;
}

.timeline-content{
  background:#fff;
  padding:35px;
  border-radius:28px;
  box-shadow:0 10px 30px rgba(0,0,0,.08);
  border:3px solid #111;
  transition:.3s;
  position:relative;
}

.timeline-content:hover{
  transform:translateY(-8px);
}

/* ICONOS */
.timeline-icon{
  position:absolute;
  width:70px;
  height:70px;
  background:#ff7b00;
  border-radius:50%;
  display:flex;
  justify-content:center;
  align-items:center;
  font-size:30px;
  color:white;
  border:4px solid #111;
  top:40px;
  z-index:2;
}

.left .timeline-icon{
  right:-35px;
}

.right .timeline-icon{
  left:-35px;
}

/* TEXTOS */
.timeline-content h2{
  color:#111;
  font-weight:700;
  margin-bottom:15px;
}

.timeline-content p{
  color:#555;
  line-height:1.8;
}

/* BOTONES */
.action-buttons{
  display:flex;
  justify-content:center;
  gap:20px;
  flex-wrap:wrap;
  margin-bottom:80px;
}

.action-btn{
  padding:16px 30px;
  border-radius:18px;
  text-decoration:none;
  background:#111;
  color:white;
  font-weight:600;
  transition:.3s;
  border:3px solid #111;
}

.action-btn:hover{
  background:#ff7b00;
  color:white;
  transform:translateY(-4px);
  box-shadow:6px 6px 0 #111;
}

/* RESPONSIVE */
@media(max-width:900px){
  .timeline::before{
    left:30px;
  }

  .timeline-item{
    width:100%;
    padding-left:90px;
    padding-right:20px;
    margin-bottom:40px;
  }

  .timeline-item.right{
    left:0;
  }

  .timeline-icon{
    left:-5px !important;
    right:auto;
  }

  .hero h1{
    font-size:50px;
  }
}

.glow-circle{
  position:fixed;
  width:500px;
  height:500px;
  border-radius:50%;
  background:radial-gradient(circle, rgba(255,140,0,.12), transparent 70%);
  bottom:-150px;
  left:-150px;
  z-index:-1;
}
</style>
