<script setup>
import Navbar from '@/components/Navbar.vue';
import GradosList from '@/components/Grado/GradosList.vue';
import GradoDatos from '@/components/Grado/GradoDatos.vue';
import { ref } from 'vue';

const gradoSeleccionado = ref(null);
const vistaActual = ref(null);

function mostrarAsignaturas(grado) {
    gradoSeleccionado.value = grado;
    vistaActual.value = grado ? 'asignaturas' : null;
}

function mostrarCompetencias(grado) {
    gradoSeleccionado.value = grado;
    vistaActual.value = grado ? 'competencias' : null;
}
</script>

<template>
    <Navbar></Navbar>

    <div class="container-fluid mb-5">
        <div class="row">
            <GradosList @verAsignaturas="mostrarAsignaturas" @verCompetencias="mostrarCompetencias" />

            <GradoDatos v-if="gradoSeleccionado" :grado="gradoSeleccionado" :vista="vistaActual" />

            <div v-else class="col-12 col-lg-8 mt-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center text-muted py-5">
                        <i class="bi bi-building" style="font-size: 4rem;"></i>
                        <p class="mt-3">Selecciona un grado para ver sus asignaturas o competencias</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>