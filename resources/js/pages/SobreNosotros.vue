<template>
  <div class="sobre-nosotros">

    <!-- HERO -->
    <section class="sobre-hero">
      <div class="contenido-hero">
        <p class="mini-title">QUIÉNES SOMOS</p>
        <h1>Cada huella cuenta,<br>cada historia importa</h1>
        <p>
          Somos una comunidad dedicada a reunir mascotas perdidas con sus familias.
          Creemos que ningún animal debería estar solo, y que juntos podemos hacer la diferencia.
        </p>
        <p>
          Desde 2022 trabajamos conectando personas, construyendo redes de ayuda
          y usando la tecnología para darle una segunda oportunidad a quienes más lo necesitan.
        </p>
        <div class="botones">
          <a href="/login" class="btn naranja">Publicar mascota</a>
          <a href="/mapa" class="btn transparente">Ver el mapa</a>
        </div>
      </div>
    </section>

    <!-- HISTORIA -->
    <section class="historia">
      <div class="historia-texto">
        <span>NUESTRA HISTORIA</span>
        <h2>Nació de una pérdida,<br>creció en comunidad</h2>
        <p>
          Todo empezó cuando uno de nuestros fundadores perdió a su perra Lola en el barrio.
          Días de búsqueda, carteles en postes y grupos de WhatsApp le enseñaron algo importante:
          la tecnología podía hacer esto mucho más fácil.
        </p>
        <p>
          Así nació Huellas Perdidas. Una plataforma pensada desde la experiencia real,
          para que nadie tenga que vivir esa angustia sin herramientas que ayuden.
        </p>
        <p>
          Hoy somos cientos de voluntarios, usuarios y organizaciones trabajando juntos
          en toda la Argentina.
        </p>
      </div>

      <div class="cards-info">
        <div class="card">
          <h3>🗺️ Mapa en tiempo real</h3>
          <p>Visualizá publicaciones de mascotas perdidas y encontradas en un mapa interactivo,
             filtrado por zona y fecha para encontrar más rápido.</p>
        </div>
        <div class="card">
          <h3>📢 Red de difusión</h3>
          <p>Cada publicación llega a cientos de personas cercanas al lugar.
             Cuantos más seamos, más rápido se reencuentran las familias.</p>
        </div>
        <div class="card">
          <h3>❤️ Comunidad solidaria</h3>
          <p>Voluntarios, rescatistas y vecinos comprometidos que responden al instante
             cuando alguien necesita ayuda.</p>
        </div>
      </div>
    </section>

  </div>
</template>

<script setup>
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

.sobre-nosotros {
  width: 100%;
  background: #f5f5f5;
  overflow: hidden;
  font-family: 'Poppins', sans-serif;
}

/* HERO */
.sobre-hero {
  position: relative;
  width: 100%;
  min-height: 90vh;
  background:
    linear-gradient(rgba(0,0,0,.72), rgba(0,0,0,.72)),
    url('https://images.unsplash.com/photo-1517849845537-4d257902454a?q=80&w=2070&auto=format&fit=crop');
  background-size: cover;
  background-position: center;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  padding: 120px 8%;
}

.contenido-hero {
  max-width: 950px;
  color: white;
  z-index: 2;
}

.mini-title {
  color: #ff8c1a;
  letter-spacing: 3px;
  font-size: 14px;
  font-weight: 600;
}

.contenido-hero h1 {
  font-size: 65px;
  margin-top: 20px;
  margin-bottom: 25px;
  line-height: 1.1;
}

.contenido-hero p {
  font-size: 18px;
  line-height: 1.9;
  color: #e0e0e0;
  margin-bottom: 18px;
}

/* BOTONES */
.botones {
  margin-top: 40px;
  display: flex;
  justify-content: center;
  gap: 20px;
  flex-wrap: wrap;
}

.btn {
  padding: 15px 35px;
  border-radius: 12px;
  text-decoration: none;
  font-weight: 600;
  transition: .3s;
}

.naranja {
  background: #ff7b00;
  color: white;
}

.naranja:hover {
  background: #ff9500;
  transform: translateY(-4px);
}

.transparente {
  border: 2px solid #ff7b00;
  color: white;
}

.transparente:hover {
  background: #ff7b00;
  transform: translateY(-4px);
}

/* HISTORIA */
.historia {
  width: 100%;
  padding: 100px 8%;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 70px;
  background: white;
}

.historia-texto span {
  color: #ff7b00;
  font-weight: 600;
  letter-spacing: 2px;
}

.historia-texto h2 {
  font-size: 45px;
  margin: 20px 0;
  color: #222;
}

.historia-texto p {
  color: #666;
  line-height: 2;
  margin-bottom: 20px;
  font-size: 17px;
}

/* CARDS */
.cards-info {
  display: flex;
  flex-direction: column;
  gap: 25px;
}

.card {
  background: #fff;
  padding: 35px;
  border-radius: 20px;
  box-shadow: 0 5px 25px rgba(0,0,0,.08);
  transition: .3s;
  border-left: 5px solid #ff7b00;
}

.card:hover {
  transform: translateY(-8px);
}

.card h3 {
  margin-bottom: 15px;
  color: #222;
  font-size: 24px;
}

.card p {
  color: #666;
  line-height: 1.8;
}

/* ESTADÍSTICAS */
.estadisticas {
  background: #111827;
  padding: 90px 8%;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 30px;
}

.stat {
  background: #1f2937;
  padding: 40px;
  border-radius: 20px;
  text-align: center;
  transition: .3s;
}

.stat:hover {
  transform: translateY(-7px);
}

.stat h2 {
  color: #ff7b00;
  font-size: 50px;
  margin-bottom: 10px;
}

.stat p {
  color: #d1d5db;
  font-size: 18px;
}

/* FRASE */
.frase {
  padding: 120px 8%;
  background:
    linear-gradient(rgba(0,0,0,.75), rgba(0,0,0,.75)),
    url('https://images.unsplash.com/photo-1548199973-03cce0bbc87b?q=80&w=2070&auto=format&fit=crop');
  background-size: cover;
  background-position: center;
  text-align: center;
}

.frase h2 {
  max-width: 900px;
  margin: auto;
  color: white;
  font-size: 45px;
  line-height: 1.6;
  font-weight: 600;
}

/* RESPONSIVE */
@media (max-width: 1000px) {
  .historia { grid-template-columns: 1fr; }
  .estadisticas { grid-template-columns: 1fr 1fr; }
  .contenido-hero h1 { font-size: 50px; }
}

@media (max-width: 700px) {
  .contenido-hero h1 { font-size: 38px; }
  .contenido-hero p { font-size: 16px; }
  .estadisticas { grid-template-columns: 1fr; }
  .frase h2 { font-size: 30px; }
  .historia-texto h2 { font-size: 35px; }
}
</style>
