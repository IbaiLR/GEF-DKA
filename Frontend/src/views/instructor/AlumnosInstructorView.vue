<script setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import Navbar from '@/components/Navbar.vue'
import AlumnosList from '@/components/AlumnosList.vue'
import AlumnoDatos from '@/components/Instructor/AlumnoDatosIns.vue'

const route = useRoute()
const instructorId = route.params.id

const alumnoSeleccionado = ref(null)
</script>

<template>
  <Navbar />

  <div class="container-fluid">
    <div class="row">
      <!-- Izquierda -->
      <AlumnosList
        class="col-md-3"
        :endpoint="`/api/instructores/${instructorId}/alumnos`"
        @seleccionarAlumno="alumnoSeleccionado = $event"
      />

      <!-- Derecha -->
        <AlumnoDatos :alumno="alumnoSeleccionado" />
    </div>
  </div>
</template>
