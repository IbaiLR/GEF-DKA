<script setup>
const props = defineProps({
  competencias: Array
})
</script>

<template>
<div class="mb-5">
  <h5>🏆 Competencias</h5>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="table-indigo text-white text-center text-md-start">
        <tr>
          <th>Competencia</th>
          <th>Nota</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="n in competencias" :key="n.id">
          <td class="text-center text-md-start">{{ n.competencia?.descripcion ?? 'Sin descripción' }}</td>
          <td class="text-center text-md-start">
            <span :class="{
              'badge bg-success': n.Nota >= 5,
              'badge bg-danger text-white': n.Nota < 5,
              'badge bg-warning text-dark': n.Nota == null
            }">{{ n.Nota ?? 'Sin nota' }}</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
</template>