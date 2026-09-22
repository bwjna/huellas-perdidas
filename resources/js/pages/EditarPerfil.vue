<template>
  <div class="perfil-wrapper">
    <div class="perfil-container">

      <div class="perfil-header">
        <h1>Mi perfil</h1>
        <p>Mantené tu teléfono actualizado para que puedan contactarte por WhatsApp cuando publiques una mascota.</p>
      </div>

      <div v-if="mostrarExito" class="success-box">
        <i class="ti ti-circle-check" /> ¡Perfil actualizado!
      </div>

      <form @submit.prevent="submit">
        <div class="panel">

          <div class="field-row">
            <div class="field">
              <label>Nombre</label>
              <input type="text" v-model="form.nombre" :class="{ 'input-error': form.errors.nombre }" />
              <span v-if="form.errors.nombre" class="field-error"><i class="ti ti-alert-circle" /> {{ form.errors.nombre }}</span>
            </div>
            <div class="field">
              <label>Apellido</label>
              <input type="text" v-model="form.apellido" :class="{ 'input-error': form.errors.apellido }" />
              <span v-if="form.errors.apellido" class="field-error"><i class="ti ti-alert-circle" /> {{ form.errors.apellido }}</span>
            </div>
          </div>

          <div class="field">
            <label>Nombre de usuario</label>
            <input type="text" :value="usuario.nombre_usuario" disabled />
            <span class="field-hint">El nombre de usuario no se puede cambiar</span>
          </div>

          <div class="field">
            <label>Email</label>
            <input type="text" :value="usuario.email" disabled />
            <span class="field-hint">Para cambiar tu email, contactate con nosotros</span>
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
                :class="{ 'input-error': form.errors.telefono }"
                placeholder="2344 123456"
              />
            </div>
            <span v-if="form.errors.telefono" class="field-error"><i class="ti ti-alert-circle" /> {{ form.errors.telefono }}</span>
            <span v-else class="field-hint">Así te van a poder contactar por WhatsApp desde tus publicaciones</span>
          </div>

          <div class="btn-row">
            <a href="/publicaciones/mias" class="btn-secondary">
              <i class="ti ti-arrow-left" /> Volver
            </a>
            <button type="submit" class="btn-primary" :disabled="form.processing">
              <i v-if="form.processing" class="ti ti-loader-2 spin" />
              {{ form.processing ? 'Guardando...' : 'Guardar cambios' }}
            </button>
          </div>

        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { PAISES_TELEFONO, separarTelefono, armarTelefono } from '@/paises'

const props = defineProps({
  usuario: {
    type: Object,
    required: true,
  },
})

const mostrarExito = ref(false)

// El teléfono ya viene guardado como "+542344123456"; lo separamos para
// precargar el selector de país y el número por separado.
const { codigo: codigoInicial, numero: numeroInicial } = separarTelefono(props.usuario.telefono)
const codigoPais  = ref(codigoInicial)
const numeroLocal = ref(numeroInicial)

const form = useForm({
  nombre:   props.usuario.nombre ?? '',
  apellido: props.usuario.apellido ?? '',
  telefono: props.usuario.telefono ?? '',
})

const submit = () => {
  form.telefono = armarTelefono(codigoPais.value, numeroLocal.value)
  form.put('/perfil', {
    preserveScroll: true,
    onSuccess: () => {
      mostrarExito.value = true
      setTimeout(() => { mostrarExito.value = false }, 3000)
    },
  })
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&display=swap');

* { font-family: 'Space Grotesk', sans-serif; }

.perfil-wrapper { min-height: 100vh; background: #f4f1ea; padding: 50px 20px 80px; }
.perfil-container { max-width: 560px; margin: 0 auto; }

.perfil-header { text-align: center; margin-bottom: 28px; }
.perfil-header h1 { font-size: 32px; font-weight: 700; color: #111; margin-bottom: 8px; }
.perfil-header p { color: #666; font-size: 14px; line-height: 1.5; }

.success-box {
  display: flex; align-items: center; gap: 8px; background: #f0faf4;
  border: 1px solid #a3d9b8; border-radius: 10px; padding: 10px 14px;
  margin-bottom: 1rem; font-size: 13px; color: #2d6a4a;
}

.panel {
  background: #fff; border: 3px solid #111; border-radius: 24px;
  padding: 28px; box-shadow: 6px 6px 0 #111;
}

.field { margin-bottom: 16px; }
.field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px; }
.field label { display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #888; margin-bottom: 6px; }
.field input {
  width: 100%; padding: 11px 14px; border-radius: 10px; border: 2px solid #ddd;
  font-size: 14px; font-family: 'Space Grotesk', sans-serif; outline: none; color: #111;
}
.field input:focus { border-color: #ff7b00; }
.field input:disabled { background: #f4f1ea; color: #999; cursor: not-allowed; }
.input-error { border-color: #ff3b3b !important; }
.field-error { display: flex; align-items: center; gap: 4px; font-size: 12px; color: #ff3b3b; margin-top: 4px; }
.field-hint { display: block; font-size: 12px; color: #999; margin-top: 4px; }

.phone-row { display: flex; gap: 8px; }
.phone-code {
  flex: 0 0 108px;
  padding: 11px 10px;
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

.btn-row { display: flex; gap: 12px; margin-top: 24px; }
.btn-secondary, .btn-primary {
  flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
  padding: 13px; border-radius: 12px; font-weight: 700; font-size: 14px;
  border: 2px solid #111; cursor: pointer; text-decoration: none;
  font-family: 'Space Grotesk', sans-serif; transition: .15s;
}
.btn-secondary { background: #fff; color: #111; }
.btn-secondary:hover { background: #f4f1ea; }
.btn-primary { background: #ff7b00; color: #fff; border-color: #ff7b00; }
.btn-primary:hover { background: #e56d00; }
.btn-primary:disabled { opacity: .6; cursor: not-allowed; }

@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.spin { animation: spin .8s linear infinite; }

@media (max-width: 600px) {
  .field-row { grid-template-columns: 1fr; }
  .panel { padding: 20px; }
}
</style>