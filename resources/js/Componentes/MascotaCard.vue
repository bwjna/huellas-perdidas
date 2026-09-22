<template>
    <!-- Semántica: Usamos <article> para contenido independiente -->
    <article class="mascota-card" :aria-labelledby="`titulo-${publicacion.id}`">
        
        <!-- Semántica: <figure> para el contenedor de la imagen -->
        <figure class="card-img-wrap" :class="{ 'img-loading': publicacion.imagen && !cargada }">
            <div v-if="publicacion.imagen && !cargada" class="img-spinner" aria-hidden="true"></div>
            
            <img
                v-if="publicacion.imagen"
                :src="optimizarImagen(publicacion.imagen, { ancho: 600, alto: 600 })"
                :alt="`Fotografía de ${publicacion.titulo}`"
                class="card-img"
                :class="{ loaded: cargada }"
                loading="lazy"
                @load="cargada = true"
            >
            <div v-else class="card-img-placeholder" aria-hidden="true">
                <span>🐾</span>
            </div>
            
            <span class="card-badge" :class="`badge-${publicacion.estado}`">{{ publicacion.estado }}</span>
            
            <button type="button" class="btn-reporte" @click.prevent="reportar" aria-label="Reportar publicación" title="Reportar publicación">
                <i class="bi bi-flag-fill" aria-hidden="true"></i>
            </button>
        </figure>

        <div class="card-body">
            <!-- Wrapper superior que absorbe el crecimiento -->
            <div class="card-content">
                <h3 :id="`titulo-${publicacion.id}`" class="card-titulo">{{ publicacion.titulo }}</h3>
                <p class="card-descripcion">{{ publicacion.descripcion }}</p>
                
                <div class="info-row">
                    <i class="bi bi-calendar2" aria-hidden="true"></i>
                    <time :datetime="publicacion.fecha_evento">{{ formatFecha(publicacion.fecha_evento) }}</time>
                </div>
            </div>
            
            <!-- Flexbox dinámico: botones anclados al fondo -->
            <footer class="card-acciones">
                <a
                    v-if="linkContacto(publicacion)"
                    :href="linkContacto(publicacion).href"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn-contactar"
                >
                    <i :class="linkContacto(publicacion).tipo === 'email' ? 'bi bi-envelope-fill' : 'bi bi-whatsapp'" class="me-1" aria-hidden="true"></i>
                    Contactar
                </a>
                <span v-else class="btn-contactar btn-contactar-disabled" aria-disabled="true">
                    <i class="bi bi-slash-circle me-1" aria-hidden="true"></i> Sin contacto
                </span>
                
                <Link :href="`/publicaciones/${publicacion.id}`" class="btn-detalles">
                    Ver detalles
                </Link>
            </footer>
        </div>
    </article>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { linkContacto } from '@/contacto'
import { formatFecha } from '@/formato'
import { optimizarImagen } from '@/cloudinary'

const props = defineProps({
    publicacion: {
        type: Object,
        required: true,
    },
})

const cargada = ref(false)

const reportar = () => {
    if (!confirm('¿Querés reportar esta publicación?')) return

    router.post(`/publicaciones/${props.publicacion.id}/reporte`, {}, {
        preserveScroll: true,
        onSuccess: (page) => {
            const mensaje = page.props.flash?.success
            if (mensaje) alert(mensaje)
        },
        onError: (errors) => {
            if (errors.general) alert(errors.general)
        },
    })
}
</script>

<style scoped>
/* ESTRUCTURA FLEXBOX: Asegura que todas las tarjetas midan lo mismo en la grilla */
.mascota-card {
    display: flex;
    flex-direction: column;
    height: 100%;
    background: #fff;
    border: 3px solid #111;
    border-radius: 24px;
    overflow: hidden;
    transition: transform 0.25s, box-shadow 0.25s;
}

.mascota-card:hover {
    transform: translateY(-4px);
    box-shadow: 8px 8px 0 #111;
}

/* IMAGEN: Siempre cuadrada y cubriendo el espacio */
.card-img-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    border-bottom: 3px solid #111;
    background: #ece7db;
    margin: 0;
}

.img-spinner {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 36px;
    height: 36px;
    border: 4px solid rgba(255, 123, 0, 0.2);
    border-top-color: #ff7b00;
    border-radius: 50%;
    animation: girar-spinner 0.8s linear infinite;
    z-index: 2;
}

@keyframes girar-spinner {
    to { transform: translate(-50%, -50%) rotate(360deg); }
}

.card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.card-img.loaded {
    opacity: 1;
}

.card-img-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #e8e4db 0%, #d9d3c7 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
}

/* BADGES Y BOTONES FLOTANTES */
.card-badge {
    position: absolute; 
    top: 12px; 
    left: 12px;
    font-size: 12px; 
    font-weight: 700;
    padding: 4px 12px; 
    border-radius: 999px;
    border: 2px solid #111; 
    text-transform: capitalize;
    z-index: 10;
}
.badge-perdido    { background: #ff3b3b; color: #fff; }
.badge-encontrado { background: #2563eb; color: #fff; }
.badge-resuelto   { background: #22c55e; color: #fff; }

.btn-reporte {
    position: absolute; 
    top: 12px; 
    right: 12px;
    width: 34px; 
    height: 34px; 
    border-radius: 999px;
    border: 2px solid #111; 
    background: #fff; 
    color: #111;
    font-size: 14px; 
    display: flex; 
    align-items: center; 
    justify-content: center;
    cursor: pointer; 
    box-shadow: 3px 3px 0 #111; 
    transition: all 0.2s;
    z-index: 10;
}
.btn-reporte:hover { 
    background: #ff3b3b; 
    color: #fff; 
    border-color: #ff3b3b; 
    transform: translate(-2px, -2px); 
    box-shadow: 5px 5px 0 #111; 
}

/* CUERPO DE LA TARJETA */
.card-body { 
    display: flex;
    flex-direction: column;
    flex: 1 1 auto;
    padding: 20px; 
}

.card-content {
    flex: 1 0 auto;
}

.card-titulo { 
    font-size: 20px; 
    font-weight: 700; 
    color: #111; 
    margin: 0 0 8px 0; 
}

.card-descripcion {
    font-size: 14px; 
    color: #555; 
    line-height: 1.5; 
    margin: 0 0 14px 0;
    display: -webkit-box; 
    -webkit-line-clamp: 2; 
    -webkit-box-orient: vertical; 
    overflow: hidden;
}

.info-row { 
    display: flex; 
    align-items: center; 
    gap: 8px; 
    font-size: 13px; 
    color: #888; 
    font-weight: 500; 
    margin-bottom: 20px; 
}

.info-row i { 
    color: #ff7b00; 
    font-size: 14px; 
}

/* ACCIONES (Siempre empujadas al fondo gracias a margin-top: auto) */
.card-acciones { 
    display: flex; 
    gap: 10px;
    margin-top: auto; 
    padding-top: 4px;
}

.btn-contactar, .btn-detalles {
    flex: 1; 
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 12px 8px; 
    text-align: center; 
    border-radius: 14px; 
    font-weight: 700; 
    font-size: 14px;
    text-decoration: none; 
    border: 2px solid #111; 
    transition: all 0.2s;
    cursor: pointer; 
}

.btn-contactar {
    background: #111; 
    color: #fff;
}
.btn-contactar:hover { 
    background: #ff7b00; 
    transform: translate(-2px, -2px); 
    box-shadow: 4px 4px 0 #111; 
    color: #fff; 
}

.btn-contactar-disabled { 
    background: #ddd; 
    color: #888; 
    border-color: #ddd;
    cursor: not-allowed; 
}
.btn-contactar-disabled:hover { 
    transform: none; 
    box-shadow: none; 
}

.btn-detalles {
    background: #fff; 
    color: #111;
    box-shadow: 3px 3px 0 #111;
    font-family: 'Space Grotesk', sans-serif;
}
.btn-detalles:hover { 
    background: #f4f1ea; 
    transform: translate(-2px, -2px); 
    box-shadow: 5px 5px 0 #111; 
    color: #111; 
}

</style>