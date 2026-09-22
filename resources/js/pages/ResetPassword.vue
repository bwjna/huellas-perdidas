<template>
  <div class="wrapper">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="container">
      <div class="header">
        <h1>Nueva <span class="highlight">contraseña</span></h1>
        <p>Elegí una contraseña nueva para tu cuenta</p>
      </div>

      <div v-if="Object.keys(form.errors).length" class="alert-error">
        <ul>
          <li v-for="(error, campo) in form.errors" :key="campo">{{ error }}</li>
        </ul>
      </div>

      <form @submit.prevent="submit">
        <div class="panel">
          <div class="field">
            <label>Email</label>
            <input type="email" v-model="form.email" disabled />
          </div>

          <div class="field">
            <label>Nueva contraseña</label>
            <div class="password-wrap">
              <input :type="showPassword ? 'text' : 'password'" v-model="form.password" autocomplete="off" />
              <button type="button" class="toggle-password" @click="showPassword = !showPassword" tabindex="-1">
                <i :class="showPassword ? 'ti ti-eye-off' : 'ti ti-eye'" />
              </button>
            </div>
          </div>

          <div class="field">
            <label>Confirmar contraseña</label>
            <div class="password-wrap">
              <input :type="showConfirm ? 'text' : 'password'" v-model="form.password_confirmation" autocomplete="off" />
              <button type="button" class="toggle-password" @click="showConfirm = !showConfirm" tabindex="-1">
                <i :class="showConfirm ? 'ti ti-eye-off' : 'ti ti-eye'" />
              </button>
            </div>
          </div>

          <button type="submit" class="btn-primary" :disabled="form.processing">
            <i v-if="form.processing" class="ti ti-loader-2 spin" />
            {{ form.processing ? 'Guardando...' : 'Cambiar contraseña' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  token: { type: String, required: true },
  email: { type: String, default: '' },
})

const showPassword = ref(false)
const showConfirm  = ref(false)

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post('/reset-password')
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
.header p { color: #666; font-size: 14px; }

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
.field input:disabled { background: #f4f1ea; color: #999; }

.password-wrap { position: relative; }
.password-wrap input { padding-right: 42px; }
.toggle-password {
  position: absolute; right: 4px; top: 50%; transform: translateY(-50%);
  width: 34px; height: 34px; border: none; background: transparent; color: #999;
  cursor: pointer; display: flex; align-items: center; justify-content: center;
  font-size: 18px; border-radius: 8px;
}
.toggle-password:hover { color: #ff7b00; background: #f4f1ea; }

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

.alert-error {
  background: #fff3f0; border: 1.5px solid #f5c4b3; color: #993c1d;
  border-radius: 12px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px;
}
.alert-error ul { margin: 0; padding-left: 16px; }
</style>