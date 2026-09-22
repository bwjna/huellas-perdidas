<template>
  <form class="filtros" @submit.prevent="apply">
    <div class="row">
      <div class="field">
        <label>Especie</label>
        <select v-model="local.especie">
          <option value="">Todos</option>
          <option value="perro">Perro</option>
          <option value="gato">Gato</option>
        </select>
      </div>

      <div class="field">
        <label>Raza</label>
        <input
          list="razas"
          v-model="local.raza"
          placeholder="Buscar raza"
          @input="onRazaInput"
        />
        <datalist id="razas">
          <option v-for="r in razasSugeridas" :key="r" :value="r" />
        </datalist>
      </div>

      <div class="field">
        <label>Color</label>
        <select v-model="local.color">
          <option value="">Todos</option>
          <option v-for="c in colores" :key="c" :value="c">{{ c }}</option>
        </select>
      </div>

      <div class="field">
        <label>Sexo</label>
        <select v-model="local.sexo">
          <option value="">Todos</option>
          <option value="macho">Macho</option>
          <option value="hembra">Hembra</option>
        </select>
      </div>

      <div class="field">
        <label>Tamaño</label>
        <select v-model="local.tamano">
          <option value="">Todos</option>
          <option value="pequeño">Pequeño</option>
          <option value="mediano">Mediano</option>
          <option value="grande">Grande</option>
        </select>
      </div>

      <div class="actions">
        <button type="button" class="btn-secondary" @click="reset">Limpiar</button>
        <button type="submit" class="btn-primary">Aplicar</button>
      </div>
    </div>
  </form>
</template>

<script setup>
import { reactive, computed, watch } from 'vue'
import { razasPerroFCI, razasGato, coloresMascota } from '@/Razas.js'

const props = defineProps({
  initial: { type: Object, default: () => ({ especie: '', raza: '', color: '', sexo: '', tamano: '' }) }
})
const emit = defineEmits(['apply'])

const local = reactive({
  especie: props.initial.especie ?? '',
  raza: props.initial.raza ?? '',
  color: props.initial.color ?? '',
  sexo: props.initial.sexo ?? '',
  tamano: props.initial.tamano ?? '',
})

watch(() => props.initial, (v) => {
  local.especie = v?.especie ?? ''
  local.raza = v?.raza ?? ''
  local.color = v?.color ?? ''
  local.sexo = v?.sexo ?? ''
  local.tamano = v?.tamano ?? ''
})

const colores = coloresMascota

const razasSugeridas = computed(() => {
  const lista = local.especie === 'perro' ? razasPerroFCI
              : local.especie === 'gato' ? razasGato
              : [...razasPerroFCI, ...razasGato]
  if (!local.raza) return lista.slice(0, 40)
  return lista.filter(r => r.toLowerCase().includes(local.raza.toLowerCase())).slice(0, 40)
})

function onRazaInput(e) {
  // mantiene el comportamiento reactivo del datalist
  // no emite nada hasta que el usuario haga click en Aplicar
}

function apply() {
  emit('apply', {
    especie: local.especie,
    raza: local.raza,
    color: local.color,
    sexo: local.sexo,
    tamano: local.tamano,
  })
}

function reset() {
  local.especie = ''
  local.raza = ''
  local.color = ''
  local.sexo = ''
  local.tamano = ''
  emit('apply', { especie: '', raza: '', color: '', sexo: '', tamano: '' })
}
</script>

<style scoped>
.filtros { padding: 8px 0; }
.row { display:flex; flex-wrap:wrap; gap:12px; align-items:end; }
.field { display:flex; flex-direction:column; min-width:140px; }
.actions { display:flex; gap:8px; margin-left:auto; }
.btn-primary { background:#D85A30; color:#fff; padding:8px 12px; border-radius:6px; border:none; cursor:pointer; }
.btn-secondary { background:#fff; border:1px solid #ddd; padding:8px 12px; border-radius:6px; cursor:pointer; }
</style>
