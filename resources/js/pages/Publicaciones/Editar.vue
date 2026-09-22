<template>
  <div class="crear-wrapper">
    <div class="crear-container">

      <div class="crear-header">
        <h1>Editar publicación</h1>
        <p>Corregí los datos de tu aviso. Si no subís una foto nueva, se mantiene la que ya tenías.</p>
      </div>

      <div v-if="form.errors.general" class="alert-general">
        <i class="ti ti-alert-circle" /> {{ form.errors.general }}
      </div>

      <form @submit.prevent="submit">
        <div class="panel">

          <div class="panel-header">
            <h3>Datos de la mascota</h3>
          </div>

          <div class="field-row">
            <div class="field">
              <label>Nombre</label>
              <input type="text" v-model="form.nombre" :class="{ 'input-error': form.errors.nombre }" />
              <span v-if="form.errors.nombre" class="field-error"><i class="ti ti-alert-circle" /> {{ form.errors.nombre }}</span>
            </div>
            <div class="field">
              <label>Especie</label>
              <select v-model="form.especie" :class="{ 'input-error': form.errors.especie }">
                <option value="">Seleccionar</option>
                <option value="perro">Perro</option>
                <option value="gato">Gato</option>
              </select>
              <span v-if="form.errors.especie" class="field-error"><i class="ti ti-alert-circle" /> {{ form.errors.especie }}</span>
            </div>
          </div>

          <div class="field-row">
            <div class="field">
              <label>Raza</label>
              <SelectorRazas
                v-model="form.raza"
                :especie="form.especie"
                placeholder="Buscar raza..."
              />
            </div>
            <div class="field">
              <label>Color</label>
              <input type="text" v-model="form.color" />
            </div>
          </div>

          <div class="field-row">
            <div class="field">
              <label>Tamaño</label>
              <select v-model="form.tamano">
                <option value="">Seleccionar</option>
                <option value="pequeño">Pequeño</option>
                <option value="mediano">Mediano</option>
                <option value="grande">Grande</option>
              </select>
            </div>
            <div class="field">
              <label>Sexo</label>
              <select v-model="form.sexo">
                <option value="">Seleccionar</option>
                <option value="macho">Macho</option>
                <option value="hembra">Hembra</option>
                <option value="desconocido">Desconocido</option>
              </select>
            </div>
          </div>

          <div class="panel-header" style="margin-top: 1.5rem;">
            <h3>Datos de la publicación</h3>
          </div>

          <div class="field">
            <label>Título</label>
            <input type="text" v-model="form.titulo" :class="{ 'input-error': form.errors.titulo }" />
            <span v-if="form.errors.titulo" class="field-error"><i class="ti ti-alert-circle" /> {{ form.errors.titulo }}</span>
          </div>

          <div class="field">
            <label>Descripción</label>
            <textarea v-model="form.descripcion" rows="3" />
          </div>

          <div class="field-row">
            <div class="field">
              <label>Fecha del evento</label>
              <input type="date" v-model="form.fecha_evento" />
            </div>
          </div>

          <div class="field">
            <label>Ubicación</label>
            <input type="text" v-model="form.zona" />
          </div>

          <template v-if="publicacion.estado === 'encontrado'">
            <div class="field-row">
              <div class="field">
                <label>Fecha en que la encontraste</label>
                <input type="date" v-model="form.fecha_encontrada" :class="{ 'input-error': form.errors.fecha_encontrada }" />
                <span v-if="form.errors.fecha_encontrada" class="field-error"><i class="ti ti-alert-circle" /> {{ form.errors.fecha_encontrada }}</span>
              </div>
              <div class="field">
                <label>Dónde la encontraste</label>
                <input type="text" v-model="form.ubicacion_encontrada" :class="{ 'input-error': form.errors.ubicacion_encontrada }" />
                <span v-if="form.errors.ubicacion_encontrada" class="field-error"><i class="ti ti-alert-circle" /> {{ form.errors.ubicacion_encontrada }}</span>
              </div>
            </div>
            <div class="field">
              <label>Tu contacto</label>
              <input type="text" v-model="form.contacto" :class="{ 'input-error': form.errors.contacto }" />
              <span v-if="form.errors.contacto" class="field-error"><i class="ti ti-alert-circle" /> {{ form.errors.contacto }}</span>
            </div>
          </template>

          <div class="field">
            <label>Foto de portada actual</label>
            <div class="foto-actual" v-if="publicacion.imagen">
              <img :src="optimizarImagen(publicacion.imagen, { ancho: 200, alto: 200 })" alt="Foto actual">
            </div>
            <div
              class="photo-drop"
              :class="{ 'photo-drop-error': form.errors.imagen }"
              @click="$refs.fotoInput.click()"
            >
              <i class="ti ti-camera" />
              <p v-if="!fotoNombre">Hacé clic para reemplazar la foto de portada (opcional)</p>
              <p v-else class="foto-ok"><i class="ti ti-check" /> {{ fotoNombre }}</p>
              <input ref="fotoInput" type="file" accept="image/*" style="display:none" @change="onFoto" />
            </div>
            <span v-if="form.errors.imagen" class="field-error"><i class="ti ti-alert-circle" /> {{ form.errors.imagen }}</span>
          </div>

          <div class="field">
            <label>Fotos actuales de la galería</label>
            <div class="galeria-actual" v-if="publicacion.imagenes && publicacion.imagenes.length">
              <img v-for="img in publicacion.imagenes" :key="img.id" :src="optimizarImagen(img.url, { ancho: 140, alto: 140 })" alt="Foto galería">
            </div>
            <p v-else class="sin-galeria">Todavía no tenés fotos extra.</p>

            <div class="photo-drop" @click="$refs.fotosExtraInput.click()">
              <i class="ti ti-photo" />
              <p v-if="!fotosExtraNombres.length">Agregar más fotos (opcional)</p>
              <p v-else class="foto-ok"><i class="ti ti-check" /> {{ fotosExtraNombres.length }} foto{{ fotosExtraNombres.length !== 1 ? 's' : '' }} nueva{{ fotosExtraNombres.length !== 1 ? 's' : '' }}</p>
              <input ref="fotosExtraInput" type="file" accept="image/*" multiple style="display:none" @change="onFotosExtra" />
            </div>
          </div>

          <div class="btn-row">
            <Link href="/publicaciones/mias" class="btn-secondary">
              <i class="ti ti-arrow-left" /> Cancelar
            </Link>

            <button type="submit" class="btn-primary" :disabled="form.processing">
              <i v-if="form.processing" class="ti ti-loader-2 spin" />
              {{ form.processing ? 'Guardando...' : 'Guardar cambios' }}
              <i v-if="!form.processing" class="ti ti-check" />
            </button>
          </div>

        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import SelectorRazas from '@/Componentes/SelectorRazas.vue'
import { optimizarImagen } from '@/cloudinary'

const props = defineProps({
  publicacion: {
    type: Object,
    required: true,
  },
})

const fotoNombre = ref('')
const fotosExtraNombres = ref([])

const form = useForm({
  estado:       props.publicacion.estado ?? 'perdido',
  nombre:       props.publicacion.mascota?.nombre ?? '',
  especie:      props.publicacion.mascota?.especie ?? '',
  raza:         props.publicacion.mascota?.raza ?? '',
  color:        props.publicacion.mascota?.color ?? '',
  tamano:       props.publicacion.mascota?.tamano ?? '',
  sexo:         props.publicacion.mascota?.sexo ?? '',
  titulo:       props.publicacion.titulo ?? '',
  descripcion:  props.publicacion.descripcion ?? '',
  fecha_evento: props.publicacion.fecha_evento ? props.publicacion.fecha_evento.substring(0, 10) : '',
  zona:         props.publicacion.zona ?? '',
  imagen:       null,
  imagenes:     [],
  fecha_encontrada:     props.publicacion.fecha_encontrada ? props.publicacion.fecha_encontrada.substring(0, 10) : '',
  ubicacion_encontrada: props.publicacion.ubicacion_encontrada ?? '',
  contacto:             props.publicacion.contacto ?? '',
})

const onFoto = (e) => {
  const file = e.target.files[0]
  if (!file) return

  if (file.size > 2 * 1024 * 1024) {
    form.setError('imagen', 'La imagen no puede superar los 2MB.')
    e.target.value = ''
    return
  }

  form.clearErrors('imagen')
  form.imagen = file
  fotoNombre.value = file.name
}

const onFotosExtra = (e) => {
  const files = Array.from(e.target.files)
  if (!files.length) return

  const invalida = files.find(f => f.size > 2 * 1024 * 1024)
  if (invalida) {
    form.setError('imagenes', `"${invalida.name}" supera los 2MB.`)
    e.target.value = ''
    return
  }

  form.clearErrors('imagenes')
  form.imagenes = files
  fotosExtraNombres.value = files.map(f => f.name)
}

const submit = () => {
  // Laravel necesita el spoofing de _method para recibir PUT con archivos (multipart)
  form.transform((data) => ({ ...data, _method: 'put' }))
      .post(`/publicaciones/${props.publicacion.id}`, {
        forceFormData: true,
      })
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&display=swap');

* { font-family: 'Space Grotesk', sans-serif; }

.crear-wrapper { min-height: 100vh; background: #f4f1ea; padding: 50px 20px 80px; }
.crear-container { max-width: 640px; margin: 0 auto; }

.crear-header { text-align: center; margin-bottom: 28px; }
.crear-header h1 { font-size: 32px; font-weight: 700; color: #111; margin-bottom: 8px; }
.crear-header p { color: #666; font-size: 14px; }

.alert-general {
  display: flex; align-items: center; gap: 8px;
  background: #fff3f0; border: 1.5px solid #f5c4b3; color: #993c1d;
  border-radius: 10px; padding: 12px 16px; margin-bottom: 20px; font-size: 14px;
}

.panel {
  background: #fff; border: 3px solid #111; border-radius: 24px;
  padding: 28px; box-shadow: 6px 6px 0 #111;
}
.panel-header h3 { font-size: 17px; font-weight: 700; color: #111; margin-bottom: 4px; }

.field { margin-bottom: 16px; }
.field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px; }
.field label { display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #888; margin-bottom: 6px; }
.field input, .field select, .field textarea {
  width: 100%; padding: 11px 14px; border-radius: 10px; border: 2px solid #ddd;
  font-size: 14px; font-family: 'Space Grotesk', sans-serif; outline: none; color: #111;
}
.field input:focus, .field select:focus, .field textarea:focus { border-color: #ff7b00; }
.input-error { border-color: #ff3b3b !important; }
.field-error { display: flex; align-items: center; gap: 4px; font-size: 12px; color: #ff3b3b; margin-top: 4px; }

.foto-actual { display: flex; gap: 8px; margin-bottom: 10px; }
.foto-actual img { width: 90px; height: 90px; object-fit: cover; border-radius: 10px; border: 2px solid #111; }

.galeria-actual { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 10px; }
.galeria-actual img { width: 70px; height: 70px; object-fit: cover; border-radius: 8px; border: 2px solid #ddd; }
.sin-galeria { font-size: 13px; color: #999; margin-bottom: 10px; }

.photo-drop {
  border: 2px dashed #ddd; border-radius: 14px; padding: 20px; text-align: center;
  cursor: pointer; color: #999; transition: .15s;
}
.photo-drop:hover { border-color: #ff7b00; color: #ff7b00; }
.photo-drop i { font-size: 24px; display: block; margin-bottom: 6px; }
.photo-drop p { font-size: 13px; margin: 0; }
.photo-drop-error { border-color: #ff3b3b; }
.foto-ok { color: #22c55e !important; font-weight: 600; }

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