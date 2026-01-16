<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useUserStore } from '@/stores/userStore'

const userStore = useUserStore()
const alumnoId = userStore.user?.id

const entregas = ref([])
const archivos = ref({})
const mensaje = ref('')

// Fetch de entregas del alumno
async function fetchEntregas() {
  const token = localStorage.getItem('token')
  try {
    const res = await axios.get(
      `http://localhost:8000/api/entregas/alumno/${alumnoId}`,
      { headers: { Authorization: `Bearer ${token}` } }
    )
    entregas.value = res.data
  } catch (err) {
    console.error(err)
    mensaje.value = 'Error al cargar las entregas'
  }
}

// Guardar archivo seleccionado en memoria
function cambioArchivo(e, entregaId) {
  archivos.value[entregaId] = e.target.files[0]
}

// Comprueba si la fecha límite ha pasado (solo día, ignorando hora)
function fechaVencida(fechaLimite) {
  const hoy = new Date()
  const hoySolo = new Date(hoy.getFullYear(), hoy.getMonth(), hoy.getDate()).getTime()

  const [year, month, day] = fechaLimite.split('-')
  const limiteSolo = new Date(+year, +month - 1, +day).getTime()

  return hoySolo > limiteSolo
}

// Subir o reemplazar PDF
async function subirPDF(entregaId) {
  const file = archivos.value[entregaId]
  if (!file) return alert('Selecciona un archivo')

  const entrega = entregas.value.find(e => e.id === entregaId)
  if (fechaVencida(entrega.Fecha_Limite)) {
    return alert('La fecha límite ha pasado. No se puede subir el cuaderno.')
  }

  const formData = new FormData()
  formData.append('cuaderno', file)
  formData.append('ID_Entrega', entregaId)

  try {
    await axios.post(
      `http://localhost:8000/api/entregarCuaderno/alumno/${alumnoId}`,
      formData,
      { headers: { Authorization: `Bearer ${userStore.token}`, 'Content-Type': 'multipart/form-data' } }
    )
    archivos.value[entregaId] = {}
    fetchEntregas()
  } catch (err) {
    console.error(err)
    alert('Error al subir el PDF')
  }
}

onMounted(fetchEntregas)
</script>

<template>
  <div>
    <h3 class="mb-4">Mis Cuadernos</h3>

    <div v-if="mensaje" class="alert alert-danger">
      {{ mensaje }}
    </div>

    <div v-if="entregas.length === 0" class="alert alert-info">
      No hay entregas asignadas para tu grado.
    </div>

    <div class="row">
      <div v-for="entrega in entregas" :key="entrega.id" class="col-md-6 mb-3">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title">Entrega {{ entrega.id }}</h5>
            <p class="card-subtitle mb-2 text-muted">Fecha límite: {{ entrega.Fecha_Limite }}</p>

            <!-- Badge estado -->
            <div class="mb-3">
              <span
                class="badge"
                :class="entrega.alumno_entrega?.length
                          ? 'bg-success'
                          : fechaVencida(entrega.Fecha_Limite)
                          ? 'bg-secondary'
                          : 'bg-danger'"
              >
                <i class="bi"
                   :class="entrega.alumno_entrega?.length ? 'bi-check-circle' : 'bi-x-circle'"></i>
                <span v-if="entrega.alumno_entrega?.length"> Entregado</span>
                <span v-else-if="fechaVencida(entrega.Fecha_Limite)"> Fecha vencida</span>
                <span v-else> No entregado</span>
              </span>
            </div>

            <!-- Última entrega -->
            <div v-if="entrega.alumno_entrega?.length" class="mb-3">
              Última entrega: {{ new Date(entrega.alumno_entrega[entrega.alumno_entrega.length - 1].Fecha_Entrega).toLocaleDateString() }}
            </div>

            <!-- Input y botón solo si la fecha límite NO ha pasado -->
            <div v-if="!fechaVencida(entrega.Fecha_Limite)" class="d-flex align-items-center mb-2">
              <input
                type="file"
                accept="application/pdf"
                class="form-control form-control-sm me-2"
                @change="e => cambioArchivo(e, entrega.id)"
              />
              <button
                class="btn btn-primary btn-sm"
                @click="subirPDF(entrega.id)"
                v-if="archivos[entrega.id]"
              >
                Subir/Actualizar PDF
              </button>
            </div>

            <!-- Descargar última entrega -->
            <div v-if="entrega.alumno_entrega?.length">
              <a
  :href="`http://localhost:8000/api/alumno/entregas/descargar/${entrega.alumno_entrega[entrega.alumno_entrega.length - 1].id}`"
  target="_blank"
  class="btn btn-outline-indigo btn-sm"
>
  <i class="bi bi-download"></i> Descargar última entrega
</a>

            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.card {
  transition: transform 0.2s;
}
.card:hover {
  transform: translateY(-3px);
}
</style>
