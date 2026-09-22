<template>
    <main class="min-h-screen bg-[#fff085] px-5 pt-10 pb-20 font-['Space_Grotesk',sans-serif]">
        <div class="max-w-[700px] lg:max-w-[1040px] mx-auto">
            
            <!-- Botón Volver -->
            <Link 
    :href="publicacion.estado === 'encontrado' ? '/mascotas-encontradas' : '/mascotas-perdidas'" 
    class="group inline-flex items-center gap-2 text-base font-bold !no-underline mb-5"
    aria-label="Volver al listado de mascotas"
>
    <i class="bi bi-arrow-left text-[#ff7b00] text-lg transition-transform duration-200 group-hover:-translate-x-1.5" aria-hidden="true"></i>
    <span class="bg-gradient-to-r from-[#ff7b00] via-orange-600 to-[#ff7b00] bg-clip-text !text-transparent">
        Volver al listado
    </span>
</Link>

            <!-- Tarjeta Principal (Imagen a la izquierda, texto a la derecha) -->
            <article class="flex flex-col lg:flex-row bg-gradient-to-r from-[#ff7b00] to-[#ff7b00] border-[3px] border-[#111] rounded-[24px] overflow-hidden shadow-[8px_8px_0_#111]">
                
<!-- SECCIÓN IZQUIERDA: IMAGEN CON FONDO BLUR -->
<figure class="relative w-full lg:w-[45%] shrink-0 min-h-[350px] lg:min-h-[520px] bg-[#111] flex items-center justify-center overflow-hidden border-b-[3px] lg:border-b-0 lg:border-r-[3px] border-[#111] m-0">
    <template v-if="imagenActual">
        <!-- Imagen de fondo borrosa -->
        <img 
            :src="imagenActual" 
            class="absolute inset-0 w-full h-full object-cover blur-xl opacity-40 scale-110 pointer-events-none" 
            aria-hidden="true" 
        />
        <!-- Foto principal nítida centrada -->
        <img
            :src="imagenActual"
            :alt="`Fotografía de ${publicacion.titulo}`"
            class="absolute inset-0 w-full h-full object-cover object-center z-0"
        />
    </template>
                    <div v-else class="w-full h-full flex items-center justify-center text-[80px]" aria-hidden="true">
                        🐾
                    </div>

                    <!-- Badge Dinámico -->
                    <span 
                        class="absolute top-4 left-4 text-[13px] font-bold px-4 py-1.5 rounded-full border-2 border-[#111] capitalize z-10 text-white shadow-[2px_2px_0_#111]"
                        :class="{
                            'bg-[#ff3b3b]': publicacion.estado === 'perdido',
                            'bg-[#2563eb]': publicacion.estado === 'encontrado',
                            'bg-[#22c55e]': publicacion.estado === 'resuelto'
                        }"
                    >
                        {{ publicacion.estado }}
                    </span>

                    <!-- Controles de Carrusel -->
                    <nav v-if="todasLasImagenes.length > 1" aria-label="Galería de imágenes">
                        <button 
                            type="button"
                            class="absolute top-1/2 -translate-y-1/2 left-14 w-10 h-10 rounded-full border-2 border-[#111] bg-white text-[#111] flex items-center justify-center cursor-pointer text-lg shadow-[3px_3px_0_#111] transition-all duration-150 z-10 hover:bg-[#ff7b00] hover:text-white" 
                            @click="anterior" 
                            aria-label="Ver foto anterior"
                        >
                            <i class="bi bi-chevron-left" aria-hidden="true"></i>
                        </button>
                        
                        <button 
                            type="button"
                            class="absolute top-1/2 -translate-y-1/2 right-14 w-10 h-10 rounded-full border-2 border-[#111] bg-white text-[#111] flex items-center justify-center cursor-pointer text-lg shadow-[3px_3px_0_#111] transition-all duration-150 z-10 hover:bg-[#ff7b00] hover:text-white" 
                            @click="siguiente" 
                            aria-label="Ver foto siguiente"
                        >
                            <i class="bi bi-chevron-right" aria-hidden="true"></i>
                        </button>

                        <div class="absolute bottom-14 left-1/2 -translate-x-1/2 flex gap-1.5 z-10" role="tablist">
                            <button
                                type="button"
                                v-for="(img, i) in todasLasImagenes" :key="i"
                                role="tab"
                                :aria-selected="i === indiceActual"
                                class="h-2 rounded-full border-2 border-[#111] cursor-pointer p-0 transition-all duration-150"
                                :class="i === indiceActual ? 'bg-[#ff7b00] w-[22px]' : 'bg-white w-2'"
                                @click="indiceActual = i"
                                :aria-label="`Ir a foto ${i + 1}`"
                            ></button>
                        </div>

                        <span class="absolute top-4 right-4 bg-[#111111b3] text-white text-xs font-bold px-2.5 py-1 rounded-full z-10" aria-live="polite">
                            {{ indiceActual + 1 }} / {{ todasLasImagenes.length }}
                        </span>
                    </nav>
                </figure>

                <!-- SECCIÓN DERECHA: TEXTO Y DETALLES -->
                <section class="flex-1 flex flex-col justify-between min-w-0 p-5 sm:p-8">
                    
                    <!-- BLOQUE SUPERIOR (Información) -->
                    <div class="flex-1 flex flex-col justify-start">
                        <header>
                            <h1 class="text-[24px] sm:text-[30px] font-bold text-[#111] mb-3">{{ publicacion.titulo }}</h1>
                            <p class="text-[15px] text-[#555] leading-relaxed mb-5">{{ publicacion.descripcion }}</p>
                        </header>

                        <!-- Info Básica -->
                        <ul class="list-none p-0 m-0 space-y-2.5">
                            <li class="flex items-center gap-2 text-sm text-[#888] font-medium">
                                <i class="bi bi-calendar2 text-[#ff7b00] text-[15px]" aria-hidden="true"></i>
                                <time :datetime="publicacion.fecha_evento">{{ formatFecha(publicacion.fecha_evento) }}</time>
                            </li>
                            <li v-if="publicacion.zona" class="flex items-center gap-2 text-sm text-[#888] font-medium">
                                <i class="bi bi-geo-alt text-[#ff7b00] text-[15px]" aria-hidden="true"></i>
                                <span>{{ publicacion.zona }}</span>
                            </li>
                            <li class="flex items-center gap-2 text-sm text-[#888] font-medium">
                                <i class="bi bi-eye text-[#ff7b00] text-[15px]" aria-hidden="true"></i>
                                <span>{{ publicacion.vistas }} {{ publicacion.vistas === 1 ? 'vista' : 'vistas' }}</span>
                            </li>
                        </ul>

                        <!-- Grilla de Características -->
                        <div v-if="publicacion.mascota" class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 my-6 p-5 bg-[#f4f1ea] border-2 border-[#111] rounded-[16px]">
                            <div v-if="publicacion.mascota.nombre" class="flex flex-col gap-0.5">
                                <span class="text-[11px] uppercase tracking-wider text-[#888] font-bold">Nombre</span>
                                <strong class="text-base text-[#111] font-semibold capitalize">{{ publicacion.mascota.nombre }}</strong>
                            </div>
                            <div v-if="publicacion.mascota.especie" class="flex flex-col gap-0.5">
                                <span class="text-[11px] uppercase tracking-wider text-[#888] font-bold">Especie</span>
                                <strong class="text-base text-[#111] font-semibold capitalize">{{ publicacion.mascota.especie }}</strong>
                            </div>
                            <div v-if="publicacion.mascota.raza" class="flex flex-col gap-0.5">
                                <span class="text-[11px] uppercase tracking-wider text-[#888] font-bold">Raza</span>
                                <strong class="text-base text-[#111] font-semibold capitalize">{{ publicacion.mascota.raza }}</strong>
                            </div>
                            <div v-if="publicacion.mascota.color" class="flex flex-col gap-0.5">
                                <span class="text-[11px] uppercase tracking-wider text-[#888] font-bold">Color</span>
                                <strong class="text-base text-[#111] font-semibold capitalize">{{ publicacion.mascota.color }}</strong>
                            </div>
                            <div v-if="publicacion.mascota.tamano" class="flex flex-col gap-0.5">
                                <span class="text-[11px] uppercase tracking-wider text-[#888] font-bold">Tamaño</span>
                                <strong class="text-base text-[#111] font-semibold capitalize">{{ publicacion.mascota.tamano }}</strong>
                            </div>
                            <div v-if="publicacion.mascota.sexo" class="flex flex-col gap-0.5">
                                <span class="text-[11px] uppercase tracking-wider text-[#888] font-bold">Sexo</span>
                                <strong class="text-base text-[#111] font-semibold capitalize">{{ publicacion.mascota.sexo }}</strong>
                            </div>
                            <div v-if="publicacion.estado === 'encontrado' && publicacion.ubicacion_encontrada" class="flex flex-col gap-0.5">
                                <span class="text-[11px] uppercase tracking-wider text-[#888] font-bold">Dónde la encontró</span>
                                <strong class="text-base text-[#111] font-semibold capitalize">{{ publicacion.ubicacion_encontrada }}</strong>
                            </div>
                            <div v-if="publicacion.estado === 'encontrado' && publicacion.contacto" class="flex flex-col gap-0.5">
                                <span class="text-[11px] uppercase tracking-wider text-[#888] font-bold">Contacto</span>
                                <strong class="text-base text-[#111] font-semibold capitalize">{{ publicacion.contacto }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- BLOQUE INFERIOR (Acciones) -->
                    <div class="mt-auto pt-4 border-t border-transparent">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a
                                v-if="linkContacto(publicacion)"
                                :href="linkContacto(publicacion).href"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex-1 flex items-center justify-center p-3.5 bg-[#111] text-white text-center rounded-[14px] font-bold text-[15px] no-underline border-2 border-[#111] transition-all duration-200 hover:bg-[#ff7b00] hover:-translate-x-3 hover:-translate-y-3 hover:shadow-[6px_6px_0_#111]"
                            >
                                <i :class="linkContacto(publicacion).tipo === 'email' ? 'bi bi-envelope-fill' : 'bi bi-whatsapp'" class="mr-2" aria-hidden="true"></i>
                                Contactar
                            </a>
                            
                            <span v-else class="flex-1 flex items-center justify-center p-3.5 bg-[#ddd] text-[#888] text-center rounded-[14px] font-bold text-[15px] border-2 border-[#ddd] cursor-not-allowed">
                                <i class="bi bi-slash-circle mr-2" aria-hidden="true"></i> Sin contacto
                            </span>

<div class="relative flex-1 flex flex-col sm:flex-row gap-3">
    <!-- Botón Compartir -->
    <div class="flex-1 w-full bg-[#111] !rounded-[14px]">
        <button 
            type="button"
            class="w-full h-full flex items-center justify-center gap-1.5 p-3.5 bg-gradient-to-tr from-[#ff7b00] via-orange-700 to-[#ff7b00] text-white !rounded-[14px] font-bold text-[15px] border-2 border-[#111] -translate-x-[3px] -translate-y-[3px] cursor-pointer transition-all duration-200 hover:-translate-x-[5px] hover:-translate-y-[5px] disabled:opacity-60 disabled:cursor-not-allowed"
            @click="compartir" 
            :disabled="compartiendo"
            aria-haspopup="menu"
        >
            <i v-if="compartiendo" class="bi bi-arrow-repeat animate-spin" aria-hidden="true"></i>
            <i v-else class="bi bi-share-fill" aria-hidden="true"></i>
            {{ compartiendo ? 'Preparando...' : 'Compartir' }}
        </button>
    </div>

    <!-- Botón Cartel -->
    <div class="flex-1 w-full bg-[#111] !rounded-[14px]">
        <a 
            :href="`/publicaciones/${publicacion.id}/cartel`" 
            target="_blank" 
            rel="noopener noreferrer"
            class="w-full h-full flex items-center justify-center gap-1.5 p-3.5 bg-gradient-to-tr from-[#ff7b00] via-orange-700 to-[#ff7b00] text-white !no-underline !rounded-[14px] font-bold text-[15px] border-2 border-[#111] -translate-x-[3px] -translate-y-[3px] cursor-pointer transition-all duration-200 hover:-translate-x-[5px] hover:-translate-y-[5px]"
        >
            Cartel
        </a>
    </div>

    <!-- Menú Compartir Dropdown -->
    <div v-if="mostrarMenuCompartir" class="fixed inset-0 bg-black/35 z-[100] flex items-end justify-center" @click.self="mostrarMenuCompartir = false" role="dialog" aria-modal="true">
        <menu class="bg-white border-[3px] border-[#111] rounded-t-[20px] p-4 w-full max-w-[420px] flex flex-col gap-2 m-0">
            <button type="button" class="flex items-center gap-2.5 py-3.5 px-4 rounded-[12px] border-2 border-[#111] bg-white text-[15px] font-semibold cursor-pointer transition-colors duration-150 hover:bg-[#f4f1ea]" @click="compartirWhatsapp">
                <i class="bi bi-whatsapp text-[18px]" aria-hidden="true"></i> WhatsApp
            </button>
            <button type="button" class="flex items-center gap-2.5 py-3.5 px-4 rounded-[12px] border-2 border-[#111] bg-white text-[15px] font-semibold cursor-pointer transition-colors duration-150 hover:bg-[#f4f1ea]" @click="compartirFacebook">
                <i class="bi bi-facebook text-[18px]" aria-hidden="true"></i> Facebook
            </button>
            <button type="button" class="flex items-center justify-center gap-2.5 py-3.5 px-4 rounded-[12px] border-2 border-[#ddd] bg-white text-[15px] font-semibold cursor-pointer transition-colors duration-150 hover:bg-[#f4f1ea] text-[#888]" @click="mostrarMenuCompartir = false">
                Cancelar
            </button>
        </menu>
    </div>
    </div>
</div>

                        <button 
                            type="button"
                            class="w-full mt-3 p-3.5 bg-[#fff8f0] text-[#c05a1a] border-2 border-dashed border-[#ffb98a] rounded-[14px] font-bold text-sm cursor-pointer transition-all duration-150 hover:bg-[#ffe9d6] hover:border-solid" 
                            @click="abrirModalAvistamiento"
                        >
                            <i class="bi bi-geo-alt-fill mr-1" aria-hidden="true"></i> ¿La viste? Reportá dónde
                        </button>
                    </div>
                </section>
            </article>

            <!-- AVISTAMIENTOS -->
            <aside class="mt-5 bg-white border-[3px] border-[#111] rounded-[20px] p-[22px]">
                <h3 class="flex items-center gap-2 text-[17px] font-bold text-[#111] mb-4">
                    <i class="bi bi-binoculars-fill text-[#ff7b00]" aria-hidden="true"></i>
                    {{ publicacion.avistamientos?.length ? `${publicacion.avistamientos.length} avistamiento${publicacion.avistamientos.length !== 1 ? 's' : ''} reportado${publicacion.avistamientos.length !== 1 ? 's' : ''}` : 'Avistamientos' }}
                </h3>

                <template v-if="publicacion.avistamientos && publicacion.avistamientos.length">
                    <div id="leaflet-vistos" class="h-[220px] w-full border-2 border-[#111] rounded-[14px] mb-4" aria-label="Mapa de avistamientos reportados"></div>

                    <ul class="flex flex-col gap-2.5 m-0 p-0 list-none">
                        <li v-for="a in publicacion.avistamientos" :key="a.id" class="flex gap-2.5 p-2.5 bg-[#f4f1ea] rounded-[12px]">
                            <div class="shrink-0 w-[30px] h-[30px] rounded-full bg-white border-2 border-[#111] flex items-center justify-center text-[#ff7b00] text-[13px]">
                                <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p v-if="a.direccion" class="text-[13px] font-bold text-[#111] m-0 mb-0.5">{{ a.direccion }}</p>
                                <p v-if="a.descripcion" class="text-[13px] text-[#555] m-0 mb-1">{{ a.descripcion }}</p>
                                <time :datetime="a.created_at" class="text-[11px] text-[#999]">{{ formatFechaHora(a.created_at) }}</time>
                            </div>
                        </li>
                    </ul>
                </template>

                <div v-else class="flex items-center gap-2 bg-[#f4f1ea] rounded-[12px] p-3.5 text-[13px] text-[#888]" role="status">
                    <i class="bi bi-info-circle text-[#ff7b00]" aria-hidden="true"></i>
                    Todavía nadie reportó haberla visto. Si la reconocés, sé el primero en avisar.
                </div>
            </aside>
        </div>

        <!-- MODAL REPORTAR AVISTAMIENTO -->
        <div v-if="mostrarModalAvistamiento" class="fixed inset-0 bg-black/45 z-[200] flex items-center justify-center p-5" @click.self="cerrarModalAvistamiento" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <form @submit.prevent="enviarAvistamiento" class="bg-white border-[3px] border-[#111] rounded-[20px] p-6 w-full max-w-[480px] max-h-[90vh] overflow-y-auto relative">
                
                <button type="button" class="absolute top-3.5 right-3.5 w-8 h-8 rounded-full bg-[#f4f1ea] border-2 border-[#111] flex items-center justify-center cursor-pointer hover:bg-[#e5e1d8] transition-colors" @click="cerrarModalAvistamiento" aria-label="Cerrar ventana">
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                </button>

                <h3 id="modal-title" class="text-[19px] font-bold text-[#111] mb-1">Reportá dónde la viste</h3>
                <p class="text-[13px] text-[#888] mb-3.5">Tocá en el mapa el lugar exacto (podés mover el pin arrastrándolo)</p>

                <div v-if="avistForm.errors.general" class="flex items-center gap-2 bg-[#fff3f0] border-[1.5px] border-[#f5c4b3] text-[#993c1d] rounded-[10px] py-2.5 px-3.5 text-[13px] mb-3.5" role="alert">
                    <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i> {{ avistForm.errors.general }}
                </div>

                <div id="leaflet-avistamiento" class="h-[260px] w-full border-2 border-[#111] rounded-[14px] mb-4"></div>

                <fieldset class="mb-3.5 border-none p-0 m-0">
                    <label for="descripcion-avistamiento" class="block text-xs font-bold text-[#888] uppercase mb-1.5">Descripción (opcional)</label>
                    <textarea 
                        id="descripcion-avistamiento"
                        v-model="avistForm.descripcion" 
                        rows="2" 
                        placeholder="Ej: la vi cruzando la plaza, parecía asustada..." 
                        class="w-full py-2.5 px-3 rounded-[10px] border-2 border-[#ddd] text-sm outline-none font-['Space_Grotesk'] focus:border-[#ff7b00] transition-colors"
                    ></textarea>
                </fieldset>

                <fieldset class="mb-3.5 border-none p-0 m-0">
                    <label for="direccion-avistamiento" class="block text-xs font-bold text-[#888] uppercase mb-1.5">Dirección aproximada (opcional)</label>
                    <input 
                        id="direccion-avistamiento"
                        type="text" 
                        v-model="avistForm.direccion" 
                        placeholder="Ej: Av. Rivadavia y Mitre" 
                        class="w-full py-2.5 px-3 rounded-[10px] border-2 border-[#ddd] text-sm outline-none font-['Space_Grotesk'] focus:border-[#ff7b00] transition-colors"
                    >
                </fieldset>

                <p class="flex items-center gap-1.5 text-xs text-[#888] mb-2">
                    <i class="bi bi-pin-map-fill" aria-hidden="true"></i> Solo se puede marcar dentro del cuadro punteado
                </p>

                <div aria-live="polite">
                    <p v-if="fueraDeZona" class="flex items-center gap-1.5 text-[13px] mb-3 text-[#ff3b3b] font-semibold">
                        <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i> Ese punto está fuera de la zona habilitada
                    </p>
                    
                    <p v-else-if="!pinColocado" class="flex items-center gap-1.5 text-[13px] text-[#c05a1a] mb-3">
                        <i class="bi bi-info-circle" aria-hidden="true"></i> Tocá el mapa para marcar el lugar
                    </p>
                </div>

                <button 
                    type="submit"
                    class="w-full p-[13px] bg-[#ff7b00] text-white border-2 border-[#111] rounded-[12px] font-bold text-[15px] cursor-pointer flex items-center justify-center gap-1.5 transition-colors disabled:opacity-50 disabled:cursor-not-allowed hover:bg-[#e06c00]" 
                    :disabled="!pinColocado || avistForm.processing"
                >
                    <i v-if="avistForm.processing" class="bi bi-arrow-repeat animate-spin" aria-hidden="true"></i>
                    {{ avistForm.processing ? 'Enviando...' : 'Enviar reporte' }}
                </button>
            </form>
        </div>
    </main>
</template>

<script setup>
import { computed, ref, nextTick, onMounted, onBeforeUnmount } from 'vue'
import QrcodeVue from 'qrcode.vue'
import { linkContacto } from '@/contacto'
import { optimizarImagen } from '@/cloudinary'
import { Link, useForm } from '@inertiajs/vue3'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png'
import markerIcon from 'leaflet/dist/images/marker-icon.png'
import markerShadow from 'leaflet/dist/images/marker-shadow.png'
import { CENTRO_SALADILLO, LIMITES_ZONA, LIMITES_PANEO, dentroDeZona } from '@/zonaMapa'

// --- Configuración Global Leaflet ---
delete L.Icon.Default.prototype._getIconUrl
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
})

const props = defineProps({
    publicacion: {
        type: Object,
        required: true,
    },
})

// --- Lógica de Galería e Imágenes ---
const indiceActual = ref(0)
const RELACION_IMAGEN = { ancho: 800, alto: 600 } 

const todasLasImagenes = computed(() => {
    const imgs = []
    if (props.publicacion.imagen) imgs.push(props.publicacion.imagen)
    if (props.publicacion.imagenes && props.publicacion.imagenes.length) {
        props.publicacion.imagenes.forEach(img => imgs.push(img.url))
    }
    return imgs.map(url => optimizarImagen(url, RELACION_IMAGEN))
})

const imagenActual = computed(() => todasLasImagenes.value[indiceActual.value] ?? null)

const siguiente = () => {
    indiceActual.value = (indiceActual.value + 1) % todasLasImagenes.value.length
}
const anterior = () => {
    indiceActual.value = (indiceActual.value - 1 + todasLasImagenes.value.length) % todasLasImagenes.value.length
}

// --- Utilidades de Formateo ---
const formatFecha = (fecha) => {
    if (!fecha) return ''
    return new Date(fecha).toLocaleDateString('es-AR', { day: '2-digit', month: 'long', year: 'numeric', timeZone: 'America/Argentina/Buenos_Aires' })
}

const formatFechaHora = (fecha) => {
    if (!fecha) return ''
    return new Date(fecha).toLocaleDateString('es-AR', {
        day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', timeZone: 'America/Argentina/Buenos_Aires'
    })
}

// --- Lógica de Mapas (Leaflet) ---
let mapaVistosInstance = null

onMounted(() => {
    if (!props.publicacion.avistamientos || !props.publicacion.avistamientos.length) return

    nextTick(() => {
        mapaVistosInstance = L.map('leaflet-vistos', {
            maxBounds: LIMITES_PANEO,
            maxBoundsViscosity: 0.8,
            minZoom: 11,
            zoomControl: false,
        }).setView(CENTRO_SALADILLO, 13)

        L.control.zoom({ position: 'topright' }).addTo(mapaVistosInstance)

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap',
            maxZoom: 19,
        }).addTo(mapaVistosInstance)

        L.rectangle(LIMITES_ZONA, {
            color: '#111', weight: 2, dashArray: '6, 5',
            fillColor: '#ff7b00', fillOpacity: 0.05, interactive: false,
        }).addTo(mapaVistosInstance)

        const puntos = []

        props.publicacion.avistamientos.forEach((a) => {
            const marker = L.marker([a.latitud, a.longitud]).addTo(mapaVistosInstance)
            marker.bindPopup(`
                <div style="font-family:sans-serif;font-size:13px;min-width:150px;">
                    ${a.direccion ? `<b>${a.direccion}</b><br>` : ''}
                    ${a.descripcion ? `<p style="margin:4px 0;">${a.descripcion}</p>` : ''}
                    <span style="color:#888;font-size:12px;">${formatFechaHora(a.created_at)}</span>
                </div>
            `)
            puntos.push([a.latitud, a.longitud])
        })

        if (puntos.length > 1) {
            mapaVistosInstance.fitBounds(puntos, { padding: [30, 30] })
        } else if (puntos.length === 1) {
            mapaVistosInstance.setView(puntos[0], 15)
        }

        setTimeout(() => mapaVistosInstance && mapaVistosInstance.invalidateSize(), 200)
    })
})

onBeforeUnmount(() => {
    if (mapaVistosInstance) {
        mapaVistosInstance.remove()
        mapaVistosInstance = null
    }
    cerrarModalAvistamiento()
})

// --- Lógica de Compartir ---
const compartiendo = ref(false)
const mostrarMenuCompartir = ref(false)
const esMobil = /Android|iPhone|iPad|iPod|Mobile/i.test(navigator.userAgent)

const compartir = async () => {
    const url = window.location.href
    const texto = `${props.publicacion.titulo}\n${props.publicacion.descripcion || ''}\n\nMirá si la reconocés: ${url}`

    if (esMobil && navigator.canShare && props.publicacion.imagen) {
        try {
            compartiendo.value = true
            const respuesta = await fetch(props.publicacion.imagen)

            if (!respuesta.ok) throw new Error(`Status ${respuesta.status}`)

            const blob = await respuesta.blob()
            const archivo = new File([blob], 'mascota.jpg', { type: blob.type || 'image/jpeg' })

            if (navigator.canShare({ files: [archivo] })) {
                await navigator.share({ files: [archivo], title: props.publicacion.titulo, text: texto })
                return
            }
        } catch (e) {
            console.error("Error compartiendo archivo:", e)
        } finally {
            compartiendo.value = false
        }
    }

    if (esMobil && navigator.share) {
        try {
            await navigator.share({ title: props.publicacion.titulo, text: texto, url })
            return
        } catch (e) {
            if (e.name !== 'AbortError') console.error("Error compartiendo link:", e)
            return
        }
    }

    mostrarMenuCompartir.value = true
}

const compartirWhatsapp = () => {
    const url = window.location.href
    const texto = `${props.publicacion.titulo}\n${props.publicacion.descripcion || ''}\n\nMirá si la reconocés: ${url}`
    window.open(`https://wa.me/?text=${encodeURIComponent(texto)}`, '_blank', 'noopener,noreferrer')
    mostrarMenuCompartir.value = false
}

const compartirFacebook = () => {
    const url = window.location.href
    window.open(
        `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`,
        '_blank',
        'width=600,height=500,noopener,noreferrer'
    )
    mostrarMenuCompartir.value = false
}

// --- Lógica Modal de Avistamientos ---
const mostrarModalAvistamiento = ref(false)
const pinColocado = ref(false)
const fueraDeZona = ref(false)
let mapaAvistamiento = null
let marcadorAvistamiento = null

const avistForm = useForm({
    latitud: null,
    longitud: null,
    descripcion: '',
    direccion: '',
})

const abrirModalAvistamiento = () => {
    mostrarModalAvistamiento.value = true
    pinColocado.value = false
    fueraDeZona.value = false
    avistForm.reset()

    nextTick(() => {
        if (mapaAvistamiento) {
            mapaAvistamiento.remove()
        }

        mapaAvistamiento = L.map('leaflet-avistamiento', {
            maxBounds: LIMITES_PANEO,
            maxBoundsViscosity: 0.8,
            minZoom: 11,
        }).setView(CENTRO_SALADILLO, 13)

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap',
            maxZoom: 19,
        }).addTo(mapaAvistamiento)

        L.rectangle(LIMITES_ZONA, {
            color: '#111',
            weight: 3,
            dashArray: '8, 6',
            fillColor: '#ff7b00',
            fillOpacity: 0.06,
        }).addTo(mapaAvistamiento)

        mapaAvistamiento.on('click', (e) => {
            colocarPin(e.latlng.lat, e.latlng.lng)
        })

        setTimeout(() => mapaAvistamiento && mapaAvistamiento.invalidateSize(), 200)
    })
}

const colocarPin = (lat, lng) => {
    if (!dentroDeZona(lat, lng)) {
        fueraDeZona.value = true
        setTimeout(() => { fueraDeZona.value = false }, 2500)
        return
    }

    fueraDeZona.value = false
    avistForm.latitud = lat
    avistForm.longitud = lng
    pinColocado.value = true

    if (marcadorAvistamiento) {
        marcadorAvistamiento.setLatLng([lat, lng])
    } else {
        marcadorAvistamiento = L.marker([lat, lng], { draggable: true }).addTo(mapaAvistamiento)
        marcadorAvistamiento.on('dragend', () => {
            const pos = marcadorAvistamiento.getLatLng()

            if (!dentroDeZona(pos.lat, pos.lng)) {
                marcadorAvistamiento.setLatLng([avistForm.latitud, avistForm.longitud])
                fueraDeZona.value = true
                setTimeout(() => { fueraDeZona.value = false }, 2500)
                return
            }

            avistForm.latitud = pos.lat
            avistForm.longitud = pos.lng
        })
    }
}

const cerrarModalAvistamiento = () => {
    mostrarModalAvistamiento.value = false
    if (mapaAvistamiento) {
        mapaAvistamiento.remove()
        mapaAvistamiento = null
        marcadorAvistamiento = null
    }
}

const enviarAvistamiento = () => {
    avistForm.post(`/publicaciones/${props.publicacion.id}/avistamientos`, {
        preserveScroll: true,
        onSuccess: () => {
            cerrarModalAvistamiento()
        },
    })
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&display=swap');
</style>