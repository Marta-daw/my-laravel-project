<template>
    <!-- Layout Horizontal: imatge esquerra, text dreta-->
    <q-card v-if="layout === 'horizontal'" class="row col-12 q-my-sm" style="overflow: hidden;">
        <div class="col-4">
            <q-img :src="item.img" alt="Card Image" class="full-height"/>
        </div>
        <div class="col-8 column">
            <q-card-section class="q-py-none">
                <h1> {{ item.title }} </h1>
            </q-card-section>
            <q-card-section class="col-grow">
                <p> {{ item.content }} </p>
            </q-card-section>
            <q-card-actions align="right" class="q-pb-sm q-pr-sm">
                <q-btn label="Accedir" color="primary" class="item-end" to="/biblioteca"/>
            </q-card-actions>
        </div>
    </q-card>

    <!-- Layout Vertical: imatge a dalt, text a baix -->
    <q-card v-else class="q-ma-sm" style="overflow: hidden;">
        <div class="q-mb-sm">
            <q-img :src="item.img" :ratio="16/9"/>
        </div>
        <q-card-section>
            <h1>{{ item.title }}</h1>
        </q-card-section>
        <q-card-section>
            <p>{{ item.content }}</p>
        </q-card-section>
    </q-card>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    index: {
        type: Number,
        required: true
    },
    layout: {
        type: String,
        default: 'vertical'
    }
});

const dades = [
    { img: 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80', title: 'Biblioteca', content: 'Projecte on tenim una llista de llibres que tinc a la meva biblioteca i on també podrem afegir-ne conforme es descobreixin nous títols, editar-los i, si fos necessari, eliminar-los.' },
    { img: '/workinprogress.avif', title: 'Work in Progress', content: 'Projectes en curs de desenvolupament.' },
    { img: '/workinprogress.avif', title: 'Work in Progress', content: 'Projectes en curs de desenvolupament.' },
    { img: '/workinprogress.avif', title: 'Work in Progress', content: 'Projectes en curs de desenvolupament.' },
]

const item = computed(() => dades[props.index]);

</script>

<style lang="scss" scoped>
</style>