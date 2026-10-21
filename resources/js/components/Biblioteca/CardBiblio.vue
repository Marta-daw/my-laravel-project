<template>
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
            <div class="text-h4 q-ma-sm">Descripció</div>
            <q-separator class="q-my-sm q-mx-sm bg-primary" />
            <div class="q-ma-sm text-h6">ccdwfweferij,gv erohtjeruvthvriomufiwucrtiwyutrythw</div>
        </q-card>
    </span>

    <span class="row q-col-gutter-sm q-mt-md">
        <div class="col-12 col-md-7">
            <q-card>
                    <q-card-section class="text-h6"> Llibres per categories</q-card-section>
                    <q-card-section>
                        <Bar :data="categoriesData" :options="chartOptions" />
                    </q-card-section>  
            </q-card>
        </div>
        <div class="col-12 col-md-5">
            <q-card>
                <q-card-section class="text-h6"> Estat de la lectura </q-card-section>
                <q-card-section>
                    <Doughnut :data="lecturaData" :options="chartOptions" />
                </q-card-section>
            </q-card>
        </div>
    </span>
</template>

<script setup>
import { onMounted, computed, ref } from 'vue';
import { getResumLectures } from '@/services/api';
import { Bar, Doughnut } from 'vue-chartjs';
import {
    ArcElement,
    BarElement,
    CategoryScale,
    Chart as ChartJS,
    Legend,
    LinearScale,
    Title,
    Tooltip,
} from 'chart.js';

ChartJS.register(
    ArcElement,
    BarElement,
    CategoryScale,
    Legend,
    LinearScale,
    Title,
    Tooltip
);

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

// Dades pels gràfics


// Opcions pròpies pel grafic de lecturaData
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
        },
        title: {
            display: false,
        },
    },
};
</script>

<style lang="scss" scoped>
</style>