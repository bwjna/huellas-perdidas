<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>
    <div class="container my-5">
    <h2 class="mb-4">Mascotas perdidas en la zona</h2>
    <div id="map" style="height: 500px; width: 100%; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);"></div>
</div>

<script>
    // Coordenadas de Saladillo (según tu link)
    var lat = -35.63822;
    var lng = -59.77944;

    // Inicializar el mapa
    var map = L.map('map').setView([lat, lng], 13);

    // Añadir la capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Ejemplo: Añadir un marcador de una mascota perdida
    var marcador = L.marker([lat, lng]).addTo(map);
</script>
</body>
</html>