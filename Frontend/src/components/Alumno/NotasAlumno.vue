<script setup>
import { defineProps, reactive } from "vue";

const props = defineProps({
  tipo: { type: String, required: true },
  competencias: { type: Array, default: () => [] },
});

const notas = reactive({});

import { useRoute, useRouter } from "vue-router";
import { onMounted, ref } from "vue";
import axios from "axios";
const route = useRoute();
const router = useRouter();
const idAlumno = route.params.idAlumno;
const loading = ref(true);
const autorizado = ref(false);
onMounted(async () => {
  try {
    const token = localStorage.getItem("token");
    console.log("TOKEN: ", token);
    await axios.get(`http://127.0.0.1:8000/api/alumnos/${idAlumno}/notas`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    autorizado.value = true;
  } catch (err) {
    if (err.response?.status === 403 || err.response?.status === 401) {
      // No autorizado: fuera
      router.push("/home");
    } else {
      console.error(err);
    }
  } finally {
    loading.value = false;
  }
});
</script>

<template>
  <div v-if="loading">
    Cargando
    <ul class="carga">
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
    </ul>
  </div>

  <div v-else-if="autorizado">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th style="width: 50px">#</th>
          <th>{{ tipo }}</th>
          <th style="width: 120px">Nota</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(c, index) in competencias" :key="c.id">
          <td class="text-center">{{ index + 1 }}</td>

          <td>
            {{ c.descripcion }}
          </td>

          <td>
            <select
              class="form-select form-select-sm"
              v-model="notas[c.id]"
              required
            >
              <option v-for="n in 4" :key="n" :value="n">
                {{ n }}
              </option>
            </select>
          </td>
        </tr>

        <tr v-if="competencias.length === 0">
          <td colspan="3" class="text-center">No hay competencias</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div v-else>
    <h1> DONDE VAS PRINGAO</h1>
  </div>
</template>

<!-- 
<script setup>
  import { ref, onMounted, defineProps } from "vue";
  import axios from "axios";
  
  // Recibimos el ID del alumno desde la vista padre
  const props = defineProps({
    idAlumno: { type: [String, Number], required: true }
  });
  
  // Estado de la interfaz
  const loading = ref(true);
  const guardando = ref(false);
  const nombreAlumno = ref("");
  
  // Datos de las tablas
  const transversales = ref([]);
  const tecnicas = ref([]);
  
  // Almacén de cambios (solo lo que se va a enviar)
  const cambios = ref({
    transversales: {},
    tecnicas: {}
  });
  
  // Cargar datos al montar el componente
  onMounted(async () => {
    loading.value = true;
    try {
      const token = localStorage.getItem('token');
      const res = await axios.get(`http://127.0.0.1:8000/api/alumnos/${props.idAlumno}/notas`, {
        headers: { Authorization: `Bearer ${token}` }
      });
      
      // Asignamos datos
      const data = res.data.data;
      transversales.value = data.transversales;
      tecnicas.value = data.tecnicas;
      nombreAlumno.value = data.alumno_nombre;
  
    } catch (error) {
      console.error("Error cargando notas:", error);
      alert("Error al cargar los datos del alumno.");
    } finally {
      loading.value = false;
    }
  });
  
  // Función para registrar cambios (se ejecuta al mover un select)
  function registrarCambio(tipo, id, evento) {
    const valor = evento.target.value;
    // Guardamos en el objeto de cambios: cambios.tecnicas[5] = 4
    cambios.value[tipo][id] = valor;
  }
  
  // Enviar datos al servidor
  async function guardarNotas() {
    guardando.value = true;
    try {
      const token = localStorage.getItem('token');
      await axios.post(`http://127.0.0.1:8000/api/alumnos/${props.idAlumno}/notas`, {
        transversales: cambios.value.transversales,
        tecnicas: cambios.value.tecnicas
      }, {
        headers: { Authorization: `Bearer ${token}` }
      });
  
      alert("¡Evaluación guardada correctamente!");
      // Opcional: Limpiar los cambios pendientes
      cambios.value.transversales = {};
      cambios.value.tecnicas = {};
  
    } catch (error) {
      console.error("Error guardando:", error);
      alert("Hubo un error al guardar las notas.");
    } finally {
      guardando.value = false;
    }
  }
  </script>
  
  <template>
    <div class="container-notas">
      
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h4 class="mb-0">Evaluación de Competencias</h4>
          <small class="text-muted" v-if="!loading">{{ nombreAlumno }}</small>
        </div>
        <button 
          class="btn btn-primary" 
          @click="guardarNotas" 
          :disabled="loading || guardando"
        >
          <span v-if="guardando" class="spinner-border spinner-border-sm me-1"></span>
          {{ guardando ? 'Guardando...' : 'Guardar Cambios' }}
        </button>
      </div>
  
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-2">Cargando evaluación...</p>
      </div>
  
      <div v-else>
        
        <div class="card mb-4 shadow-sm">
          <div class="card-header bg-light fw-bold">Competencias Transversales</div>
          <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
              <thead class="table-light">
                <tr>
                  <th class="text-center" style="width: 50px">#</th>
                  <th>Descripción</th>
                  <th class="text-center" style="width: 150px">Nota</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(t, index) in transversales" :key="t.id">
                  <td class="text-center">{{ index + 1 }}</td>
                  <td>{{ t.descripcion }}</td>
                  <td class="text-center">
                    <select 
                      class="form-select form-select-sm"
                      :value="t.nota" 
                      @change="registrarCambio('transversales', t.id, $event)"
                    >
                      <option :value="null" disabled> - </option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                    </select>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
  
        <div class="card shadow-sm">
          <div class="card-header bg-light fw-bold">Competencias Técnicas</div>
          <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
              <thead class="table-light">
                <tr>
                  <th class="text-center" style="width: 50px">#</th>
                  <th>Descripción (RA / Competencia)</th>
                  <th class="text-center" style="width: 150px">Nota</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(c, index) in tecnicas" :key="c.id">
                  <td class="text-center">{{ index + 1 }}</td>
                  <td>{{ c.descripcion }}</td>
                  <td class="text-center">
                    <select 
                      class="form-select form-select-sm"
                      :value="c.nota" 
                      @change="registrarCambio('tecnicas', c.id, $event)"
                    >
                      <option :value="null" disabled> - </option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                    </select>
                  </td>
                </tr>
                <tr v-if="tecnicas.length === 0">
                  <td colspan="3" class="text-center py-3 text-muted">No hay competencias técnicas asignadas.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
  
      </div>
    </div>
  </template> -->