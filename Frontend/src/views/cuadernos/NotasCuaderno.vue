<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useUserStore } from '@/stores/userStore'

const userStore = useUserStore()
const tutorId = userStore.user?.id

const alumnos = ref([])
const mensaje = ref('')

async function fetchNotas() {
  try {
    const res = await axios.get(
      `http://localhost:8000/api/tutor/${tutorId}/notas-cuaderno`
    )

    // Inicializar nota_cuaderno si es null
    alumnos.value = res.data.map(alumno => ({
      ...alumno,
      nota_cuaderno: alumno.nota_cuaderno ?? { Nota: null }
    }))

    console.log(alumnos.value);
  } catch (err) {
    console.error(err)
    mensaje.value = 'Error cargando notas'
  }
}


async function guardarNota(alumno) {
  try {
    await axios.post('http://localhost:8000/api/nota-cuaderno', {
      ID_Alumno: alumno.ID_Usuario,
      Nota: alumno.nota_cuaderno.Nota,
    })
  } catch (err) {
    console.error(err)
    alert('Error al guardar la nota')
  }
}

onMounted(fetchNotas)
</script>

<template>
  <div class="mt-5">
    <h3>Notas de Cuaderno</h3>

    <div v-if="mensaje" class="text-danger mb-3">
      {{ mensaje }}
    </div>

    <div v-if="alumnos.length" class="card shadow-sm">
      <div class="card-body">
        <table class="table table-striped align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Alumno</th>
              <th class="text-center" style="width: 140px">Nota</th>
              <th class="text-center" style="width: 120px">Acción</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="alumno in alumnos" :key="alumno.id">
              <td>
                {{ alumno.usuario?.nombre ?? '—' }}
              </td>

              <td class="text-center">
                <input
                  type="number"
                  min="0"
                  max="10"
                  step="0.25"
                  class="form-control text-center"
                  v-model="alumno.nota_cuaderno.Nota"
                />
              </td>

              <td class="text-center">
                <button
                  class="btn btn-outline-primary btn-sm"
                  @click="guardarNota(alumno)">
                  Guardar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div v-else class="text-muted">
      No hay alumnos asignados.
    </div>
  </div>
</template>
