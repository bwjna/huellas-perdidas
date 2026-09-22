<template>
    <div class="encontradas-wrapper">

        <!-- HERO -->
        <div class="hero">
            <h1>Mascotas <span class="highlight">encontradas</span></h1>
            <p>Estas mascotas fueron encontradas y esperan reunirse con sus familias. Si reconocés a alguna, contactate con quien la encontró.</p>
        </div>

        <!-- FILTROS -->
        <div class="filtros-bar">
            <input
                v-model="busqueda"
                type="text"
                class="filtro-input"
                placeholder="🔍  Buscar por nombre o zona..."
            >
            <select v-model="zonaFiltro" class="filtro-select">
                <option value="">Todas las zonas</option>
                <option v-for="zona in zonas" :key="zona" :value="zona">{{ zona }}</option>
            </select>
        </div>

        <!-- GRID DE TARJETAS -->
        <div class="cards-grid" v-if="mascotasFiltradas.length > 0">
            <div
                class="mascota-card"
                v-for="mascota in mascotasFiltradas"
                :key="mascota.id"
            >
                <div class="card-img-wrap">
                    <img
                        :src="mascota.foto ?? '/img/default_pet.png'"
                        :alt="mascota.nombre"
                        class="card-img"
                    >
                    <span class="card-badge">Encontrada</span>
                </div>

                <div class="card-body">
                    <h3 class="card-nombre">{{ mascota.nombre ?? 'Sin nombre' }}</h3>

                    <div class="card-info">
                        <div class="info-row">
                            <i class="bi bi-calendar-event"></i>
                            <span>{{ formatFecha(mascota.fecha_encontrada) }}</span>
                        </div>
                        <div class="info-row">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>{{ mascota.zona }}</span>
                        </div>
                        <div class="info-row">
                            <i class="bi bi-telephone-fill"></i>
                            <span>{{ mascota.contacto }}</span>
                        </div>
                    </div>

                    <a :href="`tel:${mascota.contacto}`" class="btn-contactar">
                        <i class="bi bi-whatsapp me-1"></i> Contactar
                    </a>
                </div>
            </div>
        </div>

        <!-- ESTADO VACÍO -->
        <div class="empty-state" v-else>
            <div class="empty-icon">🐾</div>
            <h3>No hay resultados</h3>
            <p>Probá con otra búsqueda o zona.</p>
        </div>

    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    mascotas: {
        type: Array,
        default: () => [],
    },
})

const busqueda   = ref('')
const zonaFiltro = ref('')

// Zonas únicas para el select
const zonas = computed(() =>
    [...new Set(props.mascotas.map(m => m.zona).filter(Boolean))]
)

// Filtrado reactivo
const mascotasFiltradas = computed(() => {
    return props.mascotas.filter(m => {
        const texto = busqueda.value.toLowerCase()
        const coincideTexto =
            (m.nombre ?? '').toLowerCase().includes(texto) ||
            (m.zona   ?? '').toLowerCase().includes(texto)
        const coincideZona =
            zonaFiltro.value === '' || m.zona === zonaFiltro.value
        return coincideTexto && coincideZona
    })
})

function formatFecha(fecha) {
    if (!fecha) return 'Fecha desconocida'
    return new Date(fecha).toLocaleDateString('es-AR', {
        day: '2-digit', month: 'long', year: 'numeric'
    })
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&display=swap');

* { font-family: 'Space Grotesk', sans-serif; }

.encontradas-wrapper {
    min-height: 100vh;
    background: #f4f1ea;
    overflow-x: hidden;
    position: relative;
    padding-bottom: 80px;
}

/* FONDO GRILLA */
.encontradas-wrapper::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(0,0,0,.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0,0,0,.05) 1px, transparent 1px);
    background-size: 40px 40px;
    z-index: 0;
}

/* STICKERS */
.sticker {
    position: absolute;
    padding: 10px 20px;
    border-radius: 999px;
    font-weight: 700;
    z-index: 2;
    box-shadow: 0 10px 25px rgba(0,0,0,.12);
    font-size: 14px;
}
.sticker1 { top: 120px; left: 120px; background: #111; color: #fff; transform: rotate(-8deg); }
.sticker2 { bottom: 80px; right: 80px; background: #ff7b00; color: #fff; transform: rotate(8deg); }

/* HERO */
.hero {
    position: relative;
    z-index: 3;
    text-align: center;
    padding: 80px 20px 40px;
}
.hero-badge {
    display: inline-block;
    background: #111;
    color: #fff;
    padding: 6px 18px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 18px;
}
.hero h1 {
    font-size: 56px;
    font-weight: 700;
    color: #111;
    line-height: 1;
    margin-bottom: 14px;
}
.highlight { color: #ff7b00; }
.hero p {
    color: #555;
    font-size: 16px;
    max-width: 560px;
    margin: 0 auto;
    line-height: 1.6;
}

/* FILTROS */
.filtros-bar {
    position: relative;
    z-index: 3;
    display: flex;
    gap: 12px;
    justify-content: center;
    padding: 0 20px 40px;
    flex-wrap: wrap;
}
.filtro-input,
.filtro-select {
    padding: 12px 18px;
    border-radius: 16px;
    border: 3px solid #111;
    background: #fff;
    font-size: 14px;
    font-family: 'Space Grotesk', sans-serif;
    outline: none;
    transition: .2s;
    box-shadow: 4px 4px 0 #111;
}
.filtro-input { width: 320px; }
.filtro-select { min-width: 180px; cursor: pointer; }
.filtro-input:focus,
.filtro-select:focus {
    transform: translate(-2px, -2px);
    box-shadow: 6px 6px 0 #ff7b00;
}

/* GRID */
.cards-grid {
    position: relative;
    z-index: 3;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 28px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 30px;
}

/* TARJETA */
.mascota-card {
    background: #fff;
    border: 3px solid #111;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 8px 8px 0 #111;
    transition: .25s;
}
.mascota-card:hover {
    transform: translate(-4px, -4px);
    box-shadow: 12px 12px 0 #ff7b00;
}

.card-img-wrap {
    position: relative;
    height: 200px;
    overflow: hidden;
}
.card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.card-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #ff7b00;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 999px;
    border: 2px solid #111;
}

.card-body {
    padding: 20px;
}
.card-nombre {
    font-size: 22px;
    font-weight: 700;
    color: #111;
    margin-bottom: 14px;
}

.card-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 18px;
}
.info-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #555;
    font-weight: 500;
}
.info-row i {
    color: #ff7b00;
    font-size: 15px;
    flex-shrink: 0;
}

.btn-contactar {
    display: block;
    width: 100%;
    padding: 12px;
    background: #111;
    color: #fff;
    text-align: center;
    border-radius: 14px;
    font-weight: 700;
    font-size: 14px;
    text-decoration: none;
    border: 2px solid #111;
    transition: .2s;
}
.btn-contactar:hover {
    background: #ff7b00;
    transform: translate(-3px, -3px);
    box-shadow: 6px 6px 0 #111;
    color: #fff;
}

/* EMPTY STATE */
.empty-state {
    position: relative;
    z-index: 3;
    text-align: center;
    padding: 80px 20px;
}
.empty-icon { font-size: 60px; margin-bottom: 16px; }
.empty-state h3 { font-size: 28px; font-weight: 700; color: #111; margin-bottom: 8px; }
.empty-state p { color: #666; font-size: 15px; }

/* RESPONSIVE */
@media (max-width: 768px) {
    .hero h1 { font-size: 38px; }
    .filtro-input { width: 100%; }
    .cards-grid { padding: 0 16px; gap: 20px; }
    .sticker1, .sticker2 { display: none; }
}
</style>
