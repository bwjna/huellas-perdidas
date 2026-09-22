// Lista de países para el selector de código telefónico.
// Se puede ampliar agregando más objetos con el mismo formato.

export const PAISES_TELEFONO = [
    { codigo: '54',  bandera: '🇦🇷', nombre: 'Argentina' },
    { codigo: '598', bandera: '🇺🇾', nombre: 'Uruguay' },
    { codigo: '56',  bandera: '🇨🇱', nombre: 'Chile' },
    { codigo: '595', bandera: '🇵🇾', nombre: 'Paraguay' },
    { codigo: '55',  bandera: '🇧🇷', nombre: 'Brasil' },
    { codigo: '591', bandera: '🇧🇴', nombre: 'Bolivia' },
    { codigo: '51',  bandera: '🇵🇪', nombre: 'Perú' },
    { codigo: '57',  bandera: '🇨🇴', nombre: 'Colombia' },
    { codigo: '58',  bandera: '🇻🇪', nombre: 'Venezuela' },
    { codigo: '593', bandera: '🇪🇨', nombre: 'Ecuador' },
    { codigo: '52',  bandera: '🇲🇽', nombre: 'México' },
    { codigo: '34',  bandera: '🇪🇸', nombre: 'España' },
    { codigo: '1',   bandera: '🇺🇸', nombre: 'Estados Unidos' },
]

export const PAIS_POR_DEFECTO = '54' // Argentina

// Separa un teléfono guardado como "+542344123456" en { codigo: '54', numero: '2344123456' }
// Si no tiene el formato esperado, asume Argentina y devuelve el número tal cual.
export function separarTelefono(telefonoCompleto) {
    if (!telefonoCompleto) {
        return { codigo: PAIS_POR_DEFECTO, numero: '' }
    }

    const soloDigitos = telefonoCompleto.replace(/\D/g, '')

    // Probamos los códigos más largos primero (para no confundir 598 con 59, etc.)
    const ordenados = [...PAISES_TELEFONO].sort((a, b) => b.codigo.length - a.codigo.length)

    for (const pais of ordenados) {
        if (telefonoCompleto.startsWith('+' + pais.codigo) || soloDigitos.startsWith(pais.codigo)) {
            return {
                codigo: pais.codigo,
                numero: soloDigitos.slice(pais.codigo.length),
            }
        }
    }

    return { codigo: PAIS_POR_DEFECTO, numero: soloDigitos }
}

// Arma el string completo a guardar en la base: "+542344123456"
export function armarTelefono(codigo, numero) {
    const soloDigitosNumero = (numero || '').replace(/\D/g, '')
    if (!soloDigitosNumero) return ''
    return `+${codigo}${soloDigitosNumero}`
}