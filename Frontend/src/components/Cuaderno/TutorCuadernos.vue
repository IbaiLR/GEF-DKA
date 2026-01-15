<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useUserStore } from '@/stores/userStore'

const userStore = useUserStore()
const tutorId = userStore.user?.id

const entregas = ref([])
const mensaje = ref('')

async function fetchEntregas() {
  if (!tutorId) return

  try {
    // Obtener los grados que tutorea
    const gradosResponse = await axios.get(`http://localhost:8000/api/tutor/${tutorId}/grados`)
    const grados = gradosResponse.data

    entregas.value = []

    // Obtener entregas de cada grado
    for (const grado of grados) {
      const resResponse = await axios.get(`http://localhost:8000/api/grado/${grado.id}/entregas`)
      const res = resResponse.data

      // Mapear alumno_entrega a alumnoEntrega
      res.forEach(function(e) {
        e.alumnoEntrega = e.alumno_entrega || []
        e.alumnoEntrega.forEach(a => {
          if (!a.nota) a.nota = { Nota: 0 }
        })
      })

      entregas.value.push(...res)
    }
  } catch (err) {
    console.error(err)
    mensaje.value = 'Error cargando entregas'
  }
}

async function guardarNota(alumnoEntrega) {
  try {
    await axios.post('http://localhost:8000/api/nota-cuaderno', {
      ID_Cuaderno: alumnoEntrega.ID_Entrega,
      Nota: alumnoEntrega.nota?.Nota || 0
    })
    alert('Nota guardada correctamente')
  } catch (err) {
    console.error(err)
    alert('Error al guardar la nota')
  }
}

onMounted(fetchEntregas)
</script>

<template>
  <div>
    <h3>Cuadernos de Alumnos</h3>
    <div v-if="mensaje" class="text-danger mb-3">{{ mensaje }}</div>

    <div v-if="entregas.length">
      <div v-for="entrega in entregas" :key="entrega.id" class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <strong>Entrega ID: {{ entrega.id }}</strong>
            <span class="text-muted ms-3">Fecha límite: {{ entrega.Fecha_Limite }}</span>
          </div>
          <div>
            <span class="badge bg-success" v-if="entrega.alumnoEntrega.length">Entregas realizadas: {{ entrega.alumnoEntrega.length }}</span>
            <span class="badge bg-danger" v-else>No hay entregas</span>
          </div>
        </div>

        <div class="card-body p-0">
          <table class="table table-striped mb-0">
            <thead class="table-light">
              <tr>
                <th>Alumno</th>
                <th>PDF</th>
                <th>Nota</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="alumnoEntrega in entrega.alumnoEntrega" :key="alumnoEntrega.id">
                <td>{{ alumnoEntrega.alumno?.usuario?.nombre ?? '—' }}</td>
                <td>
                  <a v-if="alumnoEntrega.URL_Cuaderno" :href="`http://localhost:8000/api/alumno/entregas/descargar/${alumnoEntrega.id}`" target="_blank">
                    Descargar PDF
                  </a>
                  <span v-else class="text-danger">No entregado</span>
                </td>
                <td class="d-flex align-items-center gap-2">
                  <input v-model.number="alumnoEntrega.nota.Nota" type="number" min="0" max="10" class="form-control form-control-sm w-25" />
                  <button @click="guardarNota(alumnoEntrega)" class="btn btn-success btn-sm">Guardar</button>
                </td>
              </tr>

              <!-- Si no hay alumnos entregados -->
              <tr v-if="entrega.alumnoEntrega.length === 0">
                <td colspan="3" class="text-center text-muted">Ningún alumno ha entregado este cuaderno</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div v-else class="text-center text-muted mt-3">
      No hay entregas asignadas para tus grados.
    </div>
  </div>
</template>

