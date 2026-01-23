<script setup>
import axios from "axios";
import { ref, watch, onMounted } from "vue";
import UserCreationButtons from "./UserCreationButtons.vue";
import api from '@/services/api.js'
const emit = defineEmits(["change"]);

const tipo = ref("NONE");
const grado = ref("NONE");
const grados = ref([]);
async function cargarGrados() {
  try {
    const response = await api.get("/api/grados");
    grados.value = response.data.data;
  } catch (e) {
    console.error(e);
  }
}

onMounted(() => {
  cargarGrados();
});

watch([tipo, grado], () => {
  emit("change", {
    tipo: tipo.value,
    id_grado: grado.value
  });
});




</script>


<template>
  <div class="card mb-4">
    <div class="card-body">
      <div class="mb-3">
        <label class="form-label">Tipo de usuario</label>
        <select v-model="tipo" class="form-select">
          <option value="NONE">Selecciona un tipo</option>
          <option value=".">Todos</option>
          <option value="alumno">Alumno</option>
          <option value="tutor">Tutor</option>
          <option value="instructor">Instructor</option>
          <option value="admin">Administrador</option>
        </select>
      </div>

      <div v-if="tipo === 'alumno'" class="mb-3">
        <label class="form-label">Grado</label>
        <select v-model="grado" class="form-select">
          <option value="NONE">Selecciona un grado</option>
          <option value=".">Todos</option>
          <option  v-for="g in grados" :key="g.id" :value="g.id">
            {{ g.nombre }}
          </option>
        </select>
      </div>

      <UserCreationButtons :tipo="tipo" :grado="grado == '.' ? false : grado"></UserCreationButtons>



    </div>
  </div>
</template>
