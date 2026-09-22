<template>
    <div class="mias-wrapper">

        <div class="hero">
            <h1>Mis <span class="highlight">publicaciones</span></h1>
            <p>Administrá tus avisos: editá los datos, marcá una mascota como encontrada, o eliminá la publicación.</p>

            <Link href="/publicaciones/crear" class="btn-publicar">
                + Publicar mascota perdida
            </Link>

        </div>

        <div v-if="$page.props.flash?.success" class="flash-success">
            <i class="bi bi-check-circle-fill"></i> {{ $page.props.flash.success }}
        </div>

        <div class="cards-grid" v-if="publicaciones.length > 0">
            <div class="mascota-card" v-for="p in publicaciones" :key="p.id">
                <div class="card-img-wrap">
                    <img
                        v-if="p.imagen"
                        :src="optimizarImagen(p.imagen, { ancho: 600, alto: 600 })"
                        :alt="p.titulo"
                        class="card-img"
                        loading="lazy"
                    >
                    <div v-else class="card-img-placeholder"><span>🐾</span></div>
                    <span class="card-badge" :class="`badge-${p.estado}`">{{ p.estado }}</span>
                </div>

                <div class="card-body">
                    <h3 class="card-titulo">{{ p.titulo }}</h3>
                    <p class="card-descripcion">{{ p.descripcion }}</p>
                    <div class="info-row">
                        <i class="bi bi-calendar2"></i>
                        <span>{{ formatFecha(p.fecha_evento) }}</span>
                    </div>

                    <div class="card-acciones">
                        <Link :href="`/publicaciones/${p.id}/editar`" class="btn-accion btn-editar">
                            <i class="bi bi-pencil-fill"></i> Editar
                        </Link>

                        <button
                            v-if="p.estado === 'perdido'"
                            class="btn-accion btn-encontrado"
                            @click="marcarResuelto(p)"
                        >
                            <i class="bi bi-check-circle-fill"></i> Marcar como encontrado
                        </button>

                        <button
                            v-if="p.estado === 'encontrado'"
                            class="btn-accion btn-encontrado"
                            @click="marcarResuelto(p)"
                        >
                            <i class="bi bi-check-circle-fill"></i> Marcar como resuelto
                        </button>

                        <button class="btn-accion btn-eliminar" @click="eliminar(p)">
                            <i class="bi bi-trash-fill"></i> Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="empty-state" v-else>
            <div class="empty-icon">🐾</div>
            <h3>Todavía no publicaste ninguna mascota</h3>
            <p>Cuando publiques un aviso, vas a poder administrarlo acá.</p>
            <Link href="/publicaciones/crear" class="btn-publicar btn-publicar-empty">
                + Publicar mascota perdida
            </Link>
        </div>

    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import { optimizarImagen } from '@/cloudinary'

defineProps({
    publicaciones: {
        type: Array,
        default: () => [],
    },
})

const formatFecha = (fecha) => {
    if (!fecha) return ''
    return new Date(fecha).toLocaleDateString('es-AR', { day: '2-digit', month: 'long', year: 'numeric' })
}

const marcarResuelto = (p) => {
    router.patch(`/publicaciones/${p.id}/resuelto`, {}, { preserveScroll: true })
}

const eliminar = (p) => {
    if (!confirm(`¿Seguro que querés eliminar "${p.titulo}"? Esta acción no se puede deshacer.`)) return
    router.delete(`/publicaciones/${p.id}`, { preserveScroll: true })
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&display=swap');

* { font-family: 'Space Grotesk', sans-serif; }

.mias-wrapper {
    min-height: 100vh;
    background: #f4f1ea;
    padding-bottom: 80px;
}

.hero {
    text-align: center;
    padding: 70px 20px 40px;
}
.hero h1 { font-size: 48px; font-weight: 700; color: #111; margin-bottom: 12px; }
.highlight { color: #ff7b00; }
.hero p { color: #555; font-size: 15px; max-width: 520px; margin: 0 auto 24px; line-height: 1.6; }

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
.btn-publicar:hover { transform: translate(-3px, -3px); box-shadow: 9px 9px 0 #111; color: #fff; }
.btn-publicar-empty { margin-top: 16px; }

.flash-success {
    max-width: 700px;
    margin: 0 auto 24px;
    padding: 14px 20px;
    background: #e8f9ee;
    border: 2px solid #22c55e;
    border-radius: 14px;
    color: #15803d;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 340px));
    gap: 28px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 30px;
    align-items: start;
}

.mascota-card {
    background: #fff;
    border: 3px solid #111;
    border-radius: 24px;
    overflow: hidden;
}

.card-img-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    border-bottom: 3px solid #111;
    background: #ece7db;
}
.card-img { width: 100%; height: 100%; object-fit: cover; display: block; }
.card-img-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    font-size: 48px;
    background: linear-gradient(135deg, #e8e4db 0%, #d9d3c7 100%);
}

.card-badge {
    position: absolute; top: 12px; left: 12px;
    font-size: 12px; font-weight: 700;
    padding: 4px 12px; border-radius: 999px;
    border: 2px solid #111; text-transform: capitalize;
}
.badge-perdido    { background: #ff3b3b; color: #fff; }
.badge-encontrado { background: #2563eb; color: #fff; }
.badge-resuelto   { background: #22c55e; color: #fff; }

.card-body { padding: 20px; }
.card-titulo { font-size: 19px; font-weight: 700; color: #111; margin-bottom: 6px; }
.card-descripcion {
    font-size: 13px; color: #555; line-height: 1.5; margin-bottom: 12px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.info-row { display: flex; align-items: center; gap: 8px; font-size: 12px; color: #888; font-weight: 500; margin-bottom: 16px; }
.info-row i { color: #ff7b00; }

.card-acciones { display: flex; flex-direction: column; gap: 8px; }
.btn-accion {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    padding: 10px; border-radius: 12px; font-weight: 700; font-size: 13px;
    border: 2px solid #111; cursor: pointer; text-decoration: none;
    transition: .15s; font-family: 'Space Grotesk', sans-serif;
}
.btn-editar     { background: #fff; color: #111; }
.btn-editar:hover     { background: #f4f1ea; transform: translate(-1px,-1px); box-shadow: 3px 3px 0 #111; }
.btn-encontrado { background: #22c55e; color: #fff; border-color: #15803d; }
.btn-encontrado:hover { background: #16a34a; transform: translate(-1px,-1px); box-shadow: 3px 3px 0 #111; }
.btn-eliminar   { background: #fff; color: #ff3b3b; border-color: #ff3b3b; }
.btn-eliminar:hover   { background: #ff3b3b; color: #fff; transform: translate(-1px,-1px); box-shadow: 3px 3px 0 #111; }

.empty-state { text-align: center; padding: 80px 20px; }
.empty-icon { font-size: 60px; margin-bottom: 16px; }
.empty-state h3 { font-size: 26px; font-weight: 700; color: #111; margin-bottom: 8px; }
.empty-state p { color: #666; font-size: 15px; }

@media (max-width: 768px) {
    .hero h1 { font-size: 36px; }
    .cards-grid { padding: 0 16px; gap: 20px; }
}
</style>