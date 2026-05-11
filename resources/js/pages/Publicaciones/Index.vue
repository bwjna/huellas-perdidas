<template>
  <div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Mascotas perdidas 🐾</h2>
      <a href="/publicaciones/crear" class="btn btn-naranja">+ Publicar mascota</a>
    </div>

    <div v-if="publicaciones.length === 0" class="text-center mt-5">
      <p class="text-muted">No hay publicaciones todavía.</p>
    </div>

    <div class="row">
      <div class="col-md-4 mb-4" v-for="p in publicaciones" :key="p.id">
        <div class="card h-100">
          <img v-if="p.imagen" :src="'/storage/' + p.imagen" class="card-img-top">
          <div v-else class="bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
            <span class="text-white">Sin foto</span>
          </div>
          <div class="card-body">
            <h5 class="card-title">{{ p.titulo }}</h5>
            <p class="card-text text-muted">{{ p.mascota?.nombre }} · {{ p.mascota?.especie }}</p>
            <p class="card-text">{{ p.descripcion }}</p>
            <p class="card-text"><small class="text-muted">📍 {{ p.zona }}</small></p>
          </div>
          <div class="card-footer">
            <span class="badge" :class="{
              'bg-danger': p.estado === 'perdido',
              'bg-success': p.estado === 'encontrado',
              'bg-secondary': p.estado === 'resuelto'
            }">{{ p.estado }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  publicaciones: {
    type: Array,
    default: () => []
  }
})
</script>

<style scoped>
.card-img-top {
  height: 300px;
  object-fit: contain;
  background: #f8f8f8;
}
</style>
