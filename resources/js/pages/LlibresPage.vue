<template>
    <q-page class="q-pa-md">
        <div class="col-12 q-px-md">
            <h1>Llibres de la col·lecció</h1>
        </div>
        <div class="col-12 q-px-md">
            <q-btn color="primary" label="Afegir llibre" to="/afegir-llibre" />
            <q-btn color="secondary" label="Veure detalls" to="/detalls-llibre" />
        </div>

        <div class="col-12">
            <Libros />
        </div>
    </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useQuasar } from 'quasar';

import Libros from '@/components/Llibre.vue';

</script>

<style scoped></style>