// Zona habilitada para reportar avistamientos y ver el mapa: Saladillo (CP 7260) + Los Troncos.
// Cuando la app deje de ser local y quieras ampliar la cobertura, alcanza con editar estos valores.

export const CENTRO_SALADILLO = [-35.6339, -59.7794]

// Rectángulo que cubre la ciudad de Saladillo y el barrio Los Troncos (al norte)
export const LIMITES_ZONA = [
    [-35.685, -59.835], // esquina suroeste
    [-35.575, -59.730], // esquina noreste
]

// Un poco más amplio que LIMITES_ZONA, para poder alejar el zoom y ver
// el cuadro "de afuera" sin que la app se sienta encerrada de golpe.
export const LIMITES_PANEO = [
    [-35.78, -59.95],
    [-35.48, -59.62],
]

export function dentroDeZona(lat, lng) {
    const [[sur, oeste], [norte, este]] = LIMITES_ZONA
    return lat >= sur && lat <= norte && lng >= oeste && lng <= este
}