<template>
    <div>
        <h4>Test connexió - Llibres</h4>
        
        <div v-if="carregant">Carregant...</div>

        <div v-else-if="error" style="color: red;">
            Error: {{ error }}
        </div>

        <ul v-else>
            <li v-for="libro in libros" :key="libro.id">
                <div class="border q-mb-md">
                    <strong>{{ libro.titulo }}</strong>
                    <p>{{ libro.páginas }} pàgines</p>
                    <p>{{ libro.sinopsis }}</p>
                    <p>{{ libro.idioma_id }} idioma id</p>
                    <p>leido? {{ libro.leido}}</p>
                    <p>Puntuación: {{ libro.puntuación }}</p>
                </div>
                
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api } from '@/boot/axios.js'

const libros = ref([])
const autors = ref([])
const error = ref(null)
const carregant = ref(true)

onMounted(async () => {
    try {
        const response = await api.get('/api/llibres')
        libros.value = response.data

        const autorResponse = await api.get('/api/autors')
        autors.value = autorResponse.data
    } catch (err) {
        error.value = err.message
        console.error('Error carregant llibres:', err)
    } finally {
        carregant.value = false
    }
})
</script>

<style scoped></style>