<script setup>
import { watch, ref } from 'vue'
import axios from 'axios'

const props = defineProps({
  alumno: Object
})
console.log(props.alumno);
const competencias = ref([])
const cargando = ref(false)

watch(
  () => props.alumno,
  async (nuevo) => {
    if (!nuevo || !nuevo.estancia_actual?.id) {
      competencias.value = []
      return
    }

    cargando.value = true
    try {
      const token = localStorage.getItem('token')
      const res = await axios.get(
        `http://127.0.0.1:8000/api/estancias/${nuevo.estancia_actual.id}/competencias`,
        { headers: { Authorization: `Bearer ${token}` } }
      )

      competencias.value = res.data.competencias
    } catch (e) {
      console.error('Error cargando competencias', e)
      competencias.value = []
    } finally {
      cargando.value = false
    }
  },
  { immediate: true }
)
</script>
<template>
  <div v-if="alumno" class="card shadow-sm col-md-9 mt-3">
    <div class="card-body">
      <h4>{{ alumno.Nombre }} {{ alumno.Apellidos }}</h4>
      <p class="text-muted">{{ alumno.Email }}</p>
      <p><strong>Grado:</strong> {{ alumno.Grado }}</p>

      <hr />

      <h5>Competencias de la estancia</h5>

      <div v-if="cargando" class="spinner-border text-indigo"></div>

      <ul v-else-if="competencias.length" class="list-group mb-3">
        <li v-for="c in competencias" :key="c.id"
          class="list-group-item d-flex justify-content-between align-items-center">
          {{ c.descripcion }}
          <button class="btn btn-sm btn-danger" @click="eliminarCompetencia(c.id)">Eliminar</button>
        </li>
      </ul>

      <p v-else class="text-muted mb-3">
        No hay competencias asignadas
      </p>

      <!-- Añadir competencia -->
      <div class="input-group mb-3">
        <select v-model="competenciaSeleccionada" class="form-select">
          <option value="">Selecciona una competencia</option>
          <option v-for="c in competenciasDisponibles" :key="c.id" :value="c.id">
            {{ c.descripcion }}
          </option>
        </select>
        <button class="btn btn-indigo text-white" @click="agregarCompetencia" :disabled="!competenciaSeleccionada">
          Añadir
        </button>
      </div>

    </div>
  </div>

  <p v-else class="text-muted text-center mt-4">
    Selecciona un alumno
  </p>
</template>
