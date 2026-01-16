<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import Buscador from "./Buscador.vue"; // Asegúrate de que la ruta es correcta

// Estado
const grados = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);
const loading = ref(false);

// Variable para guardar el texto actual de búsqueda
const searchQuery = ref("");
let searchTimeout = null;

// Función que recibe el evento del hijo (Buscador.vue)
function onSearch(texto) {
  // Actualizamos el valor local
  searchQuery.value = texto;

  // LOGICA DEBOUNCE: Cancelamos la petición anterior si escribe rápido
  if (searchTimeout) clearTimeout(searchTimeout);

  // Esperamos 400ms antes de llamar a la API
  searchTimeout = setTimeout(() => {
    fetchGrados(1); // Al buscar, siempre volvemos a la página 1
  }, 400);
}

const fetchGrados = async (page = 1) => {
  loading.value = true;
  try {
    // Enviamos 'page' y 'q' (query de búsqueda)
    const response = await axios.get(`http://127.0.0.1:8000/api/grados`, {
        params: {
            page: page,
            q: searchQuery.value // Enviamos lo que haya en el buscador
        }
    });
    
    const data = response.data;
    
    grados.value = data.data || []; 
    currentPage.value = data.current_page || 1;
    totalPages.value = data.last_page || 1;

  } catch (error) {
    console.error("Error al cargar los grados:", error);
  } finally {
    loading.value = false;
  }
};

// Cargar datos iniciales
onMounted(() => {
  fetchGrados(1);
});
</script>

<template>
  <div>
    <Buscador tipo="Buscar grado o curso..." @search="onSearch" />

    <div v-if="loading" class="text-center py-3">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Cargando...</span>
      </div>
    </div>

    <div v-else>
        <table class="table table-striped w-25">
        <thead>
            <tr>
          
            <th> Grado</th>
           
            </tr>
        </thead>
        <tbody>
            <tr v-for="grado in grados" :key="grado.id">
            <td>{{ grado.nombre }} ({{ grado.curso }})</td> 
            </tr>
            
            <tr v-if="grados.length === 0">
                <td colspan="3" class="text-center py-4">
                    No se encontraron grados con: "<strong>{{ searchQuery }}</strong>"
                </td>
            </tr>
        </tbody>
        </table>

        <nav v-if="totalPages > 1">
        <ul class="pagination justify-content-center">
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
            <button 
                class="page-link" 
                @click.prevent="fetchGrados(currentPage - 1)"
            >
                Anterior
            </button>
            </li>

            <li
            class="page-item"
            v-for="page in totalPages"
            :key="page"
            :class="{ active: currentPage === page }"
            >
            <button 
                class="page-link" 
                @click.prevent="fetchGrados(page)"
            >
                {{ page }}
            </button>
            </li>

            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
            <button 
                class="page-link" 
                @click.prevent="fetchGrados(currentPage + 1)"
            >
                Siguiente
            </button>
            </li>
        </ul>
        </nav>
    </div>
  </div>
</template>