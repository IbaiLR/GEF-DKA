<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import Buscador from '@/components/Buscador.vue'
import { useUserStore } from '@/stores/userStore'

const prop = defineProps({
  endpoint: String
})

const userStore = useUserStore()
const tutorId = userStore.user.id

const alumnos = ref([])
const q = ref('')
const cargando = ref(false)
const alumnoSeleccionado = ref(null)
const emit = defineEmits(['seleccionarAlumno'])

async function cargarAlumnos() {
  cargando.value = true
  try {
    const token = localStorage.getItem('token')
    const res = await axios.get(prop.endpoint, {
      headers: { Authorization: `Bearer ${token}` }
    })
    alumnos.value = res.data
  } catch (e) {
    console.error('Error cargando alumnos', e)
    alumnos.value = []
  } finally {
    cargando.value = false
  }
}

function seleccionarAlumno(a) {
  alumnoSeleccionado.value = a
  emit('seleccionarAlumno', a)
}


function onSearch(texto) {
  q.value = (texto || '').trim().toLowerCase()
}


onMounted(cargarAlumnos)
</script>

<template>
    <div class="col-md-3 mt-3">
      <Buscador tipo="Buscar Alumno" @search="onSearch" />

      <ul v-if="alumnos" class="list-group mt-3 shadow-sm">
        <button
          class="list-group-item list-group-item-action bg-indigo text-white fw-semibold"
          @click="alumnoSeleccionado = null"
        >
          Alumnos
        </button>

        <li
          v-for="a in alumnos"
          :key="a.ID_Usuario"
          class="list-group-item cursor-pointer"
          :class="{ ' bg-light text-dark': alumnoSeleccionado?.ID_Usuario === a.ID_Usuario }"
          @click="seleccionarAlumno(a.usuario)"
        >
          {{ a.usuario?.nombre }} {{ a.usuario?.apellidos }}
        </li>
      </ul>

      <div v-if="cargando" class="text-center mt-3">
        <div class="spinner-border text-indigo"></div>
      </div>

      <div v-if="!cargando && !alumnos" class="text-muted text-center mt-3">
        No se encontraron alumnos
      </div>
    </div>
</template>
