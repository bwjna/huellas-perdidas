<template>
  <div class="admin-panel">

    <SidebarAdmin activo="usuarios" :cantidad-reportes="cantidadReportes" />

    <!-- MAIN -->
    <div class="main-content">

      <!-- TOPBAR -->
      <div class="topbar">
        <div>
          <h1>Panel Admin</h1>
          <p>Gestión de usuarios registrados</p>
        </div>
      </div>

      <!-- STATS -->
      <div class="stats">
        <div class="card-stat">
          <div class="card-stat-info">
            <p>Total Usuarios</p>
            <h2>{{ usuarios.length }}</h2>
            <span class="stat-change up">▲ activos esta semana</span>
          </div>
        </div>
        <div class="card-stat">
          <div class="card-stat-info">
            <p>Administradores</p>
            <h2>{{ usuarios.filter(u => u.rol === 'admin').length }}</h2>
            <span class="stat-change up">▲ con acceso total</span>
          </div>
        </div>
        <div class="card-stat">
          <div class="card-stat-info">
            <p>Usuarios normales</p>
            <h2>{{ usuarios.filter(u => u.rol !== 'admin').length }}</h2>
            <span class="stat-change up">▲ registrados</span>
          </div>
        </div>
        <div class="card-stat">
          <div class="card-stat-info">
            <p>Fecha actual</p>
            <h2 style="font-size:22px;">{{ new Date().toLocaleDateString('es-AR') }}</h2>
            <span class="stat-change neutral">● hoy</span>
          </div>
        </div>
      </div>

      <!-- TABLA -->
      <div class="table-container">
        <div class="table-header">
          <h2>Usuarios registrados</h2>
        </div>
        <table>
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
                <span class="estado" :class="usuario.rol === 'admin' ? 'perdida' : 'encontrada'">
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
import SidebarAdmin from '@/Componentes/SidebarAdmin.vue'

defineProps({
  usuarios: {
    type: Array,
    default: () => []
  },
  cantidadReportes: {
    type: Number,
    default: 0,
  }
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

.admin-panel {
  display: flex;
  min-height: 100vh;
  background: #111827;
  font-family: 'Poppins', sans-serif;
}

/* MAIN */
.main-content { flex: 1; padding: 35px; }

/* TOPBAR */
.topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 40px;
}
.topbar h1 { color: white; font-size: 40px; margin-bottom: 8px; }
.topbar p { color: #9ca3af; }

/* STATS */
.stats {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-bottom: 40px;
}

.card-stat {
  background: #1f2937;
  padding: 22px 24px;
  border-radius: 16px;
  display: flex;
  align-items: center;
  gap: 18px;
  border-left: 4px solid #ff7b00;
  transition: .3s;
}
.card-stat:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0,0,0,.3);
}

.card-stat-info p {
  color: #9ca3af;
  font-size: 13px;
  margin: 0 0 4px 0;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: .5px;
}

.card-stat-info h2 {
  color: white;
  font-size: 32px;
  font-weight: 700;
  margin: 0 0 6px 0;
  line-height: 1;
}

.stat-change {
  font-size: 12px;
  font-weight: 500;
}
.stat-change.up { color: #22c55e; }
.stat-change.down { color: #ef4444; }
.stat-change.neutral { color: #9ca3af; }

/* TABLA */
.table-container {
  background: #1f2937;
  border-radius: 25px;
  padding: 30px;
}
.table-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}
.table-header h2 { color: white; font-size: 28px; }

table { width: 100%; border-collapse: collapse; }
thead { background: #111827; }
th { color: #d1d5db; padding: 18px; text-align: left; font-weight: 600; }
td { padding: 20px 18px; color: #f3f4f6; border-bottom: 1px solid rgba(255,255,255,.05); }

.estado {
  padding: 8px 14px;
  border-radius: 30px;
  font-size: 14px;
  font-weight: 600;
}
.perdida { background: rgba(239,68,68,.15); color: #ef4444; }
.encontrada { background: rgba(34,197,94,.15); color: #22c55e; }

/* RESPONSIVE */
@media (max-width: 1200px) {
  .stats { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 700px) {
  .stats { grid-template-columns: 1fr; }
  .topbar { flex-direction: column; align-items: flex-start; gap: 20px; }
  table { display: block; overflow-x: auto; }
}
</style>