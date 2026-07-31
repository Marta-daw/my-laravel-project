<template>
    <div>
        <h4>Test connexió - Idiomes</h4>

        <div v-if="carregant">Carregant...</div>

        <div v-else-if="error" style="color: red;">
            Error: {{ error }}
        </div>

        <ul v-else>
            <li v-for="idioma in idiomas" :key="idioma.id">
                {{ idioma.idioma }}
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { getIdioma } from '@/services/api.js'
import { api } from '@/boot/axios.js'

const idiomas = ref([])
const error = ref(null)
const carregant = ref(true)

onMounted(async () => {
    try {
        const response = await getIdioma()
        idiomas.value = response.data
    } catch (err) {
        error.value = err.message
        console.error('Error carregant autors:', err)
    } finally {
        carregant.value = false
    }
})
</script>

<style scoped></style>