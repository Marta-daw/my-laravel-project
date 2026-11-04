<template>
    <q-page class="q-pa-md">
        <div class="row col-12 ">
            <h1 class="text-h4 q-mb-md text-bold">Llibres de la col·lecció</h1>
            <q-btn
                class="q-ml-auto q-mb-md"
                outline
                border-color="primary"
                icon="add"
                label="Afegir llibre"
                aria-label="Afegir un llibre nou"
                @click="router.push('/biblioteca/llibres/nou')"
            />
        </div>

        <div class="col-12">
            <Libros />
        </div>
    </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useQuasar } from 'quasar';

import { useRouter } from 'vue-router';

import Libros from '@/components/Llibre.vue';

const router = useRouter();

</script>

<style scoped></style>