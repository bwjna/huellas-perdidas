<template>
  <div class="registro-wrapper">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="registro-container">
      <div class="registro-header">
        <h1>Crear <span class="highlight">cuenta</span></h1>
        <p>Sumate a la comunidad de Huellas Perdidas</p>
      </div>

      <!-- Errores globales -->
      <div v-if="Object.keys(errors).length" class="alert-general">
        <i class="ti ti-alert-circle" />
        <ul>
          <li v-for="(error, campo) in errors" :key="campo">{{ error }}</li>
        </ul>
      </div>

      <!-- Sugerencias de nombre de usuario -->
      <div v-if="sugerencias && sugerencias.length" class="alert-sugerencias">
        <p>Ese nombre de usuario ya existe. Probá con:</p>
        <div class="sugerencias-container">
          <button
            v-for="s in sugerencias"
            :key="s"
            type="button"
            class="sugerencia-btn"
            @click="form.nombre_usuario = s"
          >{{ s }}</button>
        </div>
      </div>

      <form @submit.prevent="submit">
        <div class="panel">

          <div class="field-row">
            <div class="field">
              <label>Nombre</label>
              <input type="text" v-model="form.nombre" :class="{ 'input-error': errors.nombre }" autocomplete="off" />
              <span v-if="errors.nombre" class="field-error"><i class="ti ti-alert-circle" /> {{ errors.nombre }}</span>
            </div>
            <div class="field">
              <label>Apellido</label>
              <input type="text" v-model="form.apellido" :class="{ 'input-error': errors.apellido }" autocomplete="off" />
              <span v-if="errors.apellido" class="field-error"><i class="ti ti-alert-circle" /> {{ errors.apellido }}</span>
            </div>
          </div>

          <div class="field-row">
            <div class="field">
              <label>Nombre de usuario</label>
              <input type="text" v-model="form.nombre_usuario" :class="{ 'input-error': errors.nombre_usuario }" autocomplete="off" />
              <span v-if="errors.nombre_usuario" class="field-error"><i class="ti ti-alert-circle" /> {{ errors.nombre_usuario }}</span>
            </div>
            <div class="field">
              <label>Email</label>
              <input type="email" v-model="form.email" :class="{ 'input-error': errors.email }" autocomplete="off" />
              <span v-if="errors.email" class="field-error"><i class="ti ti-alert-circle" /> {{ errors.email }}</span>
            </div>
          </div>

          <div class="field">
            <label>Teléfono / WhatsApp</label>
            <div class="phone-row">
              <select v-model="codigoPais" class="phone-code">
                <option v-for="p in PAISES_TELEFONO" :key="p.codigo" :value="p.codigo">
                  {{ p.bandera }} +{{ p.codigo }}
                </option>
              </select>
              <input
                type="tel"
                v-model="numeroLocal"
                class="phone-number"
                :class="{ 'input-error': errors.telefono }"
                placeholder="2344 123456"
                autocomplete="off"
              />
            </div>
            <span v-if="errors.telefono" class="field-error"><i class="ti ti-alert-circle" /> {{ errors.telefono }}</span>
            <span v-else class="field-hint">Así te pueden contactar por WhatsApp si alguien reconoce una mascota tuya</span>
          </div>

          <div class="field-row">
            <div class="field">
              <label>Contraseña</label>
              <div class="password-wrap">
                <input :type="showPassword ? 'text' : 'password'" v-model="form.password" :class="{ 'input-error': errors.password }" />
                <button type="button" class="toggle-password" @click="showPassword = !showPassword" tabindex="-1">
                  <i :class="showPassword ? 'ti ti-eye-off' : 'ti ti-eye'" />
                </button>
              </div>
              <span v-if="errors.password" class="field-error"><i class="ti ti-alert-circle" /> {{ errors.password }}</span>
            </div>
            <div class="field">
              <label>Confirmar contraseña</label>
              <div class="password-wrap">
                <input :type="showConfirm ? 'text' : 'password'" v-model="form.password_confirmation" />
                <button type="button" class="toggle-password" @click="showConfirm = !showConfirm" tabindex="-1">
                  <i :class="showConfirm ? 'ti ti-eye-off' : 'ti ti-eye'" />
                </button>
              </div>
            </div>
          </div>

          <button type="submit" class="btn-primary" :disabled="form.processing">
            <i v-if="form.processing" class="ti ti-loader-2 spin" />
            {{ form.processing ? 'Creando cuenta...' : 'Registrarse' }}
          </button>

          <p class="login-link">¿Ya tenés cuenta? <a href="/login">Iniciá sesión</a></p>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { PAISES_TELEFONO, PAIS_POR_DEFECTO, armarTelefono } from '@/paises'

defineProps({
  errors: {
    type: Object,
    default: () => ({})
  },
  sugerencias: {
    type: Array,
    default: () => []
  }
})

const showPassword = ref(false)
const showConfirm  = ref(false)
const codigoPais   = ref(PAIS_POR_DEFECTO)
const numeroLocal  = ref('')

const form = useForm({
  nombre: '',
  apellido: '',
  nombre_usuario: '',
  email: '',
  telefono: '',
  password: '',
  password_confirmation: ''
})

const submit = () => {
  form.telefono = armarTelefono(codigoPais.value, numeroLocal.value)
  form.post('/registro')
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&display=swap');

* { font-family: 'Space Grotesk', sans-serif; }

.registro-wrapper {
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
.blob-1 { width: 380px; height: 380px; background: #ffb98a; top: -100px; left: -100px; }
.blob-2 { width: 300px; height: 300px; background: #ffe0c2; bottom: -80px; right: -60px; }

.registro-container {
  position: relative;
  z-index: 1;
  max-width: 620px;
  width: 100%;
}

.registro-header { text-align: center; margin-bottom: 24px; }
.registro-header h1 { font-size: 36px; font-weight: 700; color: #111; margin-bottom: 8px; }
.highlight { color: #ff7b00; }
.registro-header p { color: #666; font-size: 15px; }

.panel {
  background: #fff;
  border: 3px solid #111;
  border-radius: 24px;
  padding: 32px;
  box-shadow: 6px 6px 0 #111;
}

.field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px; }
.field { margin-bottom: 16px; }
.field label {
  display: block;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .04em;
  color: #888;
  margin-bottom: 6px;
}
.field input, .field select {
  width: 100%;
  padding: 11px 14px;
  border-radius: 10px;
  border: 2px solid #ddd;
  font-size: 14px;
  font-family: 'Space Grotesk', sans-serif;
  outline: none;
  color: #111;
  background: #fff;
}
.field input:focus, .field select:focus { border-color: #ff7b00; }
.input-error { border-color: #ff3b3b !important; }
.field-error { display: flex; align-items: center; gap: 4px; font-size: 12px; color: #ff3b3b; margin-top: 4px; }
.field-hint { display: block; font-size: 12px; color: #999; margin-top: 4px; }

.phone-row { display: flex; gap: 8px; }
.phone-code {
  flex: 0 0 108px;
  padding: 11px 8px;
  border-radius: 10px;
  border: 2px solid #ddd;
  font-size: 14px;
  font-family: 'Space Grotesk', sans-serif;
  outline: none;
  color: #111;
  background: #fff;
  cursor: pointer;
}
.phone-code:focus { border-color: #ff7b00; }
.phone-number { flex: 1; min-width: 0; }

.password-wrap { position: relative; }
.password-wrap input { padding-right: 42px; }
.toggle-password {
  position: absolute;
  right: 4px;
  top: 50%;
  transform: translateY(-50%);
  width: 34px;
  height: 34px;
  border: none;
  background: transparent;
  color: #999;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  border-radius: 8px;
}
.toggle-password:hover { color: #ff7b00; background: #f4f1ea; }

.btn-primary {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 14px;
  border-radius: 12px;
  background: #ff7b00;
  color: #fff;
  border: 2px solid #111;
  font-weight: 700;
  font-size: 15px;
  cursor: pointer;
  transition: .15s;
  font-family: 'Space Grotesk', sans-serif;
  margin-top: 8px;
}
.btn-primary:hover { background: #e56d00; transform: translate(-2px, -2px); box-shadow: 4px 4px 0 #111; }
.btn-primary:disabled { opacity: .6; cursor: not-allowed; }

@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.spin { animation: spin .8s linear infinite; }

.login-link { text-align: center; margin-top: 16px; font-size: 13px; color: #666; }
.login-link a { color: #ff7b00; font-weight: 700; text-decoration: none; }
.login-link a:hover { text-decoration: underline; }

.alert-general {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  background: #fff3f0;
  border: 1.5px solid #f5c4b3;
  color: #993c1d;
  border-radius: 12px;
  padding: 12px 16px;
  margin-bottom: 16px;
  font-size: 13px;
}
.alert-general ul { margin: 0; padding-left: 16px; }

.alert-sugerencias {
  background: #fff8e8;
  border: 1.5px solid #ffdd8a;
  color: #8a6300;
  border-radius: 12px;
  padding: 12px 16px;
  margin-bottom: 16px;
  font-size: 13px;
}
.alert-sugerencias p { margin-bottom: 8px; font-weight: 600; }
.sugerencias-container { display: flex; gap: 8px; flex-wrap: wrap; }
.sugerencia-btn {
  padding: 5px 14px;
  font-size: 12px;
  font-weight: 700;
  border-radius: 999px;
  background: #fff;
  border: 2px solid #ff7b00;
  color: #ff7b00;
  cursor: pointer;
  font-family: 'Space Grotesk', sans-serif;
  transition: .15s;
}
.sugerencia-btn:hover { background: #ff7b00; color: #fff; }

@media (max-width: 600px) {
  .field-row { grid-template-columns: 1fr; gap: 0; }
  .panel { padding: 22px; }
  .registro-header h1 { font-size: 28px; }
}
</style>