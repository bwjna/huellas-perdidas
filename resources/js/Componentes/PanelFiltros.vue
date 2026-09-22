<template>
    <Teleport to="body">
        <div v-if="abierto" class="panel-overlay" @click.self="cerrar">
            <div class="panel">
                <div class="panel-header">
                    <span class="panel-title">Filtros</span>
                    <button class="panel-close" @click="cerrar">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <!-- Tipo -->
                <div class="filter-section">
                    <div class="filter-label">Tipo de mascota</div>
                    <div class="chips">
                        <button
                            v-for="op in tipoOpciones" :key="op.val"
                            class="chip" :class="{ active: tipoTemp === op.val }"
                            @click="tipoTemp = op.val; razaTemp = ''"
                        >{{ op.label }}</button>
                    </div>
                </div>

                <!-- Sexo -->
                <div class="filter-section">
                    <div class="filter-label">Sexo</div>
                    <div class="chips">
                        <button
                            v-for="op in sexoOpciones" :key="op.val"
                            class="chip" :class="{ active: sexoTemp === op.val }"
                            @click="sexoTemp = op.val"
                        >{{ op.label }}</button>
                    </div>
                </div>

                <!-- Tamaño -->
                <div class="filter-section">
                    <div class="filter-label">Tamaño</div>
                    <div class="pills">
                        <button
                            type="button"
                            v-for="s in [['pequeño','Pequeño'], ['mediano','Mediano'], ['grande','Grande']]"
                            :key="s[0]"
                            class="chip"
                            :class="{ active: tamanoTemp === s[0] }"
                            @click="tamanoTemp = s[0]"
                        >{{ s[1] }}</button>
                    </div>
                </div>

                <!-- Color -->
                <div class="filter-section">
                    <div class="filter-label">Color</div>
                    <select v-model="colorTemp" class="filter-select">
                        <option value="">Cualquier color</option>
                        <option v-for="c in coloresMascota" :key="c" :value="c">{{ c }}</option>
                    </select>
                </div>

                <!-- Raza -->
                <div class="filter-section">
                    <div class="filter-label">Raza</div>
                    <SelectorRazas
                        v-model="razaTemp"
                        :especie="tipoTemp"
                        placeholder="Escribí para buscar..."
                    />
                </div>

                <div class="panel-footer">
                    <button class="btn-limpiar-panel" @click="limpiarTemp">Limpiar</button>
                    <button class="btn-aplicar" @click="aplicar">Ver resultados</button>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { ref } from 'vue'
import { coloresMascota } from '@/Razas.js'
import SelectorRazas from '@/Componentes/SelectorRazas.vue'

defineProps({
    abierto: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits(['update:abierto', 'aplicar'])

const tipoOpciones = [{ val: '', label: 'Todos' }, { val: 'perro', label: ' Perro' }, { val: 'gato', label: ' Gato' }]
const sexoOpciones  = [{ val: '', label: 'Todos' }, { val: 'macho', label: 'Macho' }, { val: 'hembra', label: 'Hembra' }, { val: 'desconocido', label: 'Desconocido' }]

const tipoTemp        = ref('')
const sexoTemp         = ref('')
const tamanoTemp       = ref('')
const colorTemp        = ref('')
const razaTemp         = ref('')

const cerrar = () => emit('update:abierto', false)

const aplicar = () => {
    emit('aplicar', {
        tipo: tipoTemp.value,
        sexo: sexoTemp.value,
        tamano: tamanoTemp.value,
        color: colorTemp.value,
        raza: razaTemp.value,
    })
    emit('update:abierto', false)
}

const limpiarTemp = () => {
    tipoTemp.value = sexoTemp.value = tamanoTemp.value = colorTemp.value = razaTemp.value = ''
}

// La página padre necesita poder limpiar el estado temporal del panel cuando
// el usuario toca "Limpiar filtros" desde el empty-state (fuera del panel).
defineExpose({ limpiarTemp })
</script>

<style scoped>
.panel-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.4);
    z-index: 1000;
    display: flex;
    align-items: flex-end;
    justify-content: center;
}
.panel {
    background: #fff;
    border-radius: 24px 24px 0 0;
    border: 3px solid #111;
    border-bottom: none;
    padding: 24px 24px 32px;
    width: 100%;
    max-width: 520px;
    max-height: 85vh;
    overflow-y: auto;
}
.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.panel-title {
    font-size: 18px;
    font-weight: 700;
    color: #111;
}
.panel-close {
    background: #f4f1ea;
    border: 2px solid #111;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 14px;
}

.filter-section { margin-bottom: 18px; }
.filter-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #888;
    margin-bottom: 8px;
}
.chips { display: flex; gap: 6px; flex-wrap: wrap; }
.chip {
    padding: 6px 16px;
    border-radius: 999px;
    border: 2px solid #111;
    background: #fff;
    font-size: 13px;
    font-weight: 600;
    font-family: 'Space Grotesk', sans-serif;
    cursor: pointer;
    box-shadow: 3px 3px 0 #111;
    transition: .15s;
    color: #111;
}
.chip:hover { transform: translate(-1px, -1px); box-shadow: 4px 4px 0 #ff7b00; }
.chip.active { background: #ff7b00; color: #fff; border-color: #111; }

.filter-select {
    width: 100%;
    padding: 10px 14px;
    border-radius: 12px;
    border: 2px solid #111;
    background: #f4f1ea;
    font-size: 14px;
    font-family: 'Space Grotesk', sans-serif;
    outline: none;
    box-shadow: 3px 3px 0 #111;
    color: #111;
}

.panel-footer {
    display: flex;
    gap: 10px;
    margin-top: 24px;
}
.btn-limpiar-panel {
    flex: 1;
    padding: 12px;
    border-radius: 12px;
    border: 2px solid #111;
    background: #fff;
    font-size: 14px;
    font-weight: 700;
    font-family: 'Space Grotesk', sans-serif;
    cursor: pointer;
    box-shadow: 3px 3px 0 #111;
}
.btn-aplicar {
    flex: 2;
    padding: 12px;
    border-radius: 12px;
    border: 2px solid #111;
    background: #ff7b00;
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    font-family: 'Space Grotesk', sans-serif;
    cursor: pointer;
    box-shadow: 3px 3px 0 #111;
    transition: .15s;
}
.btn-aplicar:hover { transform: translate(-2px, -2px); box-shadow: 5px 5px 0 #111; }
</style>