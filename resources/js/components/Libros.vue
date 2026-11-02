<template>
    <div >
        <h4>Test connexió - Llibres</h4>
        
        <div v-if="carregant">Carregant...</div>

        <div v-else-if="error" style="color: red;">
            Error: {{ error }}
        </div>

        <div v-else class="row q-col-gutter-md">
            <div v-for="libro in libros" :key="libro.id" class="col-12 col-sm-6 col-md-3">
                <q-card class="full-height column">
                    <q-img
                        :src="`/${libro.portada}`"
                        :alt="`Portada de ${libro.titulo}`"
                        :ratio="2/3"
                        fit="cover"
                    />
                    <q-card-section>
                        <div class="text-h6">{{ libro.titulo }}</div>
                        <div class="text-subtitle1">
                            Autor(s): 
                            <span v-for="(autor, index) in libro.autores" :key="autor.id">
                                {{ autor.nombre }} {{ autor.apellido_1 }} {{ autor.apellido_2 }}<span v-if="index < libro.autores.length - 1">, </span>
                            </span>
                        </div>
                    </q-card-section>
                    <q-card-actions class="q-mt-auto" align="right">
                        <q-btn flat icon="edit" />
                        <q-btn flat icon="delete" />
                        <q-btn color="primary" label="Veure detalls" icon="visibility" @click="obrirDetall(libro)" />
                    </q-card-actions>
                </q-card>
            </div>

            <q-dialog v-model="mostrarDetalls" persistent animate-show>
                <q-card v-if="llibreSeleccionat" style="width: 900px; max-width: 90vw;">
                    <div class="row no-wrap-sm">
                        <div class="col-12 col-sm-5">
                            <q-img
                                :src="`/${llibreSeleccionat.portada}`"
                                :alt="`Portada de ${llibreSeleccionat.titulo}`"
                                :ratio="2/3"
                                fit="cover"
                            />
                        </div>
                        <div class="col-12 col-sm-7 flex column justify-between">
                            <q-card-section class="row items-center q-pb-none">
                                <div class="text-h6">{{ llibreSeleccionat.titulo }}</div>
                                <q-space />
                                <q-btn icon="close" flat round dense @click="mostrarDetalls = false" />
                            </q-card-section>
                            <q-separator />
                            <q-card-section>
                                <div class="col">
                                    <div class="text-subtitle1 q-mb-md">
                                        Autor(s): 
                                        <span v-for="(autor, index) in llibreSeleccionat.autores" :key="autor.id">
                                            {{ autor.nombre }} {{ autor.apellido_1 }} {{ autor.apellido_2 }}<span v-if="index < llibreSeleccionat.autores.length - 1">, </span>
                                        </span>
                                    </div>
                                    <div class="text-subtitle2 q-mb-md">Pàgines: {{ llibreSeleccionat.paginas }}</div>
                                    <div class="text-subtitle2 q-mb-md">Sinopsis: {{ llibreSeleccionat.sinopsis }}</div>
                                    <div class="text-subtitle2 q-mb-md">Idioma: {{ idiomas.find(idioma => idioma.id === llibreSeleccionat.idioma_id)?.idioma }}</div>
                                    <div class="text-subtitle2 q-mb-md">Llegit? {{ llibreSeleccionat.leido }}</div>
                                    <div class="text-subtitle2 q-mb-md">Puntuació: {{ llibreSeleccionat.puntuacion }}</div>
                                    <div class="text-subtitle2">
                                        Categoria(s): 
                                        <span v-for="(categoria, index) in llibreSeleccionat.categorias" :key="categoria.id">
                                            {{ categoria.nombre }}<span v-if="index < llibreSeleccionat.categorias.length - 1">, </span>
                                        </span>
                                    </div>
                                </div>
                            </q-card-section>
                            <q-separator />
                            <q-card-actions align="right" class="text-primary">
                                <q-btn flat label="Editar llibre" icon="edit" v-close-popup />
                                <q-btn color="primary" label="Eliminar llibre" icon="delete" v-close-popup />
                            </q-card-actions>
                        </div>
                    </div>
                </q-card>
            </q-dialog>
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

//Varialbe reactiva per a controlar la visibilitat del q-dialog
const mostrarDetalls = ref(false)
const llibreSeleccionat = ref(null)

function obrirDetall(libro) {
    llibreSeleccionat.value = libro
    mostrarDetalls.value = true
}

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