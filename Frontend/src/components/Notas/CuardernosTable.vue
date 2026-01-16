<script setup>
import { computed } from 'vue'

const props = defineProps({
  entregas: Array,
  alumnoId: Number
})

const total = computed(() => props.entregas.length)
const corregidos = computed(() => props.entregas.filter(e => e.alumno_entrega.find(a => a.ID_Alumno === props.alumnoId)?.nota).length)
const pendientes = computed(() => props.entregas.filter(e => !e.alumno_entrega.find(a => a.ID_Alumno === props.alumnoId)?.nota).length)
</script>

<template>
<div class="mb-5">
  <h5>📝 Cuadernos</h5>
  <p>Total: {{ total }} | Corregidos: {{ corregidos }} | Pendientes: {{ pendientes }}</p>
  <div class="table-responsive">
    <table class="table table-hover table-bordered align-middle">
      <thead class="table-indigo text-white text-center text-md-start">
        <tr>
          <th>Fecha entrega</th>
          <th>Nota</th>
          <th>Enlace</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="c in entregas" :key="c.id">
          <td class="text-center text-md-start">{{ c.pivot.Fecha_Entrega }}</td>
          <td class="text-center text-md-start">
            <span :class="{
              'badge bg-success': c.alumno_entrega.find(ae => ae.ID_Alumno === alumnoId)?.nota?.Nota >= 5,
              'badge bg-danger text-white': c.alumno_entrega.find(ae => ae.ID_Alumno === alumnoId)?.nota?.Nota < 5,
              'badge bg-warning text-dark': !c.alumno_entrega.find(ae => ae.ID_Alumno === alumnoId)?.nota?.Nota
            }">
              {{ c.alumno_entrega.find(ae => ae.ID_Alumno === alumnoId)?.nota?.Nota ?? 'Pendiente' }}
            </span>
          </td>
          <td class="text-center text-md-start">
            <a :href="`http://localhost:8000/api/alumno/entregas/descargar/${c.pivot.ID}`" target="_blank" class="btn btn-outline-primary btn-sm">
              <i class="bi bi-download"></i> Descargar
            </a>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
</template>