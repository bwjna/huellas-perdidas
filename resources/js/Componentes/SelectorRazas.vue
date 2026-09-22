<template>
  <div class="selector-raza" ref="raizEl">
    <div class="selector-raza-input">
      <span
        v-if="mostrarSugerencia"
        class="sugerencia-ghost"
        :style="{ transform: `translate(${anchoTexto}px, -50%)` }"
      >
        {{ sufijo }}
      </span>
      <input
        ref="inputEl"
        type="text"
        :value="texto"
        :placeholder="placeholder"
        :disabled="disabled"
        autocomplete="off"
        spellcheck="false"
        @input="onInput"
        @focus="abrir"
        @keydown="onKeydown"
      />
      <button
        v-if="texto && !disabled"
        type="button"
        class="boton-limpiar"
        aria-label="Limpiar raza"
        @mousedown.stop.prevent="limpiar"
      >
        <i class="bi bi-x-lg"></i>
      </button>
    </div>

    <div
      v-if="abierto && !disabled && (buscando || cantidadRazas > 0)"
      class="burbuja"
      role="listbox"
      aria-label="Razas"
    >
      <template v-for="fila in filas" :key="fila.clave">
        <div v-if="fila.tipo === 'seccion'" class="burbuja-seccion">
          {{ fila.etiqueta }}
        </div>
        <div v-else-if="fila.tipo === 'letra'" class="burbuja-letra">
          {{ fila.letra }}
        </div>
        <div
          v-else
          class="raza-fila"
          :class="{
            activa: indiceActivo === fila.indice,
            elegida: fila.raza === modelValue,
          }"
          role="option"
          :aria-selected="fila.raza === modelValue"
          @mousedown.prevent.stop="seleccionar(fila.raza)"
        >
          <span class="raza-fila-texto">{{ fila.raza }}</span>
          <i v-if="fila.raza === modelValue" class="bi bi-check-lg raza-fila-check"></i>
        </div>
      </template>

      <div v-if="sinResultados" class="burbuja-vacia">
        Sin resultados para "<strong>{{ texto }}</strong>"
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, nextTick, onMounted, onBeforeUnmount } from 'vue'
import { razasPerroFCI, razasGato } from '@/Razas.js'

const props = defineProps({
  modelValue: { type: String, default: '' },
  // 'perro' | 'gato' | 'otro' | '' (vacío = mostrar perros y gatos)
  especie: { type: String, default: '' },
  placeholder: { type: String, default: 'Buscar raza...' },
  disabled: { type: Boolean, default: false },
})

const emit = defineEmits(['update:modelValue'])

const texto = ref(props.modelValue ?? '')
const abierto = ref(false)
const indiceActivo = ref(-1)
const anchoTexto = ref(0)

const inputEl = ref(null)
const raizEl = ref(null)

// Comparador que ignora mayúsculas y acentos, ideal para listas en español
const collator = new Intl.Collator('es', { sensitivity: 'base' })

const normalizar = (s) =>
  String(s || '')
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')

const ordenar = (lista) => [...new Set(lista)].slice().sort((a, b) => collator.compare(a, b))

const perros = computed(() => ordenar(razasPerroFCI))
const gatos = computed(() => ordenar(razasGato))

const listaCompleta = computed(() => {
  if (props.especie === 'otro') return []

  const secciones = []
  if (props.especie === 'perro' || !props.especie) {
    secciones.push({ etiqueta: 'Perros', especie: 'perro', razas: perros.value })
  }
  if (props.especie === 'gato' || !props.especie) {
    secciones.push({ etiqueta: 'Gatos', especie: 'gato', razas: gatos.value })
  }
  return secciones
})

const buscando = computed(() => texto.value.trim().length > 0)

const coincidencias = computed(() => {
  if (!buscando.value) return []

  const query = normalizar(texto.value)
  const empiezan = []
  const contienen = []

  for (const seccion of listaCompleta.value) {
    for (const raza of seccion.razas) {
      const razaNormalizada = normalizar(raza)
      if (!razaNormalizada.includes(query)) continue

      const item = { raza, especie: seccion.especie }
      if (razaNormalizada.startsWith(query)) {
        empiezan.push(item)
      } else {
        contienen.push(item)
      }
    }
  }

  const comparar = (a, b) => collator.compare(a.raza, b.raza)
  empiezan.sort(comparar)
  contienen.sort(comparar)

  return [...empiezan, ...contienen]
})

// Sugerencia para el ghost: solo cuando lo tipeado es el comienzo de una raza,
// así el autocompletado acompaña sin "inventar" de más.
const sugerencia = computed(() => {
  if (!buscando.value) return ''
  const primera = coincidencias.value[0]?.raza
  if (!primera) return ''
  if (!normalizar(primera).startsWith(normalizar(texto.value))) return ''
  return primera
})

const sufijo = computed(() => {
  const sugerida = sugerencia.value
  if (!sugerida) return ''

  const textoNormal = normalizar(texto.value)
  const sugerenciaNormal = normalizar(sugerida)
  const limite = Math.min(textoNormal.length, sugerenciaNormal.length)

  let coincidentes = 0
  while (coincidentes < limite && textoNormal[coincidentes] === sugerenciaNormal[coincidentes]) {
    coincidentes++
  }

  return sugerida.slice(coincidentes)
})

const mostrarSugerencia = computed(
  () => abierto.value && buscando.value && !disabled.value && sufijo.value.length > 0
)

const filas = computed(() => {
  const resultado = []
  let indice = 0

  if (buscando.value) {
    for (const coincidencia of coincidencias.value) {
      resultado.push({
        tipo: 'raza',
        clave: `raza-busqueda-${coincidencia.especie}-${coincidencia.raza}`,
        raza: coincidencia.raza,
        indice,
      })
      indice++
    }
    return resultado
  }

  for (const seccion of listaCompleta.value) {
    resultado.push({
      tipo: 'seccion',
      clave: `seccion-${seccion.especie}`,
      etiqueta: seccion.etiqueta,
    })

    let letraActual = ''
    for (const raza of seccion.razas) {
      const letra = (normalizar(raza).charAt(0) || '?').toUpperCase()
      if (letra !== letraActual) {
        letraActual = letra
        resultado.push({
          tipo: 'letra',
          clave: `letra-${seccion.especie}-${letra}`,
          letra,
        })
      }
      resultado.push({
        tipo: 'raza',
        clave: `raza-${seccion.especie}-${raza}`,
        raza,
        indice,
      })
      indice++
    }
  }

  return resultado
})

const cantidadRazas = computed(() => filas.value.filter((f) => f.tipo === 'raza').length)
const sinResultados = computed(() => buscando.value && coincidencias.value.length === 0)

// ---------------------------------------------------------------------------
// Interacción
// ---------------------------------------------------------------------------

function abrir() {
  if (disabled.value) return
  abierto.value = true
  indiceActivo.value = -1
}

function cerrar() {
  abierto.value = false
  indiceActivo.value = -1
  // Si el usuario escribió y salió sin elegir, volvemos al valor válido
  if (texto.value !== (props.modelValue ?? '')) {
    texto.value = props.modelValue ?? ''
  }
}

function onInput(e) {
  texto.value = e.target.value
  indiceActivo.value = -1
  abierto.value = true
}

function seleccionar(raza) {
  texto.value = raza
  indiceActivo.value = -1
  abierto.value = false
  emit('update:modelValue', raza)
}

function completarConSugerencia() {
  const sugerida = sugerencia.value
  if (sugerida) seleccionar(sugerida)
}

function limpiar() {
  texto.value = ''
  indiceActivo.value = -1
  emit('update:modelValue', '')
  abierto.value = true
  inputEl.value?.focus()
}

function desplazarActivo() {
  nextTick(() => {
    const activa = raizEl.value?.querySelector('.raza-fila.activa')
    activa?.scrollIntoView({ block: 'nearest' })
  })
}

function onKeydown(e) {
  if (disabled.value) return

  switch (e.key) {
    case 'ArrowDown':
      e.preventDefault()
      abrir()
      if (cantidadRazas.value === 0) return
      indiceActivo.value =
        indiceActivo.value >= cantidadRazas.value - 1 ? 0 : indiceActivo.value + 1
      desplazarActivo()
      break

    case 'ArrowUp':
      e.preventDefault()
      abrir()
      if (cantidadRazas.value === 0) return
      indiceActivo.value =
        indiceActivo.value <= 0 ? cantidadRazas.value - 1 : indiceActivo.value - 1
      desplazarActivo()
      break

    case 'ArrowRight':
      if (mostrarSugerencia.value && e.target.selectionStart === texto.value.length) {
        e.preventDefault()
        completarConSugerencia()
      }
      break

    case 'Tab':
      if (mostrarSugerencia.value) {
        e.preventDefault()
        completarConSugerencia()
      } else if (abierto.value) {
        cerrar()
      }
      break

    case 'Enter':
      e.preventDefault()
      if (mostrarSugerencia.value && indiceActivo.value === -1) {
        completarConSugerencia()
      } else if (indiceActivo.value >= 0) {
        const seleccion = filas.value.find(
          (f) => f.tipo === 'raza' && f.indice === indiceActivo.value
        )
        if (seleccion) seleccionar(seleccion.raza)
      } else if (abierto.value) {
        cerrar()
      }
      break

    case 'Escape':
      if (abierto.value) {
        e.preventDefault()
        cerrar()
      }
      break
  }
}

function onClickFuera(e) {
  if (raizEl.value && !raizEl.value.contains(e.target)) cerrar()
}

// ---------------------------------------------------------------------------
// Medición del ghost: ancho exacto de lo tipeado para alinear la sugerencia
// ---------------------------------------------------------------------------

let lienzo = null

function medirTexto() {
  const input = inputEl.value
  if (!input) return

  if (!lienzo) lienzo = document.createElement('canvas').getContext('2d')
  const estilos = getComputedStyle(input)
  lienzo.font = `${estilos.fontWeight} ${estilos.fontSize} ${estilos.fontFamily}`
  anchoTexto.value = lienzo.measureText(texto.value).width
}

// ---------------------------------------------------------------------------
// Ciclo de vida
// ---------------------------------------------------------------------------

onMounted(() => {
  medirTexto()
  document.addEventListener('mousedown', onClickFuera)
  window.addEventListener('resize', medirTexto)
})

onBeforeUnmount(() => {
  document.removeEventListener('mousedown', onClickFuera)
  window.removeEventListener('resize', medirTexto)
})

// Mantiene el texto interno en sincronía con lo que decida la página padre
watch(
  () => props.modelValue,
  (nuevoValor) => {
    const valorLimpio = nuevoValor ?? ''
    if (texto.value !== valorLimpio) {
      texto.value = valorLimpio
      indiceActivo.value = -1
      nextTick(medirTexto)
    }
  }
)

watch(texto, () => nextTick(medirTexto))
</script>

<style scoped>
.selector-raza {
  position: relative;
}

.selector-raza-input {
  position: relative;
  display: flex;
  align-items: center;
}

.selector-raza-input input {
  width: 100%;
  padding: 11px 40px 11px 14px;
  border-radius: 10px;
  border: 1.5px solid #ddd0c4;
  font-size: 14px;
  font-family: inherit;
  line-height: 1.4;
  outline: none;
  color: #2b2b2b;
  background: #fff;
  transition: border-color 0.15s;
}

.selector-raza-input input:focus {
  border-color: #d85a30;
}

.selector-raza-input input:disabled {
  background: #f4eee6;
  cursor: not-allowed;
}

.sugerencia-ghost {
  position: absolute;
  left: 14px;
  top: 50%;
  white-space: pre;
  color: #b6ada4;
  pointer-events: none;
  font-size: 14px;
  line-height: 1.4;
}

.burbuja {
  position: absolute;
  top: calc(100% + 8px);
  left: 0;
  right: 0;
  z-index: 999;
  max-height: 320px;
  overflow-y: auto;
  background: #fff;
  border: 1.5px solid #e7ddd2;
  border-radius: 16px;
  box-shadow: 0 12px 32px rgba(70, 45, 25, 0.18);
  padding: 6px;
}

.burbuja::before {
  content: '';
  position: absolute;
  top: -7px;
  left: 26px;
  width: 12px;
  height: 12px;
  background: #fff;
  border-left: 1.5px solid #e7ddd2;
  border-top: 1.5px solid #e7ddd2;
  border-radius: 2px;
  transform: rotate(45deg);
}

.burbuja-seccion {
  padding: 10px 12px 4px;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  color: #d85a30;
}

.burbuja-letra {
  padding: 8px 12px 4px;
  margin-top: 2px;
  border-top: 1px dashed #f0e7dc;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: #c9b9a6;
}

.raza-fila {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
  padding: 8px 12px;
  border-radius: 10px;
  font-size: 14px;
  color: #2b2b2b;
  cursor: pointer;
}

.raza-fila:hover {
  background: #fdf3ea;
}

.raza-fila.activa {
  background: #fbe8dc;
  color: #b34727;
}

.raza-fila.elegida {
  color: #d85a30;
  font-weight: 600;
}

.raza-fila-check {
  color: #d85a30;
  font-size: 15px;
  flex-shrink: 0;
}

.burbuja-vacia {
  padding: 14px 12px;
  font-size: 13px;
  color: #8f8578;
  text-align: center;
}

.burbuja-vacia strong {
  color: #3a2519;
}

.boton-limpiar {
  position: absolute;
  right: 6px;
  top: 50%;
  transform: translateY(-50%);
  width: 26px;
  height: 26px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: none;
  border-radius: 50%;
  background: transparent;
  color: #9ca3af;
  cursor: pointer;
}

.boton-limpiar:hover {
  background: #f0e7dc;
  color: #3a2519;
}
</style>