<script setup>
import { ref, watch } from 'vue'
import api from '@/services/api.js'

const props = defineProps({
  show: Boolean
})

const emit = defineEmits(['close', 'created'])

const form = ref({
  nombre: '',
  curso: ''
})

const cargando = ref(false)
const error = ref('')

watch(() => props.show, (val) => {
  if (val) {
    // Resetear formulario cuando se abre
    form.value = {
      nombre: '',
      curso: ''
    }
    error.value = ''
  }
})

async function crear() {
  if (!form.value.nombre.trim()) {
    error.value = 'El nombre del grado es obligatorio'
    return
  }

  cargando.value = true
  error.value = ''

  try {
    const token = localStorage.getItem('token')
    const res = await api.post('/api/grados', form.value, {
      headers: { Authorization: `Bearer ${token}` }
    })

    emit('created', res.data)
    emit('close')
  } catch (e) {
    console.error('Error creando grado:', e)
    error.value = e.response?.data?.message || 'Error al crear el grado'
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
            <i class="bi bi-building-fill-add"></i> Crear Nuevo Grado
          </h5>
          <button class="btn-close btn-close-white" @click="emit('close')" :disabled="cargando"></button>
        </div>

        <div class="modal-body">
          <div v-if="error" class="alert alert-danger alert-dismissible">
            {{ error }}
            <button type="button" class="btn-close" @click="error = ''"></button>
          </div>

          <div class="mb-3">
            <label class="form-label">Nombre del Grado <span class="text-danger">*</span></label>
            <input 
              type="text" 
              class="form-control" 
              v-model="form.nombre"
              placeholder="Ej: Desarrollo de Aplicaciones Web (DAW)"
              :disabled="cargando"
            />
          </div>

          <div class="mb-3">
            <label class="form-label">Curso</label>
            <select 
              class="form-select" 
              v-model="form.curso"
              :disabled="cargando"
            >
              <option value="">-- Selecciona un curso --</option>
              <option value="1º">1º</option>
              <option value="2º">2º</option>
            </select>
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
            @click="crear"
            :disabled="cargando || !form.nombre.trim()"
          >
            <span v-if="cargando" class="spinner-border spinner-border-sm me-1"></span>
            <i v-else class="bi bi-check-lg"></i>
            Crear Grado
          </button>
        </div>
      </div>
    </div>
  </div>
</template>