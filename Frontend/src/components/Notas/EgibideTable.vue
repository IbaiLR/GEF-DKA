<script setup>
const props = defineProps({
  egibide: Array
})
</script>

<template>
<div class="mb-5">
  <h5>🏫 Asignaturas (Egibide)</h5>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="table-indigo text-white text-center text-md-start">
        <tr>
          <th>Asignatura</th>
          <th>Nota</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="n in egibide" :key="n.id">
          <td class="text-center text-md-start">{{ n.asignatura?.nombre ?? 'Sin nombre' }}</td>
          <td class="text-center text-md-start">
            <span :class="{
              'badge bg-success': n.nota >= 5,
              'badge bg-danger text-white': n.nota < 5,
              'badge bg-warning text-dark': n.nota == null
            }">{{ n.nota ?? 'Sin nota' }}</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
</template>