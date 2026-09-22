<template>
    <div class="sidebar">
        <div class="logo">
            <h2>🐾 Huellas</h2>
            <span>Panel de Administración</span>
        </div>
        <ul class="menu">
            <li :class="{ active: activo === 'usuarios' }">
                <Link href="/panel">Usuarios</Link>
            </li>
            <li :class="{ active: activo === 'reportes' }">
                <Link href="/panel/reportes">
                    Reportes
                    <span v-if="cantidadReportes > 0" class="badge-reportes">{{ cantidadReportes }}</span>
                </Link>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    activo: {
        type: String,
        default: '',
    },
    cantidadReportes: {
        type: Number,
        default: 0,
    },
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

.sidebar {
    width: 280px;
    background: #0f172a;
    padding: 35px 25px;
    border-right: 1px solid rgba(255,255,255,.05);
    font-family: 'Poppins', sans-serif;
    flex-shrink: 0;
}

.logo { margin-bottom: 50px; }
.logo h2 { color: #ff7b00; font-size: 32px; font-weight: 700; }
.logo span { color: #9ca3af; font-size: 15px; }

.menu { list-style: none; padding: 0; margin: 0; }
.menu li { margin-bottom: 15px; }
.menu li :deep(a) {
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
    color: #d1d5db;
    padding: 16px 18px;
    border-radius: 14px;
    transition: .3s;
    font-weight: 500;
}
.menu li :deep(a):hover,
.menu li.active :deep(a) { background: #ff7b00; color: white; }

.badge-reportes {
    background: #ef4444;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    padding: 2px 9px;
    border-radius: 999px;
}
.menu li.active .badge-reportes,
.menu li :deep(a):hover .badge-reportes { background: rgba(255,255,255,.25); }

@media (max-width: 900px) {
    .sidebar { display: none; }
}
</style>