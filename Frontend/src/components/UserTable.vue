<template>
  <div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Email</th>
          <th>Tipo</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.nombre }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.tipo }}</td>
        </tr>
      </tbody>
    </table>

    <!-- Paginación Bootstrap -->
    <nav v-if="totalPages > 1">
      <ul class="pagination">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <button class="page-link" @click="fetchUsers(currentPage - 1)">Anterior</button>
        </li>

        <li
          class="page-item"
          v-for="page in totalPages"
          :key="page"
          :class="{ active: currentPage === page }"
        >
          <button class="page-link" @click="fetchUsers(page)">{{ page }}</button>
        </li>

        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
          <button class="page-link" @click="fetchUsers(currentPage + 1)">Siguiente</button>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from "vue";
import axios from "axios";

const props = defineProps({
  filters: Object
});

const users = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);
const perPage = ref(5);

async function fetchUsers(page = 1) {
  currentPage.value = page;

  try {
    
    const response = await axios.get("http://127.0.0.1:8000/api/users", {
      params: {
        page,
        per_page: perPage.value,
        tipo: props.filters?.tipo,
        id_grado: props.filters?.id_grado
      }
    });
    
    users.value = response.data.data.data || [];
    
    totalPages.value = response.data.data.last_page;
  } catch (error) {
    console.error(error);
  }
}

watch(
  () => props.filters,
  () => {
    if(props.filters?.id_grado === "NONE"){
      users.value = []
      totalPages.value = 0
      return
    }

    fetchUsers(1)
  },
  { immediate: true }
);
</script>


<style scoped>
.pagination {
  justify-content: center;
}
</style>
