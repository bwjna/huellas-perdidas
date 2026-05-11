<template>
  <div class="container mt-5" style="max-width: 450px;">
    <h2 class="mb-4 text-center">Iniciar Sesión</h2>

    <div v-if="errors && errors.email" class="alert alert-danger">
      {{ errors.email }}
    </div>
    <div v-if="errors && errors.password" class="alert alert-danger">
      {{ errors.password }}
    </div>

    <form @submit.prevent="submit">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" v-model="form.email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Contraseña</label>
        <input type="password" v-model="form.password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-naranja w-100">Iniciar Sesión</button>
      <p class="text-center mt-3">¿No tenés cuenta? <a href="/registro">Registrate</a></p>
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
  email: '',
  password: ''
})

const submit = () => {
  form.post('/login')
}
</script>

<style scoped>
</style>