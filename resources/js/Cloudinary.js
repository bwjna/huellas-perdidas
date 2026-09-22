export function optimizarImagen(url, { ancho, alto, recorte = 'limit', gravedad = 'auto' } = {}) {
    if (!url || !url.includes('/upload/')) return url

    const partes = []
    if (ancho) partes.push(`w_${ancho}`)
    if (alto) partes.push(`h_${alto}`)
    if (ancho || alto) partes.push(`c_${recorte}`)
    if ((ancho || alto) && recorte === 'fill') partes.push(`g_${gravedad}`)
    partes.push('q_auto', 'f_auto')

    return url.replace('/upload/', `/upload/${partes.join(',')}/`)
}