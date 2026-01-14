<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

// Estado reactivo
const estancias = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);
const perPage = ref(5);

// Función para obtener usuarios
async function fetchEstancias(page = 1) {
  currentPage.value = page;
  try {
    const response = await axios.get("http://127.0.0.1:8000/api/alumno/{id}/estancias", {
      params: {
        page: currentPage.value,
        per_page: perPage.value,
      },
    });

    estancias.value = response.data.data.data ||[];
    totalPages.value = response.data.data.last_page;
  } catch (error) {
    console.error(error);
  }
}

// Cargar primera página al montar
onMounted(() => {
  fetchEstancias(1);
});
</script>
<template>
  <div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Empresa</th>
          <th>Instructor</th>
          <th>Tutor</th>
          <th>Fecha inicio</th>
          <th>Fecha fin</th>
          <th>Horario</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="estancia in estancias" :key="estancia.id">
          <td>{{ estancia.empresa }}</td>
          <td>{{ estancia.intrusctor }}</td>
          <td>{{ user.tutor }}</td>
          <td>{{ user.fecha_inicio }}</td>
          <td>{{ user.fecha_fin }}</td>
          <td>{{ user.horario }}</td>
        </tr>
      </tbody>
    </table>

    <!-- Paginación Bootstrap -->
    <nav>
      <ul class="pagination">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <button class="page-link" @click="fetchEstancias(currentPage - 1)">Anterior</button>
        </li>

        <li
          class="page-item"
          v-for="page in totalPages"
          :key="page"
          :class="{ active: currentPage === page }"
        >
          <button class="page-link" @click="fetchEstancias(page)">{{ page }}</button>
        </li>

        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
          <button class="page-link" @click="fetchEstancias(currentPage + 1)">Siguiente</button>
        </li>
      </ul>
    </nav>
  </div>
</template>
