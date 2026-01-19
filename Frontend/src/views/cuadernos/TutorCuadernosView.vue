<script setup>
import CuadernosObservaciones from '@/components/Cuaderno/CuadernosObservaciones.vue'
import NotasCuaderno from './NotasCuaderno.vue';
import Navbar from '../../components/Navbar.vue';

</script>

<template>
  <Navbar></Navbar>
  <div class="container mt-4">
    <CuadernosObservaciones />
    <NotasCuaderno />
  </div>
</template>
