<template>
    <span class="col-12 row">
        <q-card class="my-card col-8 col-md-6 q-mr-md">
            <div class="row">
                <q-card-section class="col-8 col-md-4">
                    <div class="text-h6">Total de llibres</div>
                    <div class="text-h4">{{ totalLlibres }}</div>
                </q-card-section>
                <q-card-section class="col-4 col-md-8">
                    <q-chips class="column" style="width: fit-content;">
                        <q-chip color="green" text-color="white" class="q-mr-sm">
                            Llegits: {{ llegits }}
                        </q-chip>
                        <q-chip color="red" text-color="white" class="q-mr-sm">
                            No Llegits: {{ noLlegits }}
                        </q-chip>
                        <q-chip color="blue" text-color="white">
                            En Proceso: {{ enProceso }}
                        </q-chip>
                    </q-chips>
                </q-card-section>
            </div>    
        </q-card>
        <q-card class="col-4 col-md-5">
            <h3>Descripció</h3>
        </q-card>
    </span>
    <span class="col-12 row q-mt-md">
        <q-card class="my-card col-8 col-md-6 q-mr-md">
            <div class="row">
                <q-card-section class="col-6 col-md-12">
                    <div class="text-h4">Categories</div>
                    <div class="text-h6">(Gràfiques amb les categories i quants llibres de cada una hi ha?)</div>
                    <div class="text-h6">(Gràfiques amb les categories que tinguessis la major quantitat de llibres)</div>
                </q-card-section>
            </div>    
        </q-card>
        <q-card class="col-4 col-md-5">
            <q-card-section class="col-6 col-md-12">
                    <div class="text-h4">Llibres</div>
                    <div class="text-h6">(Llista dels últims llibres inclosos)</div>
                </q-card-section>
        </q-card>
    </span>
</template>

<script setup>
import { ref } from 'vue';
import { getCountLlibres, getLlibresLlegits } from '@/services/api';

const totalLlibres = ref(0);
const llegits = ref(0);
const noLlegits = ref(0);
const enProceso = ref(0);

async function fetchCount() {
    const response = await getCountLlibres();
    totalLlibres.value = response.data.total;
}

async function fetchLlibresLlegits() {
    const response = await getLlibresLlegits();
    llegits.value = response.data.llegits;
}

async function fetchLlibresNoLlegits() {
    const response = await getLlibresLlegits();
    noLlegits.value = response.data.noLlegits;
}

async function fetchLlibresEnProceso() {
    const response = await getLlibresLlegits();
    enProceso.value = response.data.enProceso;
}

fetchCount();
fetchLlibresLlegits();
fetchLlibresNoLlegits();
fetchLlibresEnProceso();
</script>

<style lang="scss" scoped>
</style>