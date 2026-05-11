<template>
  <div class="container mt-5">
    <h2 class="mb-4">Panel de Administración</h2>

    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Usuarios registrados</h5>
      </div>
      <div class="card-body">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Id</th>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>Usuario</th>
              <th>Email</th>
              <th>Rol</th>
              <th>Registro</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="usuario in usuarios" :key="usuario.id">
              <td>{{ usuario.id }}</td>
              <td>{{ usuario.nombre }}</td>
              <td>{{ usuario.apellido }}</td>
              <td>{{ usuario.nombre_usuario }}</td>
              <td>{{ usuario.email }}</td>
              <td>
                <span class="badge" :class="usuario.rol === 'admin' ? 'bg-danger' : 'bg-secondary'">
                  {{ usuario.rol }}
                </span>
              </td>
              <td>{{ new Date(usuario.created_at).toLocaleDateString('es-AR') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  usuarios: {
    type: Array,
    default: () => []
  }
})
</script>