<template>
     <q-page class="q-pa-md">
        <q-banner v-if="error" class="bg-negative text-white q-mb-md">
            {{ error }}
        </q-banner>

        <q-spinner v-if="carregant" />

        <BookForm
            v-else
            :llibre="llibre"
            :es-edicio="esEdicio"
            @submit="desar"
        />
    </q-page>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import {
    createLlibre,
    getLlibreById,
    updateLlibre,
} from '@/services/Api.js'
import BookForm from '@/components/Biblioteca/BookForm.vue'

const route = useRoute()
const router = useRouter()
const llibre = ref(null)
const carregant = ref(false)
const error = ref(null)

const id = computed(() => route.params.id)
const esEdicio = computed(() => Boolean(id.value))

async function carregarLlibre() {
    if (!esEdicio.value) return

    carregant.value = true
    try {
        const response = await getLlibreById(id.value)
        llibre.value = response.data
    } catch (err) {
        error.value = err.response?.data?.message ?? err.message
    } finally {
        carregant.value = false
    }
}

async function desar(payload) {
    if (esEdicio.value) {
        await updateLlibre(id.value, payload)
    } else {
        await createLlibre(payload)
    }

    await router.push('/biblioteca/llibres')
}

onMounted(carregarLlibre)
</script>