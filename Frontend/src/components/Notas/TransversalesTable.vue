<script setup>
const props = defineProps({
  transversales: Array
})
</script>

<template>
<div class="mb-5">
  <h5>🎯 Transversales</h5>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="table-indigo text-white text-center text-md-start">
        <tr>
          <th>Transversal</th>
          <th>Nota</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="n in transversales" :key="n.id">
          <td class="text-center text-md-start">{{ n.transversal?.descripcion ?? 'Sin descripción' }}</td>
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