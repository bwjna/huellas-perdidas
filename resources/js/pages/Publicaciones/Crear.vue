<template>
  <div class="container mt-5" style="max-width: 600px;">
    <h2 class="mb-4 text-center">Publicar mascota perdida 🐾</h2>

    <div v-if="Object.keys(errors).length" class="alert alert-danger">
      <ul class="mb-0">
        <li v-for="error in errors" :key="error">{{ error }}</li>
      </ul>
    </div>

    <form @submit.prevent="submit" enctype="multipart/form-data">

      <h5 class="mb-3 mt-4">Datos de la mascota</h5>

      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" v-model="form.nombre" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Especie</label>
        <select v-model="form.especie" class="form-select" required>
          <option value="">Seleccioná</option>
          <option value="perro">Perro</option>
          <option value="gato">Gato</option>
          <option value="otro">Otro</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Raza</label>
        <input type="text" v-model="form.raza" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Color</label>
        <input type="text" v-model="form.color" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Tamaño</label>
        <select v-model="form.tamano" class="form-select">
          <option value="">Seleccioná</option>
          <option value="chico">Chico</option>
          <option value="mediano">Mediano</option>
          <option value="grande">Grande</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Edad (años)</label>
        <input type="number" v-model="form.edad" class="form-control" min="0">
      </div>

      <h5 class="mb-3 mt-4">Datos de la publicación</h5>

      <div class="mb-3">
        <label class="form-label">Título</label>
        <input type="text" v-model="form.titulo" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Zona</label>
        <input type="text" v-model="form.zona" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea v-model="form.descripcion" class="form-control" rows="3"></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Fecha del evento</label>
        <input type="date" v-model="form.fecha_evento" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Foto</label>
        <input type="file" class="form-control" accept="image/*" @change="form.imagen = $event.target.files[0]">
      </div>

      <button type="submit" class="btn btn-naranja w-100 mt-3">Publicar</button>
      <p class="text-center mt-3"><a href="/publicaciones">Cancelar</a></p>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

defineProps({
  errors: {
    type: Object,
    default: () => ({})
  }
})

const form = useForm({
  nombre: '',
  especie: '',
  raza: '',
  color: '',
  tamano: '',
  edad: '',
  titulo: '',
  zona: '',
  descripcion: '',
  fecha_evento: '',
  imagen: null,
})

const submit = () => {
  form.post('/publicaciones', {
    forceFormData: true
  })
}
</script>

<style scoped>
</style>