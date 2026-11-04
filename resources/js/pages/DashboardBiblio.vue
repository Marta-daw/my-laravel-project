<template>
    <q-page class="q-pa-none">
        <div class="row q-pa-md">
            <h1 class="text-h4 q-mb-md text-bold">La Biblioteca</h1>
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
        <div class="q-px-md">
            <CardBiblio />
        </div>
    </q-page>
</template>

<script setup>
import { useRouter } from 'vue-router';
import CardBiblio from '@/components/Biblioteca/CardBiblio.vue';

const router = useRouter();
</script>

<style lang="scss" scoped>
</style>