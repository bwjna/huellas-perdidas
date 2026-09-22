<template>
  <div class="login-wrapper">

    <!-- PANEL IZQUIERDO -->
    <div class="left">
      <img :src="logoUrl" alt="Logo Huellas Perdidas">
      <h1>HUELLAS <span>PERDIDAS</span></h1>
      <p>Los buscamos. Los encontramos. Los reunimos.</p>
    </div>

    <!-- PANEL DERECHO -->
    <div class="right">
      <div class="login-box">
        <h2>Iniciar sesión</h2>

        <div v-if="errors && errors.email" class="alert-error">
          {{ errors.email }}
        </div>
        <div v-if="errors && errors.password" class="alert-error">
          {{ errors.password }}
        </div>

        <form @submit.prevent="submit">

          <div class="input-group">
            <input
              type="email"
              v-model="form.email"
              placeholder="Email"
              required
            >
          </div>

          <div class="input-group">
            <input
              type="password"
              v-model="form.password"
              placeholder="Contraseña"
              required
            >
          </div>

          <button type="submit" class="login-btn">Iniciar Sesión</button>

          <a href="/auth/google" class="google-btn">
            <img src="https://www.google.com/favicon.ico" width="18" height="18" alt="Google">
            Iniciar sesión con Google
          </a>

          <button type="button" @click="handleFacebookLogin" class="facebook-btn">
            <span class="fb-icon-wrapper">
            <i class="ti ti-brand-facebook-filled"></i>
            </span>
            Continuar con Facebook
          </button>

          <div class="extra">
            <p>¿No tenés cuenta? <a href="/registro">Registrate</a></p>
            <a href="/olvide-password">¿Olvidaste tu contraseña?</a>
          </div>

        </form>
      </div>
    </div>

  </div>
</template>

<script setup>
import { onMounted } from 'vue' // Importar onMounted
import { useForm, router } from '@inertiajs/vue3' // Importar router

const logoUrl = new URL('/img/logo_fava.png', import.meta.url).href

defineProps({
  errors: {
    type: Object,
    default: () => ({})
  }
})

const form = useForm({
  email: '',
  password: ''
})

const submit = () => {
  form.post('/login')
}

// ---------------------------------------------------
// INTEGRACIÓN DE FACEBOOK SDK
// ---------------------------------------------------

onMounted(() => {
  // Inicializar SDK de Facebook si aún no existe en la ventana
  if (!window.FB) {
    window.fbAsyncInit = function() {
      window.FB.init({
        appId      : import.meta.env.VITE_FACEBOOK_APP_ID, // Recuerda tener esto en tu .env
        cookie     : true,
        xfbml      : true,
        version    : 'v19.0'
      })
    }

    // Cargar script del SDK asincrónicamente
    ;(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0]
      if (d.getElementById(id)) return
      js = d.createElement(s); js.id = id
      js.src = "https://connect.facebook.net/es_LA/sdk.js"
      fjs.parentNode.insertBefore(js, fjs)
    }(document, 'script', 'facebook-jssdk'))
  }
})

const handleFacebookLogin = () => {
  window.FB.login((response) => {
    if (response.status === 'connected') {
      const accessToken = response.authResponse.accessToken

      // Enviar el token a tu controlador de Laravel (Ruta a crear en web.php)
      router.post('/auth/facebook/callback', {
        access_token: accessToken
      }, {
        onSuccess: () => {
          // El POST de Facebook navega por Inertia sin recargar el documento,
          // así que el navbar (Blade) no se entera de la sesión nueva.
          // Recargamos para que muestre el usuario logueado y su avatar.
          window.location.reload()
        },
        onError: (errors) => console.error('Errores del servidor:', errors)
      })
    } else {
      console.log('El usuario canceló el inicio de sesión o no lo autorizó.')
    }
  }, { scope: 'public_profile,email' })
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

.login-wrapper {
  display: flex;
  width: 100%;
  min-height: 100vh;
  background: #0d0d0d;
  color: white;
  font-family: 'Poppins', sans-serif;
}

/* ──────────────── LEFT ──────────────── */
.left {
  width: 50%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background: radial-gradient(circle at top, #1a1a1a, #0d0d0d);
  text-align: center;
  padding: 40px;
}

.left img {
  width: 320px;
  height: 320px; 
  object-fit: contain;
  margin-bottom: 10px; 
}

.left h1 {
  font-size: 32px;
  font-weight: 600;
  letter-spacing: 2px;
  color: white;
}

.left h1 span {
  color: #ff7a2f;
}

.left p {
  margin-top: 10px;
  color: #aaa;
  font-size: 14px;
}

/* ──────────────── RIGHT ──────────────── */
.right {
  width: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  background: #0d0d0d;
}

/* ──────────────── LOGIN BOX ──────────────── */
.login-box {
  width: 320px;
  padding: 35px;
  background: #1a1a1a;
  border-radius: 20px;
  box-shadow: 0 0 25px rgba(255, 122, 47, 0.15);
  animation: fadeIn 0.8s ease;
}

.login-box h2 {
  text-align: center;
  margin-bottom: 20px;
  font-size: 20px;
  font-weight: 600;
  color: white;
}

/* ──────────────── ERRORES ──────────────── */
.alert-error {
  background: rgba(220, 53, 69, 0.12);
  border: 1px solid rgba(220, 53, 69, 0.35);
  color: #ff6b6b;
  padding: 9px 13px;
  border-radius: 10px;
  font-size: 13px;
  margin-bottom: 12px;
}

/* ──────────────── INPUTS ──────────────── */
.input-group {
  margin-bottom: 15px;
}

.input-group input {
  width: 100%;
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid #333;
  background: #0d0d0d;
  color: white;
  outline: none;
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.input-group input::placeholder {
  color: #555;
}

.input-group input:focus {
  border-color: #ff7a2f;
  box-shadow: 0 0 6px rgba(255, 122, 47, 0.4);
}

/* ──────────────── BOTÓN PRINCIPAL ──────────────── */
.login-btn {
  width: 100%;
  padding: 12px;
  border: none;
  border-radius: 10px;
  background: linear-gradient(135deg, #ff7a2f, #ff9a3c);
  color: white;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
  font-family: 'Poppins', sans-serif;
}

.login-btn:hover {
  transform: scale(1.03);
  box-shadow: 0 4px 15px rgba(255, 122, 47, 0.35);
}

/* ──────────────── BOTÓN GOOGLE ──────────────── */
.google-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  padding: 11px;
  margin-top: 12px;
  border: 1px solid #333;
  border-radius: 10px;
  background: transparent;
  color: #ccc;
  font-size: 13px;
  font-weight: 500;
  font-family: 'Poppins', sans-serif;
  text-decoration: none;
  transition: border-color 0.2s, background 0.2s, color 0.2s;
}

.google-btn:hover {
  border-color: #ff7a2f;
  background: rgba(255, 122, 47, 0.06);
  color: white;
}

/* ──────────────── BOTÓN FACEBOOK ──────────────── */
.facebook-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  width: 100%;
  padding: 11px;
  margin-top: 12px;
  border: 1px solid #333;
  border-radius: 10px;
  background: transparent;
  color: #ccc;
  font-size: 13px;
  font-weight: 500;
  font-family: 'Poppins', sans-serif;
  text-decoration: none;
  transition: border-color 0.2s, background 0.2s, color 0.2s;
}

.facebook-btn:hover {
  border-color: #1877f2;
  background: rgba(24, 119, 242, 0.08);
  color: white;
}

/* Contenedor circular idéntico al estilo del botón de Google */
.fb-icon-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 20px;
  height: 20px;
  background-color: #ffffff;
  border-radius: 50%;
}

.fb-icon-wrapper i {
  font-size: 14px;
  color: #1877f2;
}

/* ──────────────── EXTRA ──────────────── */
.extra {
  text-align: center;
  margin-top: 16px;
  font-size: 12px;
}

.extra p {
  color: #aaa;
  margin-bottom: 6px;
}

.extra a {
  color: #ff7a2f;
  text-decoration: none;
}

.extra a:hover {
  text-decoration: underline;
}

/* ──────────────── ANIMACIÓN ──────────────── */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ──────────────── RESPONSIVE ──────────────── */
@media (max-width: 768px) {
  .login-wrapper {
    flex-direction: column;
  }

  .left,
  .right {
    width: 100%;
  }

  .left {
    padding: 50px 30px 30px;
  }

  .right {
    padding: 30px 20px 50px;
  }

  .login-box {
    width: 100%;
    max-width: 360px;
  }
}
</style>