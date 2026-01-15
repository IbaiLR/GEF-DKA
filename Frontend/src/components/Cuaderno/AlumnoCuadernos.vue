<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useUserStore } from '@/stores/userStore'

const userStore = useUserStore()
const alumnoId = userStore.user?.id

const entregas = ref([])
const archivos = ref({})
const mensaje = ref('')

async function fetchEntregas() {
  const token = localStorage.getItem('token')
  try {
    const res = await axios.get(
      `http://localhost:8000/api/entregas/alumno/${alumnoId}`,
      {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    )
    entregas.value = res.data
  } catch (err) {
    console.error(err)
    mensaje.value = 'Error al cargar las entregas'
  }
}

function cambioArchivo(e, entregaId) {
  archivos.value[entregaId] = e.target.files[0]
}

async function subirPDF(entregaId) {
  const file = archivos.value[entregaId]
  if (!file) return alert('Selecciona un archivo')

  const formData = new FormData()
  formData.append('cuaderno', file)
  formData.append('ID_Entrega', entregaId)
  try {
    await axios.post(
      `http://localhost:8000/api/entregarCuaderno/alumno/${alumnoId}`,
      formData,
      {
        headers: {
          Authorization: `Bearer ${userStore.token}`,
          'Content-Type': 'multipart/form-data'
        }
      }
    )

    archivos.value[entregaId] = null
    fetchEntregas()
  } catch (err) {
    console.error(err)
  }
}

onMounted(fetchEntregas)
</script>



<template>
  <div>
    <h3>Mis Cuadernos</h3>

    <div v-if="mensaje" class="text-danger">
      {{ mensaje }}
    </div>

    <div
      v-for="entrega in entregas"
      :key="entrega.id"
      class="mb-3 p-2 border rounded"
    >
      <strong>Entrega ID: {{ entrega.id }}</strong>
      | Fecha límite: {{ entrega.Fecha_Limite }}

      <div class="mt-2">
        <template v-if="entrega.alumno_entrega?.length">
          <span class="text-success">Entregado</span>
          <a :href="`http://localhost:8000/api/alumno/entregas/descargar/${entrega.alumno_entrega[0].id}`" target="_blank">
  Descargar PDF
</a>

        </template>

        <span v-else class="text-danger">
          No entregado
        </span>
      </div>

      <div class="mt-2">
        <input
          type="file"
          accept="application/pdf"
          @change="e => cambioArchivo(e, entrega.id)"
        />
        <button
          class="btn btn-primary btn-sm"
          @click="subirPDF(entrega.id)"
          :disabled="entrega.alumnoEntrega?.length"
        >
          Subir PDF
        </button>
      </div>
    </div>

    <div v-if="entregas.length === 0" class="mt-3">
      No hay entregas asignadas para tu grado.
    </div>
  </div>
</template>


