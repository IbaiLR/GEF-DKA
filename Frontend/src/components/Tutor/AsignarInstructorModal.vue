<script setup>
import { ref, watch } from 'vue'
import api from '@/services/api.js'

const props = defineProps({
  alumno: Object,
  show: Boolean
})
const emit = defineEmits(['close', 'asignado'])

const instructores = ref([])
const instructorId = ref(null)
const cargando = ref(false)
const error = ref('')

watch(
  () => props.show,
  async (val) => {
    if (!val) return
    
    const cif = props.alumno?.estancia_actual?.empresa?.CIF
    if (!cif) {
      error.value = 'El alumno debe tener una estancia asignada con empresa'
      return
    }

    cargando.value = true
    error.value = ''
    instructorId.value = null
    
    try {
      // CORRECCIÓN: usar /api/empresa/ (singular) no /api/empresas/
      const res = await api.get(`/api/empresa/${cif}/instructores`)
      instructores.value = res.data
      console.log('Instructores cargados:', res.data)
    } catch (e) {
      console.error('Error cargando instructores:', e)
      error.value = 'Error al cargar instructores de la empresa'
      instructores.value = []
    } finally {
      cargando.value = false
    }
  }
)

async function asignar() {
  if (!instructorId.value) {
    error.value = 'Selecciona un instructor'
    return
  }

  cargando.value = true
  error.value = ''
  
  try {
    const token = localStorage.getItem('token')
    await api.put(
      `/api/alumnos/${props.alumno.ID_Usuario}/asignar-instructor`,
      { ID_Instructor: instructorId.value },
      { headers: { Authorization: `Bearer ${token}` } }
    )

    emit('asignado')
    emit('close')
  } catch (e) {
    console.error('Error asignando instructor:', e)
    error.value = e.response?.data?.message || 'Error asignando instructor'
  } finally {
    cargando.value = false
  }
}
</script>

<template>
  <div v-if="show" class="modal fade show d-block" style="background: rgba(0,0,0,.5)" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-indigo text-white">
          <h5 class="modal-title">
            <i class="bi bi-person-plus"></i> Asignar instructor
          </h5>
          <button class="btn-close btn-close-white" @click="emit('close')" :disabled="cargando"></button>
        </div>

        <div class="modal-body">
          <div v-if="error" class="alert alert-danger alert-dismissible">
            {{ error }}
            <button type="button" class="btn-close" @click="error = ''"></button>
          </div>

          <p class="mb-3">
            <strong>Alumno:</strong> {{ alumno?.usuario?.nombre }} {{ alumno?.usuario?.apellidos }}
          </p>
          <p class="mb-3">
            <strong>Empresa:</strong> {{ alumno?.estancia_actual?.empresa?.nombre || 'N/A' }}
          </p>

          <div v-if="cargando" class="text-center py-3">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2">Cargando instructores...</p>
          </div>

          <div v-else-if="instructores.length">
            <label class="form-label">Selecciona un instructor:</label>
            <select class="form-select" v-model="instructorId">
              <option :value="null" disabled>-- Selecciona instructor --</option>
              <option
                v-for="i in instructores"
                :key="i.ID_Usuario"
                :value="i.ID_Usuario"
              >
                {{ i.user.nombre }} {{ i.user.apellidos }}
              </option>
            </select>
          </div>

          <div v-else-if="!cargando" class="alert alert-warning">
            No hay instructores disponibles para esta empresa
          </div>
        </div>

        <div class="modal-footer">
          <button 
            class="btn btn-secondary" 
            @click="emit('close')"
            :disabled="cargando"
          >
            Cancelar
          </button>
          <button 
            class="btn btn-success" 
            @click="asignar"
            :disabled="cargando || !instructorId"
          >
            <span v-if="cargando" class="spinner-border spinner-border-sm me-1"></span>
            Asignar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>