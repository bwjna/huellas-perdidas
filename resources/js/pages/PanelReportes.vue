<template>
  <div class="admin-panel">

    <SidebarAdmin activo="reportes" :cantidad-reportes="publicaciones.length" />

    <!-- MAIN -->
    <div class="main-content">

      <!-- TOPBAR -->
      <div class="topbar">
        <div>
          <h1>Reportes</h1>
          <p>Publicaciones que llegaron a {{ limiteReportes }} reportes y quedaron ocultas del sitio</p>
        </div>
      </div>

      <div v-if="mensaje" class="flash-msg">
        <i class="bi bi-check-circle-fill"></i> {{ mensaje }}
      </div>

      <!-- TABLA -->
      <div class="table-container">
        <div class="table-header">
          <h2>Publicaciones reportadas</h2>
        </div>

        <table v-if="publicaciones.length > 0">
          <thead>
            <tr>
              <th>Foto</th>
              <th>Título</th>
              <th>Tipo</th>
              <th>Reportes</th>
              <th>Dueño</th>
              <th>Oculta desde</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="p in publicaciones" :key="p.id">
              <td>
                <img
                  v-if="p.imagen"
                  :src="optimizarImagen(p.imagen, { ancho: 60, alto: 60 })"
                  class="foto-mini"
                  alt=""
                >
                <div v-else class="foto-mini foto-mini-vacia">🐾</div>
              </td>
              <td>
                <a :href="`/publicaciones/${p.id}`" target="_blank" class="titulo-link">{{ p.titulo }}</a>
              </td>
              <td>
                <span class="estado" :class="p.estado === 'perdido' ? 'perdida' : 'encontrada'">
                  {{ p.estado }}
                </span>
              </td>
              <td>
                <span class="reportes-count">{{ p.reportes }}</span>
              </td>
              <td>{{ p.usuario?.nombre ?? '—' }}</td>
              <td>{{ p.oculta_en ? new Date(p.oculta_en).toLocaleDateString('es-AR') : '—' }}</td>
              <td class="acciones">
                <button class="btn-restaurar" @click="restaurar(p)">
                  <i class="bi bi-eye-fill"></i> Restaurar
                </button>
                <button class="btn-eliminar" @click="eliminar(p)">
                  <i class="bi bi-trash-fill"></i> Eliminar
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-else class="sin-reportes">
          <span class="icono">✅</span>
          <p>No hay publicaciones reportadas por ahora.</p>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import SidebarAdmin from '@/Componentes/SidebarAdmin.vue'
import { optimizarImagen } from '@/cloudinary'

defineProps({
  publicaciones: {
    type: Array,
    default: () => []
  },
  limiteReportes: {
    type: Number,
    default: 20,
  }
})

const mensaje = ref('')

const restaurar = (p) => {
  if (!confirm(`¿Volver a mostrar "${p.titulo}"? Se reinicia su contador de reportes.`)) return

  router.patch(`/panel/reportes/${p.id}/restaurar`, {}, {
    preserveScroll: true,
    onSuccess: (page) => {
      mensaje.value = page.props.flash?.success ?? 'Publicación restaurada.'
      setTimeout(() => mensaje.value = '', 4000)
    },
  })
}

const eliminar = (p) => {
  if (!confirm(`¿Eliminar "${p.titulo}" definitivamente? Esta acción no se puede deshacer.`)) return

  router.delete(`/panel/reportes/${p.id}`, {
    preserveScroll: true,
    onSuccess: (page) => {
      mensaje.value = page.props.flash?.success ?? 'Publicación eliminada.'
      setTimeout(() => mensaje.value = '', 4000)
    },
  })
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

.admin-panel {
  display: flex;
  min-height: 100vh;
  background: #111827;
  font-family: 'Poppins', sans-serif;
}

.main-content { flex: 1; padding: 35px; }

.topbar { margin-bottom: 30px; }
.topbar h1 { color: white; font-size: 40px; margin-bottom: 8px; }
.topbar p { color: #9ca3af; }

.flash-msg {
  display: flex;
  align-items: center;
  gap: 8px;
  background: rgba(34,197,94,.15);
  color: #22c55e;
  border: 1px solid rgba(34,197,94,.3);
  padding: 12px 18px;
  border-radius: 12px;
  margin-bottom: 20px;
  font-size: 14px;
  font-weight: 500;
}

.table-container {
  background: #1f2937;
  border-radius: 25px;
  padding: 30px;
}
.table-header { margin-bottom: 25px; }
.table-header h2 { color: white; font-size: 28px; }

table { width: 100%; border-collapse: collapse; }
thead { background: #111827; }
th { color: #d1d5db; padding: 18px; text-align: left; font-weight: 600; }
td { padding: 16px 18px; color: #f3f4f6; border-bottom: 1px solid rgba(255,255,255,.05); vertical-align: middle; }

.foto-mini {
  width: 50px;
  height: 50px;
  border-radius: 10px;
  object-fit: cover;
  display: block;
}
.foto-mini-vacia {
  display: flex;
  align-items: center;
  justify-content: center;
  background: #374151;
  font-size: 20px;
}

.titulo-link { color: #ff7b00; text-decoration: none; font-weight: 600; }
.titulo-link:hover { text-decoration: underline; }

.estado {
  padding: 6px 12px;
  border-radius: 30px;
  font-size: 13px;
  font-weight: 600;
  text-transform: capitalize;
}
.perdida { background: rgba(239,68,68,.15); color: #ef4444; }
.encontrada { background: rgba(37,99,235,.15); color: #60a5fa; }

.reportes-count {
  font-weight: 700;
  color: #ef4444;
  font-size: 16px;
}

.acciones { display: flex; gap: 8px; flex-wrap: wrap; }
.btn-restaurar, .btn-eliminar {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 9px 14px;
  border-radius: 10px;
  border: none;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  font-family: 'Poppins', sans-serif;
  transition: .2s;
}
.btn-restaurar { background: rgba(34,197,94,.15); color: #22c55e; }
.btn-restaurar:hover { background: #22c55e; color: #fff; }
.btn-eliminar { background: rgba(239,68,68,.15); color: #ef4444; }
.btn-eliminar:hover { background: #ef4444; color: #fff; }

.sin-reportes {
  text-align: center;
  padding: 60px 20px;
  color: #9ca3af;
}
.sin-reportes .icono { font-size: 44px; display: block; margin-bottom: 12px; }

@media (max-width: 700px) {
  table { display: block; overflow-x: auto; }
}
</style>