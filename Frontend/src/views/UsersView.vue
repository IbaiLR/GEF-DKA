<script setup>
import { ref } from "vue";
import Navbar from "@/components/Navbar.vue";
import UserFilters from "@/components/UserFilters.vue";
import UserTable from "@/components/UserTable.vue";

const filters = ref(null);

function onFiltersChange(newFilters) {
  // Reiniciamos siempre filters
  filters.value = null;

  // 1️⃣ Selecciona un tipo → ocultar tabla
  if (newFilters.tipo === "NONE") {
    return;
  }

  // 2️⃣ Alumno sin grado seleccionado → ocultar tabla
  if (newFilters.tipo === "alumno" && (!newFilters.id_grado || newFilters.id_grado === "NONE")) {
    return;
  }

  // 3️⃣ Alumno + Todos los grados → mostrar tabla con todos los alumnos
  if (newFilters.tipo === "alumno" && newFilters.id_grado === ".") {
    filters.value = { tipo: "alumno", id_grado: null };
    return;
  }

  // 4️⃣ Todos los tipos → mostrar tabla sin filtros
  if (newFilters.tipo === ".") {
    filters.value = {};
    return;
  }

  // 5️⃣ Caso normal: Alumno con grado concreto → enviar solo id_grado
  if (newFilters.tipo === "alumno") {
    filters.value = {
      tipo: "alumno",
      id_grado: newFilters.id_grado
    };
    return;
  }

  // 6️⃣ Otros tipos: Tutor, Instructor, Admin → reset id_grado
  filters.value = {
    tipo: newFilters.tipo,
    id_grado: null
  };

  console.log("Filters activos:", filters.value);
}




</script>

<template>
  <Navbar />

  <div class="container mt-4">
    <UserFilters @change="onFiltersChange" />

    <UserTable v-if="filters" :filters="filters"/>

    <div v-else class="alert alert-info text-center">
      Selecciona un tipo de usuario para mostrar la tabla
    </div>
  </div>
</template>
