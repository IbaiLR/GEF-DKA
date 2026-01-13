<script setup>
    import Navbar from '@/components/Navbar.vue';
    import EmpresasList from '@/components/EmpresasList.vue';
    import EmpresaDatos from '@/components/EmpresaDatos.vue';
</script>

<template>
    <Navbar></Navbar>
    <div class="container-fluid">
        <div class="row">
            <EmpresasList></EmpresasList>
            <EmpresaDatos></EmpresaDatos>
        </div>
    </div>
</template>


<style scoped>

</style>
