<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import api from "@/services/api.js"

// Estados
const alumnos = ref([]);
const asignaturas = ref([]);
const gradoNombre = ref("");
const loading = ref(false);


// Control del acordeón (guardamos el ID del alumno desplegado)
const alumnoDesplegado = ref(null);

// Función para alternar el desplegable
const toggleNotas = (idAlumno) => {
  if (alumnoDesplegado.value === idAlumno) {
    alumnoDesplegado.value = null; // Cerrar si ya está abierto
  } else {
    alumnoDesplegado.value = idAlumno; // Abrir el seleccionado
  }
};

// Cargar datos del tutor
const fetchDatosGrado = async () => {
  loading.value = true;
  const token = localStorage.getItem('token');
  try {
    const res = await api.get("/api/mi-grado/gestion", {
        headers: { Authorization: `Bearer ${token}` }
    });
    
    alumnos.value = res.data.alumnos;
    asignaturas.value = res.data.asignaturas;
    gradoNombre.value = res.data.grado.nombre;

  } catch (error) {
    console.error("Error cargando datos del grado:", error);
  } finally {
    loading.value = false;
  }
};
async function getNotasAlumno(idAlumno){

}

// Función Placeholder para el cálculo futuro
const calcularNotaFinal = (alumno) => {
    alert(`Aquí lanzaremos el algoritmo complejo para ${alumno.nombre}`);
    // Aquí llamaremos al GradeCalculatorService más adelante
};

onMounted(() => {
  fetchDatosGrado();
});
</script>

<template>
  <div class="card shadow-sm border-0">
    <div class="card-header bg-indigo text-white py-3">
      <h5 class="mb-0">
        <i class="bi bi-mortarboard-fill me-2"></i>
        Gestión de Notas - {{ gradoNombre || 'Cargando...' }}
      </h5>
    </div>

    <div class="card-body p-0">
      <div v-if="loading" class="text-center p-5">
        <div class="spinner-border text-indigo" role="status"></div>
        <p class="mt-2 text-muted">Cargando alumnos...</p>
      </div>

      <div v-else class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead class="table-light">
            <tr>
              <th class="ps-4">Apellidos y Nombre</th>
              <th>Email</th>
              <th>Teléfono</th>
              <th class="text-end pe-4">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="alumno in alumnos" :key="alumno.id">
              <tr :class="{'table-active': alumnoDesplegado === alumno.id}">
                <td class="ps-4 fw-bold text-secondary">
                  {{ alumno.apellidos }}, {{ alumno.nombre }}
                </td>
                <td>{{ alumno.email }}</td>
                <td>{{ alumno.n_tel || '-' }}</td>
                <td class="text-end pe-4">
                  <button 
                    class="btn btn-sm" 
                    :class="alumnoDesplegado === alumno.id ? 'btn-indigo text-white' : 'btn-outline-indigo'"
                    @click="toggleNotas(alumno.id)"
                  >
                    <i class="bi" :class="alumnoDesplegado === alumno.id ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                    Notas
                  </button>
                </td>
              </tr>

              <tr v-if="alumnoDesplegado === alumno.id" class="bg-light-subtle">
                <td colspan="4" class="p-0">
                  <div class="p-4 border-bottom border-indigo-subtle animacion-entrada">
                    
                    <h6 class="text-indigo fw-bold mb-3">
                      <i class="bi bi-journal-text me-2"></i>
                      Resumen de Calificaciones
                    </h6>

                    <table class="table table-sm table-bordered bg-white shadow-sm mb-3 text-center align-middle">
                      <thead class="table-secondary small">
                        <tr>
                          <th rowspan="2" class="align-middle">Asignatura</th>
                          
                          <th rowspan="2" class="align-middle bg-warning-subtle" style="width: 15%;">Nota Egibide (80%)</th>

                          <th colspan="3" class="border-bottom-0">Parte Empresa (20%)</th>
                          
                          <th rowspan="2" class="align-middle bg-success-subtle" style="width: 10%;">NOTA FINAL</th>
                        </tr>
                        <tr>
                            <th class="fw-normal text-muted" style="font-size: 0.8rem; width: 12%;">Técnica (60%)</th>
                            <th class="fw-normal text-muted" style="font-size: 0.8rem; width: 12%;">Transv. (20%)</th>
                            <th class="fw-normal text-muted" style="font-size: 0.8rem; width: 12%;">Cuaderno (20%)</th>
                        </tr>
                      </thead>
                  <tbody>
  <tr v-for="asig in asignaturas" :key="asig.id">
    <td class="text-start px-3 fw-bold text-secondary">{{ asig.nombre }}</td>
    
    <td class="bg-warning-subtle fw-bold text-dark">
        {{ alumno.notas_calculadas?.[asig.id]?.egibide ?? '-' }}
    </td>

    <td class="text-muted fst-italic">
        {{ alumno.notas_calculadas?.[asig.id]?.tecnica ?? '-' }}
    </td>

    <td class="text-muted fst-italic">
        {{ alumno.notas_calculadas?.[asig.id]?.transversal ?? '-' }}
    </td>

    <td class="text-muted fst-italic">
        {{ alumno.notas_calculadas?.[asig.id]?.cuaderno ?? '-' }}
    </td>
    
    <td class="bg-success-subtle fw-bold text-dark fs-6">
       {{ alumno.notas_calculadas?.[asig.id]?.final ?? '-' }}
    </td>
  </tr>
</tbody>
                    </table>

                    <div class="d-flex justify-content-end mt-3">
                      <button class="btn btn-success d-flex align-items-center gap-2 shadow-sm" @click="calcularNotaFinal(alumno)">
                        <i class="bi bi-calculator"></i>
                        Asignar / Calcular Nota
                      </button>
                    </div>

                  </div>
                </td>
              </tr>
            </template>
            
            <tr v-if="alumnos.length === 0">
                <td colspan="4" class="text-center py-4 text-muted">No hay alumnos matriculados en este grado.</td>
            </tr>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<style scoped>

.bg-warning-subtle { background-color: #fff3cd !important; }
.bg-success-subtle { background-color: #d1e7dd !important; }

.bg-light-subtle { background-color: #f8f9fa; }
.border-indigo-subtle { border-bottom: 2px solid #e0cffc !important; }

/* Animación de apertura */
.animacion-entrada {
  animation: slideDown 0.3s ease-out;
}
@keyframes slideDown {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>