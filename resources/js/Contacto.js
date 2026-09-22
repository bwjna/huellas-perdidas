// Arma el link de WhatsApp (o mailto, si el contacto no es un teléfono) para el
// botón "Contactar" de una publicación.
//
// - Si la publicación es "encontrado": usa el campo `contacto` que cargó quien
//   encontró a la mascota (puede ser teléfono o email, es texto libre).
// - Si es "perdido" (o "resuelto"): usa el teléfono del usuario dueño de la publicación.

function soloDigitos(texto) {
    return (texto || '').replace(/\D/g, '')
}

function pareceEmail(texto) {
    return /@/.test(texto || '')
}

// Ahora que el teléfono se carga con selector de país (código completo, ej "+542344123456"),
// ya no hace falta "adivinar" el prefijo — solo mantenemos la particularidad de Argentina,
// que necesita un "9" extra después del 54 para que los links de WhatsApp funcionen bien.
function formatearTelefonoWA(telefono) {
    const digitos = soloDigitos(telefono)
    if (!digitos) return null

    if (digitos.startsWith('54') && !digitos.startsWith('549')) {
        return '549' + digitos.slice(2)
    }

    return digitos
}

export function linkContacto(publicacion, mensaje = null) {
    const textoMensaje = mensaje ?? `Hola! Vi tu publicación "${publicacion.titulo}" en Huellas Perdidas.`

    // Caso "encontré una mascota": el contacto es texto libre cargado a mano
    if (publicacion.estado === 'encontrado' && publicacion.contacto) {
        if (pareceEmail(publicacion.contacto)) {
            return {
                tipo: 'email',
                href: `mailto:${publicacion.contacto}?subject=${encodeURIComponent('Sobre: ' + publicacion.titulo)}&body=${encodeURIComponent(textoMensaje)}`,
            }
        }

        const numero = formatearTelefonoWA(publicacion.contacto)
        if (numero) {
            return {
                tipo: 'whatsapp',
                href: `https://wa.me/${numero}?text=${encodeURIComponent(textoMensaje)}`,
            }
        }

        return null
    }

    // Caso normal: el contacto es el teléfono del usuario dueño de la publicación
    const numero = formatearTelefonoWA(publicacion.usuario?.telefono)
    if (numero) {
        return {
            tipo: 'whatsapp',
            href: `https://wa.me/${numero}?text=${encodeURIComponent(textoMensaje)}`,
        }
    }

    return null
}