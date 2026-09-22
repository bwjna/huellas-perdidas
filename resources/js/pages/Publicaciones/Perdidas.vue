<template>
    <div class="fondo-huesitos fondo-huesitos-naranja">

        <!-- HERO -->
        <div class="hero">
            <h1>Mascotas <span class="highlight">perdidas</span></h1>
            <p>Estas mascotas están esperando volver a casa. Si reconocés a alguna, contactate con su familia.</p>

            <div class="hero-actions">
                <Link href="/publicaciones/crear?tipo=perdido" class="btn-publicar">
                    + Publicar mascota perdida
                </Link>
                <button class="btn-filtrar" @click="panelAbierto = true">
                    <i class="bi bi-sliders"></i> Filtrar
                    <span v-if="cantidadFiltros > 0" class="filtrar-badge">{{ cantidadFiltros }}</span>
                </button>
            </div>
        </div>

        <PanelFiltros ref="panelRef" v-model:abierto="panelAbierto" @aplicar="onAplicarFiltros" />

        <!-- TAGS ACTIVOS + CONTADOR -->
        <div class="results-bar">
            <span class="resultados-contador">
                {{ publicacionesFiltradas.length }} resultado{{ publicacionesFiltradas.length !== 1 ? 's' : '' }}
            </span>
            <div class="active-tags">
                <button v-if="filtros.tipo" class="active-tag" @click="filtros.tipo = ''; filtros.raza = ''">{{ filtros.tipo }} ✕</button>
                <button v-if="filtros.sexo" class="active-tag" @click="filtros.sexo = ''">{{ filtros.sexo }} ✕</button>
                <button v-if="filtros.tamano" class="active-tag" @click="filtros.tamano = ''">{{ filtros.tamano }} ✕</button>
                <button v-if="filtros.color" class="active-tag" @click="filtros.color = ''">{{ filtros.color }} ✕</button>
                <button v-if="filtros.raza" class="active-tag" @click="filtros.raza = ''">{{ filtros.raza }} ✕</button>
            </div>
        </div>

        <!-- GRID -->
        <div class="cards-grid" v-if="publicacionesFiltradas.length > 0">
            <MascotaCard v-for="p in publicacionesFiltradas" :key="p.id" :publicacion="p" />
        </div>

        <!-- EMPTY STATE -->
        <div class="empty-state" v-else>
            <div class="empty-icon">🐾</div>
            <h3>No hay resultados</h3>
            <p>Probá con otra búsqueda o filtros diferentes.</p>
            <button class="btn-limpiar btn-limpiar-empty" @click="limpiarFiltros">Limpiar filtros</button>
        </div>

    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import PanelFiltros from '@/Componentes/PanelFiltros.vue'
import MascotaCard from '@/Componentes/MascotaCard.vue'

const props = defineProps({
    publicaciones: {
        type: Array,
        default: () => [],
    },
})

const panelAbierto = ref(false)
const panelRef = ref(null)

const filtros = ref({ tipo: '', sexo: '', tamano: '', color: '', raza: '' })

const cantidadFiltros = computed(() =>
    Object.values(filtros.value).filter(Boolean).length
)

const onAplicarFiltros = (nuevosFiltros) => {
    filtros.value = nuevosFiltros
}

const limpiarFiltros = () => {
    filtros.value = { tipo: '', sexo: '', tamano: '', color: '', raza: '' }
    panelRef.value?.limpiarTemp()
}

// Normaliza para comparar sin fricción: minúsculas, sin espacios extra y sin tildes.
// Así un valor guardado como " Negro", "NEGRO" o "Negro " encuentra el filtro "Negro".
const normalizar = (valor) =>
    String(valor ?? '')
        .toLowerCase()
        .trim()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')

const publicacionesFiltradas = computed(() => {
    return props.publicaciones.filter(p => {
        const coincideTipo =
            filtros.value.tipo === '' ||
            normalizar(p.mascota?.especie) === normalizar(filtros.value.tipo)

        const coincideSexo =
            filtros.value.sexo === '' ||
            // NULL o '' en la BD significan "desconocido" (la opción "No sé").
            normalizar(p.mascota?.sexo || 'desconocido') === normalizar(filtros.value.sexo)

        const coincideTamano =
            filtros.value.tamano === '' ||
            normalizar(p.mascota?.tamano) === normalizar(filtros.value.tamano)

        const coincideColor =
            filtros.value.color === '' ||
            normalizar(p.mascota?.color) === normalizar(filtros.value.color)

        const coincideRaza =
            filtros.value.raza === '' ||
            normalizar(p.mascota?.raza).includes(normalizar(filtros.value.raza))

        return coincideTipo && coincideSexo && coincideTamano && coincideColor && coincideRaza
    })
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&display=swap');

* { font-family: 'Space Grotesk', sans-serif; }


/* HERO */
.hero {
    position: relative;
    z-index: 3;
    text-align: center;
    padding: 80px 20px 40px;
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
    margin: 0 auto 28px;
    line-height: 1.6;
}

.hero-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
}

.btn-publicar {
    display: inline-block;
    padding: 14px 28px;
    background: #ff7b00;
    color: #fff;
    font-weight: 700;
    font-size: 15px;
    border-radius: 16px;
    border: 3px solid #111;
    box-shadow: 6px 6px 0 #111;
    text-decoration: none;
    transition: .2s;
}
.btn-publicar:hover {
    transform: translate(-3px, -3px);
    box-shadow: 9px 9px 0 #111;
    color: #fff;
}

.btn-filtrar {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 24px;
    background: #fff;
    color: #111;
    font-weight: 700;
    font-size: 15px;
    border-radius: 16px;
    border: 3px solid #111;
    box-shadow: 6px 6px 0 #111;
    cursor: pointer;
    transition: .2s;
    font-family: 'Space Grotesk', sans-serif;
}
.btn-filtrar:hover {
    transform: translate(-3px, -3px);
    box-shadow: 9px 9px 0 #111;
}
.filtrar-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 20px;
    height: 20px;
    background: #ff7b00;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #111;
}

/* RESULTS BAR */
.results-bar {
    position: relative;
    z-index: 3;
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto 20px;
    padding: 0 30px;
    flex-wrap: wrap;
    gap: 8px;
}
.resultados-contador {
    font-size: 13px;
    font-weight: 600;
    color: #888;
    letter-spacing: 0.03em;
    text-transform: uppercase;
}
.active-tags { display: flex; gap: 6px; flex-wrap: wrap; }
.active-tag {
    font-size: 12px;
    padding: 4px 12px;
    border-radius: 999px;
    background: #ffe8d0;
    color: #a04a10;
    border: 2px solid #111;
    cursor: pointer;
    font-weight: 600;
    font-family: 'Space Grotesk', sans-serif;
    box-shadow: 2px 2px 0 #111;
}

/* GRID */
.cards-grid {
    position: relative;
    z-index: 3;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 340px));
    gap: 28px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 30px;
    align-items: start;
}

/* EMPTY STATE */
.empty-state { position: relative; z-index: 3; text-align: center; padding: 80px 20px; }
.empty-icon { font-size: 60px; margin-bottom: 16px; }
.empty-state h3 { font-size: 28px; font-weight: 700; color: #111; margin-bottom: 8px; }
.empty-state p { color: #666; font-size: 15px; }
.btn-limpiar {
    padding: 7px 16px; border-radius: 999px; border: 2px solid #111;
    background: #111; color: #fff; font-size: 13px; font-weight: 600;
    font-family: 'Space Grotesk', sans-serif; cursor: pointer; transition: .15s; box-shadow: 3px 3px 0 #555;
}
.btn-limpiar:hover { background: #ff3b3b; border-color: #ff3b3b; box-shadow: 3px 3px 0 #111; }
.btn-limpiar-empty { margin-top: 16px; padding: 12px 28px; font-size: 15px; }

@media (max-width: 768px) {
    .hero h1 { font-size: 38px; }
    .cards-grid { padding: 0 16px; gap: 20px; }
}

.fondo-huesitos {
    position: relative;
    background: #f4f1ea;
    min-height: 100vh;
}

.fondo-huesitos::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image: url('/img/fondo-mascotas-perdidas.svg');
    background-repeat: repeat;
    background-size: 360px 360px;
    opacity: 0.06;
    pointer-events: none;
    z-index: 0;
}

/* Variante más marcada, por si la querés más visible en alguna sección puntual */
.fondo-huesitos.fondo-huesitos-fuerte::after {
    opacity: 0.2;
}

/* Variante en naranja, para que combine con la paleta de "perdidas" */
.fondo-huesitos.fondo-huesitos-naranja::after {
    opacity: 0.17;
}

</style>