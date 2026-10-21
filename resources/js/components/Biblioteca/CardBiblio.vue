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
            <div class="q-ma-sm text-h6">ccdwfweferij,gv erohtjeruvthvriomufiwucrtiwyutrythw</div>
            <div class="q-ma-sm text-h6">ccdwfweferij,gv erohtjeruvthvriomufiwucrtiwyutrythw</div>
            <div class="q-ma-sm text-h6">ccdwfweferij,gv erohtjeruvthvriomufiwucrtiwyutrythw</div>
        </q-card>
    </span>

    <span class="row q-col-gutter-sm q-mt-md chart-container">
        <div class="col-12 col-md-7">
            <q-card>
              <q-card-section class="text-h6 q-mb-none">Llibres per categories</q-card-section>
                <q-card-section class="flex-grow">
                  <div class="bar-card">
                    <Bar :data="categoriesData" :options="chartOptionsBar" />
                  </div>
              </q-card-section>  
            </q-card>
        </div>
        <div class="col-12 col-md-5">
            <q-card>
                <q-card-section class="text-h6 q-mb-none"> Estat de la lectura </q-card-section>
                <q-card-section>
                  <div class="doughnut-card">
                    <Doughnut :data="lecturaData" :options="chartOptions" />
                  </div>
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

const categorias = ref([]);

onMounted(async () => {
    try {
        const data = await getResumLectures();
        totalLlibres.value = data.data.total;
        llegits.value = data.data.leidos;
        noLlegits.value = data.data.noLeidos;
        enProceso.value = data.data.enProceso;

        categorias.value = data.data.categorias ?? [];

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
const categoriesData = computed(() => ({
  labels: categorias.value.map(c => c.nombre),
  datasets: [
    {
      label: 'Llibres per categoria',
      data: categorias.value.map(c => c.total_llibres),
      backgroundColor: ['#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f', '#edc949'],
      borderRadius: 6
    }
  ]
}));

const lecturaData = computed(() => ({
  labels: ['Llegits', 'No llegits', 'En procés'],
  datasets: [
    {
      data: [llegits.value, noLlegits.value, enProceso.value],
      backgroundColor: ['#21ba45', '#f2c037', '#3b82f6'],
      hoverOffset: 4
    }
  ]
}));

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

const chartOptionsBar = {
  responsive: true,
  maintainAspectRatio: true,
  indexAxis: 'y',
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
.chart-container {
  position: relative;
  height: 550px; /* Ajusta la altura según tus necesidades */
}

.doughnut-card {
  position: relative;
  width: 100%;
  height: 440px; /* Ajusta la altura según tus necesidades */
}

.bar-card {
  height: 440px; /* Ajusta la altura según tus necesidades */
}

/* Pantalles petites: mòbils */
@media (max-width: 599px) {  
    .chart-container {
        height: 350px;
    }

    .doughnut-card {
        height: 240px;
    }

    .bar-card {
        height: 240px;
    }
}

/* Pantalles mitjanes: tablets i portàtils petits */
@media (min-width: 600px) and (max-width: 1023px) {
    .chart-container {
        height: 410px;
    }

    .doughnut-card {
        height: 300px;
    }

    .bar-card {
        height: 300px;
    }
}

/* Pantalles grans */
@media (min-width: 1024px) {
    .chart-container {
        height: 470px;
    }

    .doughnut-card {
        height: 360px;
    }

    .bar-card {
        height: 360px;
    }
}
</style>