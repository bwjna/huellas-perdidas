<template>
    <div class="mapa-wrapper">
        <div class="mapa-header">
            <h1>Mapa de <span class="highlight">avistamientos</span></h1>
            <p>Mirá dónde reportaron haber visto a cada mascota. Tocá el mapa para publicar una mascota perdida en ese lugar.</p>
            <span class="zona-badge"><i class="bi bi-pin-map-fill"></i> Zona limitada: Saladillo y alrededores</span>
        </div>

        <div class="mapa-layout">
            <div id="leaflet-mapa" class="leaflet-container"></div>

            <aside class="stats-sidebar">
                <div class="stat-card">
                    <span class="stat-numero">{{ estadisticas.publicadasActualmente }}</span>
                    <span class="stat-label">Publicadas actualmente</span>
                </div>
                <div class="stat-card stat-card-hoy">
                    <span class="stat-numero">{{ estadisticas.publicadasHoy }}</span>
                    <span class="stat-label">Publicadas hoy</span>
                </div>
                <div class="stat-tip">
                    <i class="bi bi-info-circle"></i>
                    Tocá cualquier punto dentro del cuadro para publicar una mascota perdida ahí mismo.
                </div>
            </aside>
        </div>

        <p v-if="avistamientos.length === 0 && publicaciones.length === 0" class="sin-avistamientos">
            Todavía no hay nada reportado en el mapa. Cuando alguien publique una mascota o reporte un avistamiento, va a aparecer acá.
        </p>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import L from 'leaflet'
import { optimizarImagen } from '@/cloudinary'
import 'leaflet/dist/leaflet.css'
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png'
import markerIcon from 'leaflet/dist/images/marker-icon.png'
import markerShadow from 'leaflet/dist/images/marker-shadow.png'
import { CENTRO_SALADILLO, LIMITES_ZONA, LIMITES_PANEO, dentroDeZona } from '@/zonaMapa'

// Fix clásico: Leaflet + bundlers (Vite/Webpack) rompen las rutas por defecto de los íconos.
delete L.Icon.Default.prototype._getIconUrl
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
})

const props = defineProps({
    avistamientos: { type: Array, default: () => [] },
    publicaciones: { type: Array, default: () => [] },
    estadisticas: {
        type: Object,
        default: () => ({ publicadasActualmente: 0, publicadasHoy: 0 }),
    },
})

// Ícono de hueso para las publicaciones (mascotas perdidas con ubicación propia)
const iconoHueso = L.divIcon({
    className: '',
    html: '<div class="marcador-hueso">🦴</div>',
    iconSize: [34, 34],
    iconAnchor: [17, 30],
    popupAnchor: [0, -28],
})

// Ícono para el pin temporal que se coloca al tocar el mapa (antes de confirmar publicar)
const iconoNuevo = L.divIcon({
    className: '',
    html: '<div class="marcador-nuevo">🦴</div>',
    iconSize: [38, 38],
    iconAnchor: [19, 34],
})

onMounted(() => {
    const mapa = L.map('leaflet-mapa', {
        maxBounds: LIMITES_PANEO,
        maxBoundsViscosity: 0.8,
        minZoom: 11,
        zoomControl: false, // lo agregamos abajo, del otro lado (el izquierdo lo tapa el panel de estadísticas)
    }).setView(CENTRO_SALADILLO, 13)

    L.control.zoom({ position: 'topright' }).addTo(mapa)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(mapa)

    // Rectángulo marcando la zona habilitada (Saladillo + Los Troncos).
    // interactive:false para que nunca "robe" el click y siempre llegue al mapa.
    L.rectangle(LIMITES_ZONA, {
        color: '#111',
        weight: 3,
        dashArray: '8, 6',
        fillColor: '#ff7b00',
        fillOpacity: 0.06,
        interactive: false,
    }).addTo(mapa)

    // Avistamientos reportados por la comunidad
    props.avistamientos.forEach((a) => {
        const marker = L.marker([a.latitud, a.longitud]).addTo(mapa)

        const foto = a.publicacion?.imagen
            ? `<img src="${optimizarImagen(a.publicacion.imagen, { ancho: 300 })}" style="width:100%;border-radius:8px;margin-bottom:6px;">`
            : ''
        const link = a.publicacion
            ? `<a href="/publicaciones/${a.publicacion.id}" style="color:#ff7b00;font-weight:700;text-decoration:none;">Ver publicación →</a>`
            : ''

        marker.bindPopup(`
            <div style="min-width:180px;font-family:sans-serif;">
                ${foto}
                <b>${a.publicacion?.titulo ?? 'Avistamiento'}</b><br>
                ${a.direccion ? `<span style="color:#666;font-size:13px;">${a.direccion}</span><br>` : ''}
                ${a.descripcion ? `<p style="font-size:13px;margin:6px 0;">${a.descripcion}</p>` : ''}
                ${link}
            </div>
        `)
    })

    // Mascotas perdidas publicadas directamente con su ubicación (ícono de hueso)
    props.publicaciones.forEach((p) => {
        const marker = L.marker([p.lat, p.lng], { icon: iconoHueso }).addTo(mapa)

        const foto = p.imagen
            ? `<img src="${optimizarImagen(p.imagen, { ancho: 300 })}" style="width:100%;border-radius:8px;margin-bottom:6px;">`
            : ''

        marker.bindPopup(`
            <div style="min-width:180px;font-family:sans-serif;">
                ${foto}
                <b>${p.titulo}</b><br>
                <a href="/publicaciones/${p.id}" style="color:#ff7b00;font-weight:700;text-decoration:none;">Ver publicación →</a>
            </div>
        `)
    })

    // Tocar el mapa: si cae dentro de la zona, ofrecemos publicar una mascota perdida ahí.
    // Armamos el popup con elementos DOM reales (no innerHTML + getElementById), así el
    // botón queda enganchado desde el primer momento, sin depender de timing de eventos.
    let marcadorNuevo = null

    mapa.on('click', (e) => {
        const { lat, lng } = e.latlng

        if (!dentroDeZona(lat, lng)) {
            return // fuera de la zona habilitada, no hacemos nada
        }

        if (marcadorNuevo) {
            mapa.removeLayer(marcadorNuevo)
        }

        marcadorNuevo = L.marker([lat, lng], { icon: iconoNuevo }).addTo(mapa)

        const contenedor = document.createElement('div')
        contenedor.style.fontFamily = 'sans-serif'
        contenedor.style.textAlign = 'center'

        const texto = document.createElement('p')
        texto.style.margin = '0 0 8px'
        texto.style.fontSize = '13px'
        texto.textContent = '📍 ¿Se te perdió una mascota acá?'

        const boton = document.createElement('button')
        boton.textContent = 'Publicarla acá'
        boton.style.cssText = 'background:#ff7b00;color:#fff;border:none;border-radius:8px;padding:8px 14px;font-weight:700;cursor:pointer;font-size:13px;'
        boton.addEventListener('click', () => {
            router.visit(`/publicaciones/crear?tipo=perdido&lat=${lat}&lng=${lng}`)
        })

        contenedor.appendChild(texto)
        contenedor.appendChild(boton)

        marcadorNuevo.bindPopup(contenedor).openPopup()
    })

    setTimeout(() => mapa.invalidateSize(), 200)
    window.addEventListener('resize', () => mapa.invalidateSize())

    // Ubicación actual del usuario (solo si cae dentro de la zona; ver nota más abajo)
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const miUbicacion = [pos.coords.latitude, pos.coords.longitude]
                if (!dentroDeZona(miUbicacion[0], miUbicacion[1])) return

                const iconoUbicacion = L.divIcon({
                    className: '',
                    html: '<div class="punto-azul-pulso"></div><div class="punto-azul"></div>',
                    iconSize: [22, 22],
                    iconAnchor: [11, 11],
                })

                L.marker(miUbicacion, { icon: iconoUbicacion, zIndexOffset: 1000 })
                    .addTo(mapa)
                    .bindPopup('Estás acá')

                mapa.setView(miUbicacion, 16)
            },
            () => {}
        )
    }
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&display=swap');

* { font-family: 'Space Grotesk', sans-serif; }

.mapa-wrapper {
    min-height: 100vh;
    background: #f4f1ea;
    padding: 50px 20px 80px;
}

.mapa-header {
    text-align: center;
    max-width: 640px;
    margin: 0 auto 28px;
}
.mapa-header h1 { font-size: 42px; font-weight: 700; color: #111; margin-bottom: 10px; }
.highlight { color: #ff7b00; }
.mapa-header p { color: #666; font-size: 15px; line-height: 1.6; }

.zona-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 14px;
    padding: 6px 14px;
    background: #fff8f0;
    border: 2px solid #ffb98a;
    border-radius: 999px;
    color: #c05a1a;
    font-size: 13px;
    font-weight: 700;
}

.mapa-layout {
    max-width: 1300px;
    margin: 0 auto;
    position: relative;
}

.stats-sidebar {
    position: absolute;
    top: 16px;
    left: 16px;
    z-index: 500;
    display: flex;
    flex-direction: column;
    gap: 12px;
    width: 220px;
}

.stat-card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(4px);
    border: 3px solid #111;
    border-radius: 16px;
    padding: 14px 16px;
    box-shadow: 4px 4px 0 #111;
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.stat-numero { font-size: 28px; font-weight: 700; color: #ff7b00; line-height: 1; }
.stat-card-hoy .stat-numero { color: #2563eb; }
.stat-label { font-size: 12px; color: #666; font-weight: 600; }

.stat-tip {
    background: rgba(255,248,240,0.95);
    backdrop-filter: blur(4px);
    border: 2px dashed #ffb98a;
    border-radius: 12px;
    padding: 10px 12px;
    font-size: 11px;
    color: #c05a1a;
    line-height: 1.5;
    display: flex;
    gap: 6px;
    align-items: flex-start;
}
.stat-tip i { flex-shrink: 0; margin-top: 1px; }

.leaflet-container {
    height: 78vh;
    width: 100%;
    border: 3px solid #111;
    border-radius: 20px;
    box-shadow: 6px 6px 0 #111;
}

.sin-avistamientos {
    max-width: 500px;
    margin: 24px auto 0;
    text-align: center;
    color: #888;
    font-size: 14px;
}

@media (max-width: 700px) {
    .stats-sidebar {
        position: static;
        width: auto;
        flex-direction: row;
        flex-wrap: wrap;
        margin-bottom: 14px;
    }
    .stat-card { flex: 1; min-width: 140px; }
    .stat-tip { width: 100%; }
}

@media (max-width: 640px) {
    .mapa-header h1 { font-size: 30px; }
    .leaflet-container { height: 55vh; border-radius: 16px; }
}
</style>

<!-- Sin "scoped": Leaflet inyecta este HTML por fuera del árbol de Vue. -->
<style>
.punto-azul {
    width: 16px;
    height: 16px;
    background: #2563eb;
    border: 3px solid #fff;
    border-radius: 50%;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.4);
    position: absolute;
    top: 3px;
    left: 3px;
}

.punto-azul-pulso {
    width: 22px;
    height: 22px;
    background: rgba(37, 99, 235, 0.35);
    border-radius: 50%;
    position: absolute;
    animation: pulso-ubicacion 1.6s ease-out infinite;
}

@keyframes pulso-ubicacion {
    0%   { transform: scale(0.6); opacity: 0.8; }
    100% { transform: scale(1.8); opacity: 0; }
}

.marcador-hueso {
    font-size: 26px;
    filter: drop-shadow(0 2px 2px rgba(0,0,0,0.35));
    transform: rotate(-40deg);
}

.marcador-nuevo {
    font-size: 30px;
    filter: drop-shadow(0 3px 3px rgba(0,0,0,0.4));
    transform: rotate(-40deg);
    animation: aparecer-hueso 0.25s ease-out;
}

@keyframes aparecer-hueso {
    from { transform: rotate(-40deg) scale(0); }
    to   { transform: rotate(-40deg) scale(1); }
}
</style>