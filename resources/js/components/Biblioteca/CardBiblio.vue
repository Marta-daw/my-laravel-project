<template>
    <!-- <span class="col-12 row">
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
    </span> -->

    <span class="row q-col-gutter-md">
        <div
            v-for = "item in datosTarjeta"
            :key="item.key"
            class="col-12 col-sm-6 col-md-3"
        >
            <q-card 
                class="my-card full-height"
                :class="`bg-${item.color} text-white`"
                :aria-label="`${item.label}: ${item.value}`"
            >
                <q-card-section class="row items-center no-wrap">
                    <q-icon :name="item.icon" size="3rem" class="q-mr-md text-white"/>
                    <div class="text-h6">{{ item.label }}</div>
                    <div class="text-h4 q-mx-auto">{{ item.value }}</div>
                </q-card-section>
            </q-card>
        </div> 
    </span>

    <span class="col-12 row q-mt-md">
        <q-card class="col-12">
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
import { onMounted, computed, ref } from 'vue';
import { getResumLectures } from '@/services/api';

const totalLlibres = ref(0);
const llegits = ref(0);
const noLlegits = ref(0);
const enProceso = ref(0);

const error = ref(null);
const carregant = ref(true);

onMounted(async () => {
    try {
        const data = await getResumLectures();
        totalLlibres.value = data.data.total;
        llegits.value = data.data.leidos;
        noLlegits.value = data.data.noLeidos;
        enProceso.value = data.data.enProceso;
    } catch (err) {
        error.value = err.response?.data?.message ?? err.message;
        console.error('Error en carregar les dades', error.value);
    } finally {
        carregant.value = false;
    }
});

const datosTarjeta = computed(() => [
    {key: 'total', label: 'Total de llibres', value: totalLlibres.value , icon: 'menu_book', color: 'primary'},
    {key: 'llegits', label: 'Llibres llegits', value: llegits.value, icon: 'check_circle', color: 'positive'},
    {key: 'noLlegits', label: 'Llibres no Llegits', value: noLlegits.value, icon: 'cancel', color: 'warning'},
    {key: 'enProceso', label: 'En Proceso', value: enProceso.value, icon: 'schedule', color: 'info'},
])
</script>

<style lang="scss" scoped>
</style>