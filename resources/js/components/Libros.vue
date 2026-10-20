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
                    <p>{{ libro.paginas }} pàgines</p>
                    <p>{{ libro.sinopsis }}</p>
                    <p>idioma: {{ idiomas.find(idioma => idioma.id === libro.idioma_id)?.idioma }}</p>
                    <p>leido? {{ libro.leido}}</p>
                    <p>Puntuación: {{ libro.puntuacion }}</p>
                    <p> Autor(s): 
                        <span v-for="(autor, index) in libro.autores" :key="autor.id">
                            {{ autor.nombre }} {{ autor.apellido_1 }} {{ autor.apellido_2 }}<span v-if="index < libro.autores.length - 1">, </span>
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

        <button
            v-if="pagina < ultimaPagina"
            :disabled="cargarMas"
            @click="carregarPagina(pagina + 1)"
            class="q-mb-md q-pa-md bg-primary text-white rounded"
        >
            {{ cargarMas ? 'Carregant...' : 'Carrega més llibres' }}
        </button>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { getLlibres, getIdioma, getAutor, getCategoria } from '@/services/api.js'
//import { api } from '@/boot/axios.js'

const libros = ref([])
const autors = ref([])
const idiomas = ref([])
const categorias = ref([])
const error = ref(null)
const carregant = ref(true)

//S'afegeix aquesta part per a poder donar resposta a la paginació que s'ha establert dins del backend, concretament
//a dins de 'LlibreController',ja que si no es fa així, només es recuperen els 10 primers llibres de la BBDD.
const pagina = ref(1)
const ultimaPagina = ref(1)
const cargarMas = ref(false)

async function carregarPagina(page, substituir = false) {
    cargarMas.value = true

    try {
        const response = await getLlibres({
            page,
            per_page: 10,
            sort: 'titulo',
        })

        const llibresNous = response.data.data
        libros.value = substituir
            ? llibresNous
            : [...libros.value, ...llibresNous]
        pagina.value = response.data.current_page
        ultimaPagina.value = response.data.last_page
    } finally {
        cargarMas.value = false
    }
}

onMounted(async () => {
    try {
        const [llibresResponse, idiomaResponse, autorResponse, categoriaResponse] = await Promise.all([
            getLlibres(),
            getIdioma(),
            getAutor(),
            getCategoria()
        ])
        
        libros.value = llibresResponse.data.data
        idiomas.value = idiomaResponse.data
        autors.value = autorResponse.data
        categorias.value = categoriaResponse.data

    } catch (err) {
        error.value = err.response?.data?.message ?? err.message
        console.error('Error carregant llibres:', err)
    } finally {
        carregant.value = false
    }
})

// Afegim aquesta crida a la funció carregarPagina dins del onMounted per a
// que es carregui la primera pàgina de llibres quan el component es monta.
onMounted(() => carregarPagina(1, true))

</script>

<style scoped></style>