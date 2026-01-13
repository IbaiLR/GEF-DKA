<template>
    <div class="col-4">
        <h3>Empresas</h3>

        <ul v-if="empresas.length">
            <li v-for="empresa in empresas" :key="empresa.id">
                {{ empresa.Nombre }}
            </li>
        </ul>

        <p v-else>Cargando empresas
        <ul class="carga">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
        </p>
    </div>
</template>

<script setup>
import axios from 'axios'
import { ref, onMounted } from 'vue'

const empresas = ref([])

const cargarEmpresas = async () => {
    const token = localStorage.getItem('token')
    try {
        const response = await axios.get('http://localhost:8000/api/empresas')
        empresas.value = await response.data
        console.log(empresas.value)
    } catch (error) {
        console.error('Error al cargar empresas:', error)
    }
}

onMounted(() => {
    cargarEmpresas()
})
</script>
