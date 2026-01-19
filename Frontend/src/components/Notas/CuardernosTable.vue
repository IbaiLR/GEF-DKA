<script setup>
import { computed } from 'vue'

const props = defineProps({
  cuadernos: {
    type: Array,
    required: true
  }
})

const total = computed(() => props.cuadernos.length)

const corregidos = computed(
  () => props.cuadernos.filter(c => c.nota).length
)

const pendientes = computed(
  () => props.cuadernos.filter(c => !c.nota).length
)
</script>

<template>
  <div class="mb-5">
    <h5>Cuadernos</h5>
    <p>
      Total: {{ total }} |
      Corregidos: {{ corregidos }} |
      Pendientes: {{ pendientes }}
    </p>

    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle">
        <thead class="table-indigo text-white">
          <tr>
            <th>Fecha entrega</th>
            <th>Fecha límite</th>
            <th>Nota</th>
            <th>Enlace</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="c in cuadernos" :key="c.id">
            <td>{{ c.Fecha_Entrega }}</td>

            <td>
              {{ c.entrega?.Fecha_Limite ?? '-' }}
            </td>

            <td>
              <span
                :class="{
                  'badge bg-success': c.nota?.Nota >= 5,
                  'badge bg-danger': c.nota?.Nota < 5,
                  'badge bg-warning text-dark': !c.nota
                }"
              >
                {{ c.nota?.Nota ?? 'Pendiente' }}
              </span>
            </td>

            <td>
              <a
                :href="c.URL_Cuaderno"
                target="_blank"
                class="btn btn-outline-indigo btn-sm"
              >
                <i class="bi bi-download"></i> Descargar
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

