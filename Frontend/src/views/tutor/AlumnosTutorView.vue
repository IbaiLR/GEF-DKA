<script setup>
    import { useRoute } from "vue-router";
    import AlumnosList from "../../components/AlumnosList.vue";

const route = useRoute();
const tutorId = route.params.id;    
</script>
<template>
     <AlumnosList
    :endpoint="`http://127.0.0.1:8000/api/tutores/${tutorId}/alumnos`"
    title="Alumnos del tutor"
  />
</template>