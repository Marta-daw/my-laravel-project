<template>
    <div >
        <h4>Test connexió - Llibres</h4>
        
        <div v-if="carregant">Carregant...</div>

        <div v-else-if="error" style="color: red;">
            Error: {{ error }}
        </div>

        <ul v-else>
            <li v-for="libro in libros" :key="libro.id">
                <div class="border q-mb-md q-pa-md">
                    <strong>{{ libro.titulo }}</strong>
                    <p>{{ libro.páginas }} pàgines</p>
                    <p>{{ libro.sinopsis }}</p>
                    <p>idioma: {{ idiomas.find(idioma => idioma.id === libro.idioma_id)?.idioma }}</p>
                    <p>leido? {{ libro.leido}}</p>
                    <p>Puntuación: {{ libro.puntuación }}</p>
                    <p> Autor(s): 
                        <span v-for="(autor, index) in libro.autors" :key="autor.id">
                            {{ autor.nombre }} {{ autor.apellido_1 }} {{ autor.apellido_2 }}<span v-if="index < libro.autors.length - 1">, </span>
                        </span>
                    </p>
                    <p> Categoria(s): 
                        <span v-for="(categoria, index) in libro.categorias" :key="categoria.id">
                            {{ categoria.nombre }}<span v-if="index < libro.categorias.length - 1">, </span>
                        </span>
                    </p>
                </div>
                
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { getLlibre, getIdioma, getCategoria } from '@/services/api.js'
import { api } from '@/boot/axios.js'

const libros = ref([])
const autors = ref([])
const idiomas = ref([])
const error = ref(null)
const carregant = ref(true)

onMounted(async () => {
    try {
        const response = await getLlibre()
        libros.value = response.data

        const idiomaResponse = await getIdioma()
        idiomas.value = idiomaResponse.data
    } catch (err) {
        error.value = err.message
        console.error('Error carregant llibres:', err)
    } finally {
        carregant.value = false
    }
})
</script>

<style scoped></style>