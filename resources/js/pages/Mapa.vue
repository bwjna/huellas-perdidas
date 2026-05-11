<script setup>
import { onMounted } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import markerIcon from 'leaflet/dist/images/marker-icon.png'
import markerShadow from 'leaflet/dist/images/marker-shadow.png'

const props = defineProps({
  avistamientos: {
    type: Array,
    default: () => []
  }
})

delete L.Icon.Default.prototype._getIconUrl
L.Icon.Default.mergeOptions({
  iconUrl: markerIcon,
  shadowUrl: markerShadow,
})

onMounted(() => {
  const map = L.map('mapa').setView([-35.6333, -59.7833], 13)

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map)

  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(position => {
      const lat = position.coords.latitude
      const lng = position.coords.longitude
      map.setView([lat, lng], 15)
      L.marker([lat, lng])
        .addTo(map)
        .bindPopup('📍 Estás aquí')
        .openPopup()
    })
  }

  // Pins de avistamientos
  props.avistamientos.forEach(a => {
    L.marker([a.latitud, a.longitud])
      .addTo(map)
      .bindPopup(`
        <b>📍 Avistamiento</b><br>
        ${a.descripcion ?? 'Sin descripción'}<br>
        <small>${a.direccion ?? ''}</small>
      `)
  })
})
</script>

<template>
  <div id="mapa" style="width: 100%; height: calc(100vh - 80px);"></div>
</template>