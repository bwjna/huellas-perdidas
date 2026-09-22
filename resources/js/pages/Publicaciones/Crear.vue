<template>
  <div class="page-bg">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="glass-card">
      <div class="card-title">{{ tituloForm }}</div>

      <!-- Steps nav -->
      <div class="steps-nav">
        <div class="step-item">
          <div class="step-circle" :class="{ active: step === 1, done: step > 1 }">
            <i v-if="step > 1" class="ti ti-check" style="font-size:12px" />
            <span v-else>1</span>
          </div>
          <span class="step-label" :class="{ active: step === 1, done: step > 1 }">Mascota</span>
        </div>
        <div class="step-line" :class="{ done: step > 1 }" />
        <div class="step-item">
          <div class="step-circle" :class="{ active: step === 2, done: step > 2 }">
            <i v-if="step > 2" class="ti ti-check" style="font-size:12px" />
            <span v-else>2</span>
          </div>
          <span class="step-label" :class="{ active: step === 2, done: step > 2 }">Publicación</span>
        </div>
        <div class="step-line" :class="{ done: step > 2 }" />
        <div class="step-item">
          <div class="step-circle" :class="{ active: step === 3 }">3</div>
          <span class="step-label" :class="{ active: step === 3 }">Vista previa</span>
        </div>
      </div>

      <div v-if="flashSuccess" class="success-box">
        <i class="ti ti-circle-check" /> {{ flashSuccess }}
      </div>

      <div v-if="Object.keys(form.errors).length" class="error-box">
        <ul>
          <li v-for="(msg, campo) in form.errors" :key="campo">{{ msg }}</li>
        </ul>
      </div>

      <form @submit.prevent="submit" enctype="multipart/form-data">

        <!-- PASO 1 -->
        <transition name="fade-up">
          <div v-if="step === 1" class="panel">
            <div class="panel-header">
              <h3>Datos de la mascota</h3>
              <p v-if="form.estado === 'encontrado'">Contanos lo que sepas sobre la mascota que encontraste</p>
              <p v-else>Contanos sobre tu mascota para que puedan reconocerla</p>
            </div>

            <div class="field-row">
              <div class="field">
                <label for="nombre">Nombre {{ (form.estado === 'encontrado' || ubicacionDelMapa) ? '(opcional)' : '' }}</label>
                <input
                  id="nombre"
                  name="nombre"
                  type="text"
                  v-model="form.nombre"
                  :class="{ 'input-error': form.errors.nombre }"
                  :placeholder="form.estado === 'encontrado' ? 'Si le pusiste un nombre provisorio' : 'Ej: Luna, Toby...'"
                />
                <span v-if="form.errors.nombre" class="field-error">
                  <i class="ti ti-alert-circle" /> {{ form.errors.nombre }}
                </span>
              </div>

              <div class="field">
                <label for="especie">Especie</label>
                <select
                  id="especie"
                  name="especie"
                  v-model="form.especie"
                  :class="{ 'input-error': form.errors.especie }"
                >
                  <option value="">Seleccioná</option>
                  <option value="perro">Perro</option>
                  <option value="gato">Gato</option>
                  <option value="otro">Otro</option>
                </select>
                <span v-if="form.errors.especie" class="field-error">
                  <i class="ti ti-alert-circle" /> {{ form.errors.especie }}
                </span>
              </div>
            </div>

            <div class="field-row">
              <div class="field">
                <label>Raza</label>
                <SelectorRazas
                  v-model="form.raza"
                  :especie="form.especie"
                  :disabled="!form.especie"
                  :placeholder="!form.especie ? 'Seleccioná una especie primero' : 'Buscar raza...'"
                />
              </div>

              <div class="field" style="position: relative">
                <label>Color</label>
                <input
                  type="text"
                  v-model="busquedaColor"
                  placeholder="Buscar color..."
                  @focus="mostrarDropdownColor = true"
                  @blur="ocultarDropdownColor"
                />
                <div v-if="mostrarDropdownColor && coloresFiltrados.length" class="raza-dropdown">
                  <div
                    v-for="color in coloresFiltrados"
                    :key="color"
                    class="raza-option"
                    @mousedown="seleccionarColor(color)"
                  >
                    {{ color }}
                  </div>
                </div>
              </div>
            </div>

            <div class="field">
              <label>Tamaño</label>
              <div class="pills">
                <button
                  type="button"
                  v-for="s in [['pequeño','Pequeño'], ['mediano','Mediano'], ['grande','Grande']]"
                  :key="s[0]"
                  class="pill"
                  :class="{ selected: form.tamano === s[0] }"
                  @click="form.tamano = s[0]"
                >{{ s[1] }}</button>
              </div>
            </div>

            <div class="field">
              <label>Sexo</label>
              <div class="pills">
                <button
                  type="button"
                  v-for="sx in [['macho','Macho'], ['hembra','Hembra'], ['desconocido','No sé']]"
                  :key="sx[0]"
                  class="pill"
                  :class="{ selected: form.sexo === sx[0] }"
                  @click="form.sexo = sx[0]"
                >{{ sx[1] }}</button>
              </div>
            </div>

            <div class="btn-row">
              <button type="button" class="btn-primary" @click="goStep2">
                Continuar <i class="ti ti-arrow-right" />
              </button>
            </div>
          </div>
        </transition>

        <!-- PASO 2 -->
        <transition name="fade-up">
          <div v-if="step === 2" class="panel">
            <div class="panel-header">
              <h3>Datos de la publicación</h3>
              <p v-if="form.estado === 'encontrado'">Contanos dónde y cuándo encontraste a esta mascota</p>
              <p v-else>Agregá los detalles del aviso que verá la gente</p>
            </div>

            <div class="field">
              <label>Título</label>
              <input
                type="text"
                v-model="form.titulo"
                :placeholder="form.estado === 'encontrado' ? 'Ej: Encontré un perrito en Villa Urquiza' : 'Ej: Se perdió Luna en Villa Urquiza'"
                :class="{ 'input-error': form.errors.titulo }"
              />
              <span v-if="form.errors.titulo" class="field-error">
                <i class="ti ti-alert-circle" /> {{ form.errors.titulo }}
              </span>
            </div>

            <div class="field">
              <label>Descripción</label>
              <textarea
                v-model="form.descripcion"
                rows="3"
                :placeholder="form.estado === 'encontrado' ? 'Describí a la mascota, señas particulares, cómo la encontraste...' : 'Describí dónde y cuándo se perdió, señas particulares...'"
                :class="{ 'input-error': form.errors.descripcion }"
              />
              <span v-if="form.errors.descripcion" class="field-error">
                <i class="ti ti-alert-circle" /> {{ form.errors.descripcion }}
              </span>
            </div>

            <div class="field-row">
              <div class="field">
                <label>{{ form.estado === 'encontrado' ? 'Fecha en que se perdió (si la sabés)' : 'Fecha del evento' }}</label>
                <input
                  type="date"
                  v-model="form.fecha_evento"
                  :class="{ 'input-error': form.errors.fecha_evento }"
                />
                <span v-if="form.errors.fecha_evento" class="field-error">
                  <i class="ti ti-alert-circle" /> {{ form.errors.fecha_evento }}
                </span>
              </div>

              <div class="field">
                <label>Ubicación</label>
                <input
                  type="text"
                  v-model="form.zona"
                  placeholder="Ej: Villa Urquiza, CABA"
                  :class="{ 'input-error': form.errors.zona }"
                />
                <span v-if="form.errors.zona" class="field-error">
                  <i class="ti ti-alert-circle" /> {{ form.errors.zona }}
                </span>
                <span v-if="cargandoDireccion" class="ubicacion-mapa-ok">
                  <i class="ti ti-loader-2 spin" /> Buscando la dirección...
                </span>
                <span v-else-if="form.lat && form.lng" class="ubicacion-mapa-ok">
                  <i class="ti ti-map-pin" /> Ubicación exacta tomada del mapa
                </span>
              </div>
            </div>

            <!-- Campos específicos para "Encontré una mascota" -->
            <template v-if="form.estado === 'encontrado'">
              <div class="field-row">
                <div class="field">
                  <label>Fecha en que la encontraste</label>
                  <input
                    type="date"
                    v-model="form.fecha_encontrada"
                    :class="{ 'input-error': form.errors.fecha_encontrada }"
                  />
                  <span v-if="form.errors.fecha_encontrada" class="field-error">
                    <i class="ti ti-alert-circle" /> {{ form.errors.fecha_encontrada }}
                  </span>
                </div>

                <div class="field">
                  <label>Dónde la encontraste</label>
                  <input
                    type="text"
                    v-model="form.ubicacion_encontrada"
                    placeholder="Ej: Plaza Irlanda"
                    :class="{ 'input-error': form.errors.ubicacion_encontrada }"
                  />
                  <span v-if="form.errors.ubicacion_encontrada" class="field-error">
                    <i class="ti ti-alert-circle" /> {{ form.errors.ubicacion_encontrada }}
                  </span>
                </div>
              </div>

              <div class="field">
                <label>Tu contacto</label>
                <input
                  type="text"
                  v-model="form.contacto"
                  placeholder="Teléfono, WhatsApp o email para que te contacten"
                  :class="{ 'input-error': form.errors.contacto }"
                />
                <span v-if="form.errors.contacto" class="field-error">
                  <i class="ti ti-alert-circle" /> {{ form.errors.contacto }}
                </span>
              </div>
            </template>

            <div class="field">
              <label>Foto de portada</label>
              <div
                class="photo-drop"
                :class="{ 'photo-drop-error': form.errors.imagen }"
                @click="$refs.fotoInput.click()"
              >
                <i class="ti ti-camera" />
                <p v-if="verificandoImagen" class="foto-verificando">
                  <i class="ti ti-loader-2 spin" /> Verificando que sea una mascota...
                </p>
                <p v-else-if="!fotoNombre">Hacé clic para subir la foto principal</p>
                <p v-else class="foto-ok"><i class="ti ti-check" /> {{ fotoNombre }}</p>
                <input
                  ref="fotoInput"
                  type="file"
                  accept="image/*"
                  style="display:none"
                  @change="onFoto"
                />
              </div>
              <span v-if="form.errors.imagen" class="field-error">
                <i class="ti ti-alert-circle" /> {{ form.errors.imagen }}
              </span>
            </div>

            <div class="field">
              <label>Más fotos (opcional)</label>
              <div
                class="photo-drop"
                :class="{ 'photo-drop-error': form.errors.imagenes }"
                @click="$refs.fotosExtraInput.click()"
              >
                <i class="ti ti-photo" />
                <p v-if="!fotosExtraNombres.length">Agregá fotos extra de tu mascota</p>
                <p v-else class="foto-ok"><i class="ti ti-check" /> {{ fotosExtraNombres.length }} foto{{ fotosExtraNombres.length !== 1 ? 's' : '' }} seleccionada{{ fotosExtraNombres.length !== 1 ? 's' : '' }}</p>
                <input
                  ref="fotosExtraInput"
                  type="file"
                  accept="image/*"
                  multiple
                  style="display:none"
                  @change="onFotosExtra"
                />
              </div>
              <span v-if="form.errors.imagenes" class="field-error">
                <i class="ti ti-alert-circle" /> {{ form.errors.imagenes }}
              </span>
            </div>

            <div class="btn-row">
              <button type="button" class="btn-secondary" @click="step = 1">
                <i class="ti ti-arrow-left" /> Volver
              </button>

              <button type="button" class="btn-primary" @click="goStep3">
                Ver vista previa <i class="ti ti-arrow-right" />
              </button>
            </div>

            <p class="cancelar-link"><a :href="form.estado === 'encontrado' ? '/mascotas-encontradas' : '/mascotas-perdidas'">Cancelar</a></p>
          </div>
        </transition>

        <!-- PASO 3: VISTA PREVIA -->
        <transition name="fade-up">
          <div v-if="step === 3" class="panel">
            <div class="panel-header">
              <h3>Revisá antes de publicar</h3>
              <p>Así se va a ver tu aviso. Fijate bien que se vea la mascota con claridad</p>
            </div>

            <p class="preview-subtitulo">Así aparece en el listado</p>
            <div class="preview-card">
              <div class="preview-card-img-wrap">
                <img
                  v-if="fotoPreviewUrl"
                  :src="fotoPreviewUrl"
                  alt="Vista previa"
                  class="preview-card-img"
                >
                <div v-else class="preview-img-placeholder">🐾</div>
                <span class="preview-badge" :class="{ 'preview-badge-encontrado': form.estado === 'encontrado' }">{{ previewBadgeLabel }}</span>
              </div>
              <div class="preview-card-body">
                <h4>{{ form.titulo || 'Título de tu publicación' }}</h4>
                <p>{{ form.descripcion || 'Descripción...' }}</p>
                <span class="preview-fecha">
                  <i class="ti ti-calendar" /> {{ fechaFormateada || 'Fecha del evento' }}
                </span>
              </div>
            </div>

            <p class="preview-subtitulo">Así se ve al abrir "Ver detalles"</p>
            <div class="preview-detalle">
              <div class="preview-detalle-img-wrap">
                <img
                  v-if="imagenPreviewActual"
                  :src="imagenPreviewActual"
                  alt="Vista previa"
                  class="preview-detalle-img"
                >
                <div v-else class="preview-img-placeholder">🐾</div>
                <span class="preview-badge" :class="{ 'preview-badge-encontrado': form.estado === 'encontrado' }">{{ previewBadgeLabel }}</span>

                <template v-if="todasLasFotosPreview.length > 1">
                  <button type="button" class="preview-arrow preview-arrow-left" @click="fotoPreviewIndex = (fotoPreviewIndex - 1 + todasLasFotosPreview.length) % todasLasFotosPreview.length">
                    <i class="ti ti-chevron-left" />
                  </button>
                  <button type="button" class="preview-arrow preview-arrow-right" @click="fotoPreviewIndex = (fotoPreviewIndex + 1) % todasLasFotosPreview.length">
                    <i class="ti ti-chevron-right" />
                  </button>
                  <span class="preview-contador">{{ fotoPreviewIndex + 1 }} / {{ todasLasFotosPreview.length }}</span>
                </template>
              </div>

              <div class="preview-detalle-body">
                <h4>{{ form.titulo || 'Título de tu publicación' }}</h4>
                <p>{{ form.descripcion || 'Descripción...' }}</p>

                <div class="preview-datos-grid">
                  <div class="preview-dato" v-if="form.nombre"><span>Nombre</span><b>{{ form.nombre }}</b></div>
                  <div class="preview-dato" v-if="form.especie"><span>Especie</span><b>{{ form.especie }}</b></div>
                  <div class="preview-dato" v-if="form.raza"><span>Raza</span><b>{{ form.raza }}</b></div>
                  <div class="preview-dato" v-if="form.color"><span>Color</span><b>{{ form.color }}</b></div>
                  <div class="preview-dato" v-if="form.tamano"><span>Tamaño</span><b>{{ form.tamano }}</b></div>
                  <div class="preview-dato" v-if="form.sexo"><span>Sexo</span><b>{{ form.sexo }}</b></div>
                  <div class="preview-dato" v-if="form.estado === 'encontrado' && form.ubicacion_encontrada"><span>Dónde la encontró</span><b>{{ form.ubicacion_encontrada }}</b></div>
                  <div class="preview-dato" v-if="form.estado === 'encontrado' && form.contacto"><span>Contacto</span><b>{{ form.contacto }}</b></div>
                </div>
              </div>
            </div>

            <div v-if="!fotoPreviewUrl" class="preview-warning">
              <i class="ti ti-alert-triangle" /> No subiste ninguna foto. Las publicaciones con foto tienen muchas más chances de que reconozcan a la mascota.
            </div>

            <div class="btn-row">
              <button type="button" class="btn-secondary" @click="step = 2">
                <i class="ti ti-arrow-left" /> Volver a editar
              </button>

              <button type="button" class="btn-primary" :disabled="form.processing" @click="submit">
                <i v-if="form.processing" class="ti ti-loader-2 spin" />
                {{ form.processing ? 'Publicando...' : textoSubmit }}
                <i v-if="!form.processing" class="ti ti-send" />
              </button>
            </div>

            <p class="cancelar-link"><a :href="form.estado === 'encontrado' ? '/mascotas-encontradas' : '/mascotas-perdidas'">Cancelar</a></p>
          </div>
        </transition>

      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { coloresMascota } from '@/Razas.js'
import SelectorRazas from '@/Componentes/SelectorRazas.vue'
import imageCompression from 'browser-image-compression'
import { esImagenDeMascota } from '@/composables/useAnimalClassifier'

const page = usePage()
const flashSuccess = computed(() => page.props.flash?.success)

// Detecta el tipo desde la URL: /publicaciones/crear?tipo=encontrado o ?tipo=perdido
const paramsUrl = typeof window !== 'undefined'
  ? new URLSearchParams(window.location.search)
  : new URLSearchParams()

const tipoQuery = paramsUrl.get('tipo')

// Si venimos de tocar un punto en /mapa, llegan lat/lng ya cargados
const latQuery = paramsUrl.get('lat')
const lngQuery = paramsUrl.get('lng')
const ubicacionDelMapa = latQuery && lngQuery

// Único lugar de verdad: 'perdido' o 'encontrado' (siempre masculino, así matchea con el backend)
const estadoInicial = tipoQuery === 'encontrado' ? 'encontrado' : 'perdido'

const tituloForm        = computed(() => form.estado === 'encontrado' ? 'Publicar mascota encontrada' : 'Publicar mascota perdida')
const textoSubmit       = computed(() => form.estado === 'encontrado' ? 'Publicar encontrado' : 'Publicar perdido')
const previewBadgeLabel = computed(() => form.estado === 'encontrado' ? 'Encontrado' : 'Perdido')

const step = ref(1)
const fotoNombre = ref('')
const fotosExtraNombres = ref([])
const fotoPreviewUrl = ref('')
const fotosExtraPreviewUrls = ref([])
const fotoPreviewIndex = ref(0)
const busquedaColor = ref('')
const mostrarDropdownColor = ref(false)
const verificandoImagen = ref(false) // true mientras el modelo de IA analiza la foto

const form = useForm({
  estado:       estadoInicial, // 'perdido' o 'encontrado'
  nombre:       '',
  especie:      '',
  raza:         '',
  color:        '',
  tamano:       '',
  sexo:         '',
  titulo:       '',
  descripcion:  '',
  fecha_evento: '',
  zona:         '',
  lat:          ubicacionDelMapa ? parseFloat(latQuery) : null,
  lng:          ubicacionDelMapa ? parseFloat(lngQuery) : null,
  imagen:       null,
  imagenes:     [],
  fecha_encontrada:     '',
  ubicacion_encontrada: '',
  contacto:             '',
})

const cargandoDireccion = ref(false)

// Si venimos del mapa, convertimos las coordenadas en una dirección legible
// (geocodificación inversa) y llenamos el campo Zona solos, para no hacerte
// escribirla de nuevo si ya tocaste el lugar exacto.
onMounted(async () => {
  if (!ubicacionDelMapa) return

  cargandoDireccion.value = true
  try {
    const resp = await fetch(
      `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latQuery}&lon=${lngQuery}&zoom=18&addressdetails=1`
    )
    const data = await resp.json()

    if (data?.display_name) {
      // Recortamos: nos interesa calle + altura + barrio, no la dirección completa con país/CP
      const partes = data.display_name.split(',').map(p => p.trim())
      form.zona = partes.slice(0, 3).join(', ')
    }
  } catch (e) {
    // Si falla (sin internet, servicio caído, etc.) no pasa nada — el campo
    // queda vacío y el usuario lo puede escribir a mano.
  } finally {
    cargandoDireccion.value = false
  }
})

const ocultarDropdownColor = () => { setTimeout(() => { mostrarDropdownColor.value = false }, 150) }

const coloresFiltrados = computed(() =>
  coloresMascota.filter(c => c.toLowerCase().includes(busquedaColor.value.toLowerCase()))
)

const seleccionarColor = (color) => {
  form.color = color
  busquedaColor.value = color
  mostrarDropdownColor.value = false
}

watch(() => form.especie, () => {
  form.raza = ''
})

const goStep2 = () => {
  form.clearErrors('nombre', 'especie')

  let hayErrores = false

  // Si viene del mapa (tocaste un punto donde viste/perdiste una mascota), es normal
  // no saber el nombre, así que ahí no lo exigimos aunque sea "perdido".
  if (form.estado === 'perdido' && !ubicacionDelMapa && (!form.nombre || !String(form.nombre).trim())) {
    form.setError('nombre', 'El nombre de la mascota es requerido.')
    hayErrores = true
  }

  if (!form.especie) {
    form.setError('especie', 'La especie es requerida.')
    hayErrores = true
  }

  if (!hayErrores) {
    step.value = 2
  }
}

const onFoto = async (e) => {
  const file = e.target.files[0]
  if (!file) return

  // Validar formato (como vimos antes)
  const tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp']
  if (!tiposPermitidos.includes(file.type)) {
    form.setError('imagen', 'Formato no válido. Solo JPG, PNG o WEBP.')
    e.target.value = ''
    return
  }

  try {
    // Configuramos la compresión
    const options = {
      maxSizeMB: 2, // Forzamos a que pese menos de 2MB
      maxWidthOrHeight: 1920, // 1080p es excelente para ver una mascota
      useWebWorker: true // Usa múltiples hilos para no trabar la pantalla
    }

    // Comprimimos la imagen localmente
    const compressedFile = await imageCompression(file, options)

    // Ahora evaluamos el archivo COMPRIMIDO contra tu límite de seguridad
    if (compressedFile.size > 10 * 1024 * 1024) {
      form.setError('imagen', 'La imagen sigue siendo muy pesada tras comprimir.')
      e.target.value = ''
      return
    }

    // --- Validación con el modelo de IA: ¿es una mascota? ---
    verificandoImagen.value = true
    let resultado
    try {
      resultado = await esImagenDeMascota(compressedFile)
    } finally {
      verificandoImagen.value = false
    }

    if (!resultado.esMascota) {
      form.setError('imagen', 'Contenido incorrecto: no se reconoció esta imagen como una mascota. Volvé a intentarlo.')
      e.target.value = ''
      return
    }
    // --- Fin validación con IA ---

    form.clearErrors('imagen')
    // Guardamos el archivo ya optimizado en el formulario
    form.imagen = compressedFile
    fotoNombre.value = compressedFile.name

    if (fotoPreviewUrl.value) URL.revokeObjectURL(fotoPreviewUrl.value)
    fotoPreviewUrl.value = URL.createObjectURL(compressedFile)

  } catch (error) {
    verificandoImagen.value = false
    form.setError('imagen', 'Hubo un error al procesar la foto de tu celular.')
    e.target.value = ''
  }
}

const onFotosExtra = async (e) => {
  const files = Array.from(e.target.files)
  if (!files.length) return

  // 1. Validar el formato de TODOS los archivos seleccionados
  const tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp']
  const archivoInvalido = files.find(f => !tiposPermitidos.includes(f.type))

  if (archivoInvalido) {
    form.setError('imagenes', `El archivo "${archivoInvalido.name}" no es un formato válido.`)
    e.target.value = ''
    return
  }

  try {
    const options = {
      maxSizeMB: 2, // Límite de 2MB por foto extra
      maxWidthOrHeight: 1920,
      useWebWorker: true // Clave para no congelar la pantalla del celular
    }

    // 2. Comprimir todos los archivos en paralelo
    const promesasCompresion = files.map(file => imageCompression(file, options))
    const archivosComprimidos = await Promise.all(promesasCompresion)

    // 3. Validar el tamaño post-compresión contra tu límite de seguridad estricto
    const muyPesado = archivosComprimidos.find(f => f.size > 10 * 1024 * 1024)
    if (muyPesado) {
      form.setError('imagenes', `La imagen "${muyPesado.name}" sigue superando los 10MB tras intentar comprimirla.`)
      e.target.value = ''
      return
    }

    // 4. Actualizar el formulario y las vistas previas
    form.clearErrors('imagenes')
    form.imagenes = archivosComprimidos
    fotosExtraNombres.value = archivosComprimidos.map(f => f.name)

    // Limpiar URLs previas de la memoria para evitar fugas (como ya hacías muy bien en tu código original)
    fotosExtraPreviewUrls.value.forEach(url => URL.revokeObjectURL(url))
    fotosExtraPreviewUrls.value = archivosComprimidos.map(f => URL.createObjectURL(f))

  } catch (error) {
    form.setError('imagenes', 'Hubo un error al procesar las fotos extra. Intenta con menos cantidad.')
    e.target.value = ''
  }
}

const todasLasFotosPreview = computed(() => {
  const imgs = []
  if (fotoPreviewUrl.value) imgs.push(fotoPreviewUrl.value)
  imgs.push(...fotosExtraPreviewUrls.value)
  return imgs
})

const imagenPreviewActual = computed(() => todasLasFotosPreview.value[fotoPreviewIndex.value] ?? null)

const fechaFormateada = computed(() => {
  if (!form.fecha_evento) return ''
  return new Date(form.fecha_evento + 'T00:00:00').toLocaleDateString('es-AR', {
    day: '2-digit', month: 'long', year: 'numeric',
  })
})

const goStep3 = () => {
  form.clearErrors('titulo', 'fecha_encontrada', 'ubicacion_encontrada', 'contacto')

  if (!form.titulo || !String(form.titulo).trim()) {
    form.setError('titulo', 'El título es requerido.')
    return
  }

  if (form.estado === 'encontrado') {
    let hayErroresEncontrado = false
    if (!form.fecha_encontrada) {
      form.setError('fecha_encontrada', 'La fecha en que la encontraste es requerida.')
      hayErroresEncontrado = true
    }
    if (!form.ubicacion_encontrada || !String(form.ubicacion_encontrada).trim()) {
      form.setError('ubicacion_encontrada', 'La ubicación donde la encontraste es requerida.')
      hayErroresEncontrado = true
    }
    if (!form.contacto || !String(form.contacto).trim()) {
      form.setError('contacto', 'Tu contacto es requerido.')
      hayErroresEncontrado = true
    }
    if (hayErroresEncontrado) return
  }

  fotoPreviewIndex.value = 0
  step.value = 3
}

const submit = () => {
  form.post('/publicaciones', {
    forceFormData: true,
    onError: (errors) => {
      const camposPaso1 = ['nombre', 'especie', 'raza', 'color', 'tamano', 'sexo']
      const camposPaso2 = ['titulo', 'descripcion', 'fecha_evento', 'zona', 'imagen', 'imagenes', 'estado', 'fecha_encontrada', 'ubicacion_encontrada', 'contacto']

      if (camposPaso1.some(campo => errors[campo])) {
        step.value = 1
      } else if (camposPaso2.some(campo => errors[campo])) {
        step.value = 2
      }
    },
  })
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&display=swap');

.page-bg {
  min-height: 100vh;
  background: linear-gradient(135deg, #fdf6ee 0%, #f5ede4 100%);
  position: relative;
  overflow: hidden;
  padding: 50px 20px 80px;
  font-family: 'Space Grotesk', sans-serif;
}

.blob { position: absolute; border-radius: 50%; filter: blur(60px); opacity: .35; z-index: 0; }
.blob-1 { width: 340px; height: 340px; background: #ffb98a; top: -80px; left: -80px; }
.blob-2 { width: 260px; height: 260px; background: #f6d3b8; bottom: -60px; right: -40px; }
.blob-3 { width: 200px; height: 200px; background: #ffe0c2; top: 40%; right: 10%; }

.glass-card {
  position: relative;
  z-index: 1;
  max-width: 640px;
  margin: 0 auto;
  background: rgba(255,255,255,0.85);
  backdrop-filter: blur(10px);
  border-radius: 24px;
  padding: 32px;
  box-shadow: 0 12px 40px rgba(120, 80, 50, .12);
}

.card-title { font-size: 24px; font-weight: 700; color: #3a2519; text-align: center; margin-bottom: 20px; }

.steps-nav { display: flex; align-items: center; justify-content: center; margin-bottom: 24px; gap: 4px; }
.step-item { display: flex; flex-direction: column; align-items: center; gap: 4px; }
.step-circle {
  width: 30px; height: 30px; border-radius: 50%;
  border: 2px solid #ddd0c4; background: #fff;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; font-weight: 700; color: #a07860;
}
.step-circle.active { border-color: #D85A30; background: #D85A30; color: #fff; }
.step-circle.done    { border-color: #D85A30; background: #fff; color: #D85A30; }
.step-label { font-size: 11px; color: #a07860; font-weight: 600; }
.step-label.active, .step-label.done { color: #D85A30; }
.step-line { width: 40px; height: 2px; background: #ddd0c4; margin: 0 4px; margin-bottom: 18px; }
.step-line.done { background: #D85A30; }

.panel-header h3 { font-size: 18px; font-weight: 700; color: #3a2519; margin-bottom: 4px; }
.panel-header p  { font-size: 13px; color: #a07860; }

.field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 14px; }
.field { margin-top: 14px; }
.field label { display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #a07860; margin-bottom: 6px; }
.field input, .field select, .field textarea {
  width: 100%; padding: 11px 14px; border-radius: 10px; border: 1.5px solid #ddd0c4;
  font-size: 14px; font-family: 'Space Grotesk', sans-serif; outline: none; color: #3a2519;
  background: #fff;
}
.field input:focus, .field select:focus, .field textarea:focus { border-color: #D85A30; }
.field input:disabled { background: #f5ede4; cursor: not-allowed; }

.pills { display: flex; gap: 8px; flex-wrap: wrap; }
.pill {
  padding: 8px 16px; border-radius: 999px; border: 1.5px solid #ddd0c4;
  background: #fff; font-size: 13px; font-weight: 600; color: #7a5642;
  cursor: pointer; font-family: 'Space Grotesk', sans-serif; transition: .15s;
}
.pill:hover { border-color: #D85A30; }
.pill.selected { background: #D85A30; border-color: #D85A30; color: #fff; }

.photo-drop {
  border: 2px dashed #ddd0c4; border-radius: 14px; padding: 20px; text-align: center;
  cursor: pointer; color: #a07860; transition: .15s;
}
.photo-drop:hover { border-color: #D85A30; color: #D85A30; }
.photo-drop i { font-size: 24px; display: block; margin-bottom: 6px; }
.photo-drop p { font-size: 13px; margin: 0; }
.photo-drop-error { border-color: #D85A30; }
.foto-ok { color: #c47a50 !important; font-weight: 500; }
.foto-verificando { color: #D85A30 !important; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 6px; }

.btn-row { display: flex; gap: 12px; justify-content: flex-end; margin-top: 1.5rem; }

.btn-primary {
  display: inline-flex; align-items: center; gap: 6px; padding: 10px 22px;
  border-radius: 10px; background: #D85A30; color: #fff; border: none;
  font-size: 14px; font-weight: 500; cursor: pointer; transition: background 0.15s;
}
.btn-primary:hover    { background: #c04e26; }
.btn-primary:disabled { background: #e0a080; cursor: not-allowed; }

.btn-secondary {
  display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px;
  border-radius: 10px; background: rgba(255, 255, 255, 0.8); color: #7a5642;
  border: 1.5px solid #ddd0c4; font-size: 14px; cursor: pointer; transition: all 0.15s;
}
.btn-secondary:hover { border-color: #D85A30; color: #D85A30; }

.cancelar-link { text-align: center; margin-top: 12px; font-size: 13px; }
.cancelar-link a       { color: #a07860; text-decoration: none; }
.cancelar-link a:hover { color: #D85A30; }

.success-box {
  display: flex; align-items: center; gap: 8px; background: #f0faf4;
  border: 1px solid #a3d9b8; border-radius: 10px; padding: 10px 14px;
  margin-bottom: 1rem; font-size: 13px; color: #2d6a4a;
}

.error-box {
  background: #fff3f0; border: 1px solid #f5c4b3; border-radius: 10px;
  padding: 10px 14px; margin-bottom: 1rem; font-size: 13px; color: #993c1d;
}
.error-box ul { margin: 0; padding-left: 16px; }

.input-error { border-color: #D85A30 !important; background: #fff8f6 !important; }
.field-error { display: flex; align-items: center; gap: 4px; font-size: 12px; color: #993c1d; margin-top: 4px; }
.ubicacion-mapa-ok { display: flex; align-items: center; gap: 4px; font-size: 12px; color: #2563eb; margin-top: 4px; font-weight: 600; }

.raza-dropdown {
  position: absolute; z-index: 10; width: 100%; background: #fff;
  border: 1.5px solid #ddd0c4; border-radius: 10px; max-height: 200px;
  overflow-y: auto; margin-top: 4px; box-shadow: 0 4px 16px rgba(180, 100, 60, 0.1);
}
.raza-option { padding: 8px 12px; font-size: 14px; color: #3a2519; cursor: pointer; }
.raza-option:hover { background: #f5ede4; color: #D85A30; }

.fade-up-enter-active { animation: fadeUp 0.3s ease; }
.fade-up-leave-active { animation: fadeUp 0.2s ease reverse; }
@keyframes fadeUp { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

.btn-primary .ti-arrow-right { display: inline-block; animation: arrowPulse 1.2s ease-in-out infinite; }
@keyframes arrowPulse { 0%, 100% { transform: translateX(0); } 50% { transform: translateX(5px); } }

@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.spin { animation: spin 0.8s linear infinite; }

.preview-subtitulo {
  font-size: 12px; font-weight: 600; text-transform: uppercase;
  letter-spacing: 0.04em; color: #a07860; margin: 1.25rem 0 8px;
}

.preview-card, .preview-detalle {
  border: 1.5px solid #ddd0c4; border-radius: 14px; overflow: hidden; background: #fff;
}

.preview-card-img-wrap, .preview-detalle-img-wrap {
  position: relative; width: 100%; aspect-ratio: 4 / 3; background: #f5ede4;
}
.preview-card-img, .preview-detalle-img { width: 100%; height: 100%; object-fit: cover; display: block; }
.preview-img-placeholder {
  width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
  font-size: 48px; background: linear-gradient(135deg, #f5ede4 0%, #e4d0be 100%);
}

.preview-badge {
  position: absolute; top: 10px; left: 10px; background: #ff3b3b; color: #fff;
  font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 999px; z-index: 3;
}
.preview-badge-encontrado { background: #2563eb; }

.preview-card-body, .preview-detalle-body { padding: 14px 16px; }
.preview-card-body h4, .preview-detalle-body h4 { font-size: 15px; font-weight: 600; color: #4a2e1f; margin: 0 0 4px; }
.preview-card-body p, .preview-detalle-body p {
  font-size: 13px; color: #8a6050; margin: 0 0 8px;
  overflow: hidden; text-overflow: ellipsis; display: -webkit-box;
  -webkit-line-clamp: 2; -webkit-box-orient: vertical;
}
.preview-fecha { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #a07860; font-weight: 500; }

.preview-datos-grid {
  display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px;
  margin-top: 10px; padding: 12px; background: #f5ede4; border-radius: 10px;
}
.preview-dato { display: flex; flex-direction: column; gap: 1px; }
.preview-dato span { font-size: 10px; text-transform: uppercase; color: #a07860; font-weight: 600; }
.preview-dato b { font-size: 13px; color: #4a2e1f; font-weight: 600; text-transform: capitalize; }

.preview-arrow {
  position: absolute; top: 50%; transform: translateY(-50%); width: 30px; height: 30px;
  border-radius: 50%; border: 1.5px solid #ddd0c4; background: #fff; color: #4a2e1f;
  display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 3;
}
.preview-arrow:hover { background: #D85A30; border-color: #D85A30; color: #fff; }
.preview-arrow-left  { left: 8px; }
.preview-arrow-right { right: 8px; }

.preview-contador {
  position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.6); color: #fff;
  font-size: 11px; font-weight: 600; padding: 3px 9px; border-radius: 999px; z-index: 3;
}

.preview-warning {
  display: flex; align-items: center; gap: 8px; background: #fff3f0;
  border: 1px solid #f5c4b3; border-radius: 10px; padding: 10px 14px;
  margin-top: 1rem; font-size: 13px; color: #993c1d;
}
</style>