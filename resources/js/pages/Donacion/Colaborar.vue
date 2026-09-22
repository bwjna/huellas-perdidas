<template>
  <div class="page-wrapper">

    <!-- HERO -->
    <div class="hero">
      <span class="hero-tag"> Ayudanos a seguir</span>
      <h1>Colaborá con<br><span style="color:#ff7b00">nosotros</span></h1>
      <p>Tu donación nos ayuda a mantener el servidor y seguir reuniendo mascotas con sus familias. Podés donar lo que quieras, cualquier monto ayuda.</p>
    </div>

    <!-- CARDS -->
    <div class="donaciones">

      <!-- Mercado Pago -->
      <div class="donacion-card">
        <div class="icon-box">
          <img :src="mpLogo" alt="Mercado Pago" style="width:55px;height:55px;object-fit:contain;">
        </div>
        <h2>Mercado Pago</h2>
        <p>Donación rápida y segura desde tu cuenta de Mercado Pago. Cualquier monto es bienvenido.</p>
        <a
          href="https://link.mercadopago.com.ar/huellasperdidas"
          target="_blank"
          class="donar-btn mp-btn"
        >
          Donar con Mercado Pago
        </a>
      </div>

      <!-- PayPal -->
      <div class="donacion-card">
        <div class="icon-box paypal">
          <img :src="paypalLogo" alt="PayPal" style="width:55px;height:55px;object-fit:contain;">
        </div>
        <h2>PayPal</h2>
        <p>Donación internacional segura a través de PayPal. Aceptamos donaciones desde cualquier país.</p>
        <a
          href="https://www.paypal.com/donate/?hosted_button_id=L8L88GN2ZVUFS"
          target="_blank"
          class="donar-btn paypal-btn"
        >
          Donar con PayPal
        </a>
      </div>

    </div>

    <!-- INFO -->
    <div class="info-section">
      <div class="info-box">
        <h3>¿En qué usamos tu donación?</h3>
        <ul>
      <li><span class="tilde">✓</span> Mantenimiento del servidor y hosting</li>
      <li><span class="tilde">✓</span> Desarrollo y mejoras de la plataforma</li>
      <li><span class="tilde">✓</span> Difusión en redes sociales para ampliar el alcance</li>
      <li><span class="tilde">✓</span> Ayuda directa a mascotas en situación de calle</li>
    </ul>
      </div>
    </div>

    <p class="text-center pb-5" style="color:#888;font-size:14px;">
      🔒 Serás redirigido de forma segura al procesador de pagos
    </p>

  </div>
</template>

<script setup>
const mpLogo = '/img/mp_logo.png'
const paypalLogo = '/img/PayPal-Logo.png'
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

.page-wrapper {
  background: #f6f3ee;
  min-height: 100vh;
  overflow-x: hidden;
  color: #111;
}

/* HERO */
.hero {
  text-align: center;
  padding: 100px 20px 80px;
}
.hero-tag {
  background: #ff7b00;
  color: white;
  padding: 10px 18px;
  border-radius: 999px;
  font-size: 14px;
  font-weight: 600;
}
.hero h1 {
  font-size: 72px;
  font-weight: 700;
  margin-top: 25px;
  line-height: 1.1;
}
.hero p {
  max-width: 650px;
  margin: 20px auto 0;
  color: #555;
  font-size: 17px;
  line-height: 1.8;
}

/* GRID DONACIONES */
.donaciones {
  max-width: 1000px;
  margin: auto;
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 35px;
  padding: 0 20px 80px;
}

/* CARD */
.donacion-card {
  background: white;
  border: 4px solid #111;
  border-radius: 32px;
  padding: 45px;
  text-align: center;
  box-shadow: 12px 12px 0 #111;
  transition: .3s;
}
.donacion-card:hover {
  transform: translateY(-10px) rotate(-1deg);
  box-shadow: 14px 14px 0 #ff7b00, 0 0 35px rgba(255, 123, 0, .35);
}

/* ICON BOX */
.icon-box {
  width: 95px;
  height: 95px;
  margin: auto;
  border-radius: 24px;
  background: #ff7b00;
  display: flex;
  justify-content: center;
  align-items: center;
}
.icon-box.paypal {
  background: #0070ba;
}

/* TEXTOS */
.donacion-card h2 {
  margin-top: 25px;
  font-weight: 700;
}
.donacion-card p {
  margin-top: 15px;
  color: #555;
  line-height: 1.8;
}

/* BOTONES */
.donar-btn {
  display: inline-block;
  margin-top: 30px;
  padding: 16px 28px;
  border-radius: 18px;
  text-decoration: none;
  color: white;
  font-weight: 600;
  transition: .3s;
}
.mp-btn {
  background: #ff7b00;
}
.mp-btn:hover {
  background: #ff8c1a;
  color: white;
  transform: translateY(-5px);
  box-shadow: 0 0 15px rgba(255,123,0,.45), 0 0 35px rgba(255,123,0,.25);
}
.paypal-btn {
  background: #0070ba;
}
.paypal-btn:hover {
  background: #008be8;
  color: white;
  transform: translateY(-5px);
  box-shadow: 0 0 15px rgba(0,112,186,.45), 0 0 35px rgba(0,112,186,.25);
}

/* INFO */
.info-section {
  padding: 0 20px 80px;
}
.info-box {
  max-width: 900px;
  margin: auto;
  background: #111;
  color: white;
  border-radius: 32px;
  padding: 45px;
}
.info-box h3 {
  font-weight: 700;
  margin-bottom: 25px;
}
.info-box ul {
  list-style: none;
  padding: 0;
}
.info-box li {
  margin-bottom: 18px;
  font-size: 17px;
}
.info-box i {
  color: #ff7b00;
  margin-right: 10px;
}

/* RESPONSIVE */
@media (max-width: 900px) {
  .hero h1 { font-size: 46px; }
  .donaciones { grid-template-columns: 1fr; }
}

.tilde {
  color: #ff7b00;
  font-weight: 700;
  margin-right: 10px;
  font-size: 18px;
}

</style>
