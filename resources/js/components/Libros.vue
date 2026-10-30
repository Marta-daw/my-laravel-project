<template>
    <div >
        <h4>Test connexió - Llibres</h4>
        
        <div v-if="carregant">Carregant...</div>

        <div v-else-if="error" style="color: red;">
            Error: {{ error }}
        </div>

        <div v-else class="row q-col-gutter-md">
            <div v-for="libro in libros" :key="libro.id" class="col-12 col-sm-6 col-md-3">
                <q-card class="full-height">
                    <q-img
                        :src="`/${libro.portada}`"
                        :alt="`Portada de ${libro.titulo}`"
                        :ratio="2/3"
                        fit="cover"
                    />
                    <q-card-section>
                        <div class="text-h6">{{ libro.titulo }}</div>
                        <div class="text-subtitle2">Pàgines: {{ libro.paginas }}</div>
                        <div class="text-subtitle2">Sinopsis: {{ libro.sinopsis }}</div>
                        <div class="text-subtitle2">Idioma: {{ idiomas.find(idioma => idioma.id === libro.idioma_id)?.idioma }}</div>
                        <div class="text-subtitle2">Lleído? {{ libro.leido }}</div>
                        <div class="text-subtitle2">Puntuació: {{ libro.puntuacion }}</div>
                        <div class="text-subtitle1">
                            Autor(s): 
                            <span v-for="(autor, index) in libro.autores" :key="autor.id">
                                {{ autor.nombre }} {{ autor.apellido_1 }} {{ autor.apellido_2 }}<span v-if="index < libro.autores.length - 1">, </span>
                            </span>
                        </div>
                        <div class="text-subtitle2">
                            Categoria(s): 
                            <span v-for="(categoria, index) in libro.categorias" :key="categoria.id">
                                {{ categoria.nombre }}<span v-if="index < libro.categorias.length - 1">, </span>
                            </span>
                        </div>
                    </q-card-section>
                    <q-card-actions align="right">
                        <q-btn color="primary" label="Veure detalls" to="/detalls-llibre" />
                    </q-card-actions>
                </q-card>
            </div>
        </div>

        <button
            v-if="pagina < ultimaPagina"
            :disabled="cargarMas"
            @click="carregarPagina(pagina + 1)"
            class="q-my-md q-ml-md q-pa-md bg-primary text-white rounded"
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