<script setup>
import NotasAlumno from "@/components/Alumno/NotasAlumno.vue";
import Navbar from "@/components/Navbar.vue";

</script>

<template>
  <Navbar />

 
    <NotasAlumno
      tipo="Competencias transversales"
      :competencias="[
        { id: 1, descripcion: 'Programa y prueba aplicaciones' },
        { id: 2, descripcion: 'Desarrolla el frontend...' },
        { id: 3, descripcion: 'Desarrolla el backend...' },
      ]"
    />

    <NotasAlumno
      tipo="Competencias técnicas"
      :competencias="[
        { id: 1, descripcion: 'Programa y prueba aplicaciones' },
        { id: 2, descripcion: 'Desarrolla el frontend...' },
        { id: 3, descripcion: 'Desarrolla el backend...' },
      ]"
    />
  <!-- </div> -->
</template>

<!-- 
<script setup>
  import { useRoute } from "vue-router";
  import Navbar from "@/components/Navbar.vue";
  import NotasAlumno from "@/components/Alumno/NotasAlumno.vue";
  
  const route = useRoute();
  const idAlumno = route.params.idAlumno; 
  </script>
  
  <template>
    <Navbar />
    
    <div class="container mt-4 mb-5">
      <NotasAlumno :idAlumno="idAlumno" />
    </div>
  </template> -->