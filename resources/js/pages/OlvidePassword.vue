<template>
  <div class="wrapper">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="container">
      <div class="header">
        <h1>Recuperar <span class="highlight">contraseña</span></h1>
        <p>Ingresá tu email y te mandamos un link para crear una nueva contraseña</p>
      </div>

      <div v-if="$page.props.flash?.success" class="alert-success">
        <i class="ti ti-circle-check" /> {{ $page.props.flash.success }}
      </div>

      <form @submit.prevent="submit">
        <div class="panel">
          <div class="field">
            <label>Email</label>
            <input type="email" v-model="form.email" :class="{ 'input-error': form.errors.email }" autocomplete="off" />
            <span v-if="form.errors.email" class="field-error"><i class="ti ti-alert-circle" /> {{ form.errors.email }}</span>
          </div>

          <button type="submit" class="btn-primary" :disabled="form.processing">
            <i v-if="form.processing" class="ti ti-loader-2 spin" />
            {{ form.processing ? 'Enviando...' : 'Mandar link' }}
          </button>

          <p class="login-link"><a href="/login"><i class="ti ti-arrow-left" /> Volver a iniciar sesión</a></p>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  email: '',
})

const submit = () => {
  form.post('/olvide-password')
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&display=swap');

* { font-family: 'Space Grotesk', sans-serif; }

.wrapper {
  min-height: 100vh;
  background: #f4f1ea;
  position: relative;
  overflow: hidden;
  padding: 50px 20px 80px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.blob { position: absolute; border-radius: 50%; filter: blur(70px); opacity: .35; z-index: 0; }
.blob-1 { width: 340px; height: 340px; background: #ffb98a; top: -90px; left: -90px; }
.blob-2 { width: 260px; height: 260px; background: #ffe0c2; bottom: -70px; right: -50px; }

.container { position: relative; z-index: 1; max-width: 440px; width: 100%; }

.header { text-align: center; margin-bottom: 24px; }
.header h1 { font-size: 32px; font-weight: 700; color: #111; margin-bottom: 8px; }
.highlight { color: #ff7b00; }
.header p { color: #666; font-size: 14px; line-height: 1.5; }

.panel {
  background: #fff;
  border: 3px solid #111;
  border-radius: 24px;
  padding: 28px;
  box-shadow: 6px 6px 0 #111;
}

.field { margin-bottom: 18px; }
.field label {
  display: block; font-size: 12px; font-weight: 700; text-transform: uppercase;
  letter-spacing: .04em; color: #888; margin-bottom: 6px;
}
.field input {
  width: 100%; padding: 11px 14px; border-radius: 10px; border: 2px solid #ddd;
  font-size: 14px; font-family: 'Space Grotesk', sans-serif; outline: none; color: #111;
}
.field input:focus { border-color: #ff7b00; }
.input-error { border-color: #ff3b3b !important; }
.field-error { display: flex; align-items: center; gap: 4px; font-size: 12px; color: #ff3b3b; margin-top: 4px; }

.btn-primary {
  width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;
  padding: 14px; border-radius: 12px; background: #ff7b00; color: #fff;
  border: 2px solid #111; font-weight: 700; font-size: 15px; cursor: pointer;
  transition: .15s; font-family: 'Space Grotesk', sans-serif;
}
.btn-primary:hover { background: #e56d00; transform: translate(-2px,-2px); box-shadow: 4px 4px 0 #111; }
.btn-primary:disabled { opacity: .6; cursor: not-allowed; }

@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.spin { animation: spin .8s linear infinite; }

.login-link { text-align: center; margin-top: 16px; font-size: 13px; }
.login-link a { color: #666; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
.login-link a:hover { color: #ff7b00; }

.alert-success {
  display: flex; align-items: center; gap: 8px; background: #f0faf4;
  border: 1.5px solid #a3d9b8; color: #2d6a4a; border-radius: 12px;
  padding: 12px 16px; margin-bottom: 16px; font-size: 13px;
}
</style>