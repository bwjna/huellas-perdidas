<?php

// Límites geográficos de la zona habilitada (Saladillo + Los Troncos).
// Único lugar de la verdad en el backend — el frontend tiene su propia copia
// en resources/js/zonaMapa.js (necesaria porque el navegador no puede leer
// archivos PHP), así que si cambiás esto, actualizá ese archivo también.

return [
    'sur'   => -35.685,
    'norte' => -35.575,
    'oeste' => -59.835,
    'este'  => -59.730,
];