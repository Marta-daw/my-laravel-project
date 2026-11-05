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
            :idiomas="idiomas"
            :autors="autors"
            :categorias="categorias"
            @submit="desar"
        />
    </q-page>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { Notify } from 'quasar'
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
const desant = ref(false)

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
    desant.value = true
    error.value = null

    try {
        if (esEdicio.value) {
            await updateLlibre(id.value, payload)

            Notify.create({
                type: 'positive',
                message: 'Llibre actualitzat correctament',
            })
        } else {
            await createLlibre(payload)

            Notify.create({
                type: 'positive',
                message: 'Llibre creat correctament',
            })
        }

        await router.push('/llibres')
    } catch (err) {
        if (err.response?.status === 422) {
            error.value = Object.values(err.response.data.errors ?? {})
                .flat()
                .join(' ')

            return
        }

        error.value = err.response?.data?.message
            ?? 'No s’ha pogut desar el llibre.'

        Notify.create({
            type: 'negative',
            message: error.value,
        })
    } finally {
        desant.value = false
    }
}

onMounted(carregarLlibre)
</script>