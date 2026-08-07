<template>
    <q-card class="my-card">
                <q-card-section>
                    <div class="text-h6">Total de llibres</div>
                    <div class="text-h4">{{ totalLlibres }}</div>
                </q-card-section>
                <q-card-section>
                    <q-chips>
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
            </q-card>
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