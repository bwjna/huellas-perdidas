// Funciones de formato compartidas entre varias páginas.

export function formatFecha(fecha) {
    if (!fecha) return ''
    return new Date(fecha).toLocaleDateString('es-AR', { day: '2-digit', month: 'long', year: 'numeric' })
}