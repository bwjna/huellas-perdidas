<template>
    <div class="perfil-wrapper">
        <div class="perfil-container">

            <div class="step-container">
  <!-- Imagen tipo cuadro reemplazando el icono -->
  <div class="image-frame">
    <img src="/public/img/perro-log.jpeg" alt="Asistente de soporte perruno" />
  </div>

  <h2>¡Un último paso!</h2>
  <p>
    Ingresaste con éxito, pero necesitamos tu número de teléfono para
    que la comunidad pueda contactarte por WhatsApp cuando publiques
    o busques una mascota.
  </p>
</div>

            <form @submit.prevent="submit">
                <div class="panel">

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
                                placeholder="Ej: 2344 123456"
                            />
                        </div>
                        <span v-if="form.errors.telefono" class="field-error">
                            <i class="ti ti-alert-circle" /> {{ form.errors.telefono }}
                        </span>
                        <span v-else class="field-hint">Seleccioná tu país e ingresá el número sin el 0 ni el 15</span>
                    </div>

                    <div class="btn-row">
                        <button type="submit" class="btn-primary" :disabled="form.processing">
                            <i v-if="form.processing" class="ti ti-loader-2 spin" />
                            {{ form.processing ? 'Guardando...' : 'Guardar y Entrar' }}
                        </button>
                    </div>

                    <div class="cancel-row">
                        <button type="button" @click="cancelar" class="btn-cancel">
                            Hacerlo en otro momento (Cerrar sesión)
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import { PAISES_TELEFONO, separarTelefono, armarTelefono } from '@/paises'

// Por defecto intentamos separar si viniera algo precargado, o dejamos Argentina (54) por defecto
const { codigo: codigoInicial, numero: numeroInicial } = separarTelefono('')
const codigoPais  = ref(codigoInicial || '54')
const numeroLocal = ref(numeroInicial || '')

const form = useForm({
    telefono: '',
})

const submit = () => {
    // Unimos el código de país seleccionado con el número local ingresado
    form.telefono = armarTelefono(codigoPais.value, numeroLocal.value)
    
    form.post(route('perfil.completar.guardar'), {
        preserveScroll: true,
    })
}

const cancelar = () => {
    router.post(route('logout'))
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&display=swap');

* { font-family: 'Space Grotesk', sans-serif; }

.perfil-wrapper { min-height: 100vh; background: #f4f1ea; display: flex; align-items: center; justify-content: center; padding: 20px; }
.perfil-container { max-width: 480px; width: 100%; }

.perfil-header { text-align: center; margin-bottom: 24px; }
.badge-icon { font-size: 42px; margin-bottom: 8px; }
.perfil-header h1 { font-size: 28px; font-weight: 700; color: #111; margin-bottom: 8px; }
.perfil-header p { color: #555; font-size: 14px; line-height: 1.5; }

.panel {
    background: #fff; border: 3px solid #111; border-radius: 24px;
    padding: 28px; box-shadow: 6px 6px 0 #111;
}

.field { margin-bottom: 20px; }
.field label { display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #888; margin-bottom: 6px; }

.phone-row { display: flex; gap: 8px; }
.phone-code {
    flex: 0 0 115px;
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
.phone-number { 
    flex: 1; 
    min-width: 0;
    padding: 11px 14px; 
    border-radius: 10px; 
    border: 2px solid #ddd;
    font-size: 14px; 
    outline: none; 
    color: #111;
}
.phone-number:focus { border-color: #ff7b00; }

.input-error { border-color: #ff3b3b !important; }
.field-error { display: flex; align-items: center; gap: 4px; font-size: 12px; color: #ff3b3b; margin-top: 6px; font-weight: 700; }
.field-hint { display: block; font-size: 12px; color: #888; margin-top: 6px; }

.btn-row { margin-top: 24px; }
.btn-primary {
    width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px;
    padding: 13px; border-radius: 12px; font-weight: 700; font-size: 15px;
    border: 2px solid #ff7b00; cursor: pointer; text-decoration: none;
    background: #ff7b00; color: #fff;
    font-family: 'Space Grotesk', sans-serif; transition: .15s;
    box-shadow: 3px 3px 0 #111;
}
.btn-primary:hover { background: #e56d00; transform: translateY(-1px); }
.btn-primary:disabled { opacity: .6; cursor: not-allowed; transform: none; }

.cancel-row { margin-top: 16px; text-align: center; }
.btn-cancel {
    background: none; border: none; color: #888; font-size: 13px; font-weight: 700;
    text-decoration: underline; cursor: pointer; font-family: 'Space Grotesk', sans-serif;
}
.btn-cancel:hover { color: #111; }

.step-container {
    text-align: center;
    font-family: system-ui, -apple-system, sans-serif;
    max-width: 550px;
    margin: 0 auto;
    padding: 2rem;
    background-color: #fcfbf9; /* Mantiene el fondo clarito de tu diseño original */
  }

  .image-frame {
    width: 140px; 
    height: 140px;
    margin: 0 auto 1.5rem;
    border-radius: 16px; /* Suaviza las esquinas del cuadro */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15); /* Crea el efecto de profundidad/cuadro */
    border: 4px solid #ffffff; /* Marco blanco */
    overflow: hidden;
  }

  .image-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Centra y recorta la imagen sin deformarla */
  }

  .step-container h2 {
    font-size: 1.8rem;
    color: #222;
    margin-bottom: 1rem;
    font-weight: bold;
  }

  .step-container p {
    font-size: 1.1rem;
    color: #555;
    line-height: 1.6;
  }

@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.spin { animation: spin .8s linear infinite; }

@media (max-width: 480px) {
    .panel { padding: 20px; }
}
</style>