<template>
  <div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4 text-center">Crear cuenta</h2>

    <div v-if="Object.keys(errors).length" class="alert alert-danger">
      <ul class="mb-0">
        <li v-for="error in errors" :key="error">{{ error }}</li>
      </ul>
    </div>

    <div v-if="sugerencias && sugerencias.length" class="alert alert-warning" style="padding: 12px;">
      <p class="mb-2">El nombre de usuario ya existe. Sugerencias:</p>
      <div class="sugerencias-container">
        <button
          v-for="s in sugerencias"
          :key="s"
          type="button"
          class="btn btn-sm btn-outline-secondary sugerencia"
          @click="form.nombre_usuario = s"
        >{{ s }}</button>
      </div>
    </div>

    <form @submit.prevent="submit">
      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" v-model="form.nombre" :class="['form-control', errors.nombre ? 'is-invalid' : '']" required>
        <div v-if="errors.nombre" class="invalid-feedback">{{ errors.nombre }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Apellido</label>
        <input type="text" v-model="form.apellido" :class="['form-control', errors.apellido ? 'is-invalid' : '']" required>
        <div v-if="errors.apellido" class="invalid-feedback">{{ errors.apellido }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Nombre de usuario</label>
        <input type="text" v-model="form.nombre_usuario" :class="['form-control', errors.nombre_usuario ? 'is-invalid' : '']" required>
        <div v-if="errors.nombre_usuario" class="invalid-feedback">{{ errors.nombre_usuario }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" v-model="form.email" :class="['form-control', errors.email ? 'is-invalid' : '']" required>
        <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Contraseña</label>
        <input type="password" v-model="form.password" :class="['form-control', errors.password ? 'is-invalid' : '']" required>
        <div v-if="errors.password" class="invalid-feedback">{{ errors.password }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Confirmar contraseña</label>
        <input type="password" v-model="form.password_confirmation" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-naranja w-100">Registrarse</button>
      <p class="text-center mt-3">¿Ya tenés cuenta? <a href="/login">Iniciá sesión</a></p>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

defineProps({
  errors: {
    type: Object,
    default: () => ({})
  },
  sugerencias: {
    type: Array,
    default: () => []
  }
})

const form = useForm({
  nombre: '',
  apellido: '',
  nombre_usuario: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const submit = () => {
  form.post('/registro')
}
</script>

<style scoped>
.sugerencias-container {
  padding-left: 0;
  margin-left: 0;
  width: 100%;
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}
.sugerencia {
  margin: 0;
}
.alert {
  overflow: hidden;
}
</style>