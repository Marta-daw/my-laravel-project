<template>
    <div >
        <h4>Test connexió - Llibres</h4>
        
        <div v-if="carregant">Carregant...</div>

        <div v-else-if="error" style="color: red;">
            Error: {{ error }}
        </div>

        <div v-else class="row q-col-gutter-md">
            <div v-for="libro in libros" :key="libro.id" class="col-12 col-sm-6 col-md-3">
                <BookCard
                    :llibre="libro"
                    @view="obrirDetall"
                    @edit="editarLlibre"
                    @delete="demanarEliminacio"
                />
            </div>
            
            <BookDialogs
                v-model:obert="dialogObert"
                :tipus="tipusDialog"
                :llibre="llibreSeleccionat"
                :loading="eliminant"
                :idiomas="idiomas"
                @edit="editarLlibre"
                @delete="demanarEliminacio"
                @confirm="confirmarEliminacio"
            />
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
import { useRouter } from 'vue-router'
import { getLlibres, getIdioma, getAutor, getCategoria, deleteLlibre } from '@/services/Api.js'
import BookCard from '@/components/Biblioteca/BookCard.vue'
import BookDialogs from '@/components/Biblioteca/BookDialogs.vue'
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
const llibreSeleccionat = ref(null)
const dialogObert = ref(false)
const tipusDialog = ref(null)

// Variables pel formulari de edició de llibres i eliminar un llibre
const router = useRouter()
const llibrePerEliminar = ref(null)
const eliminant = ref(false)

// Funció per a obrir el pop up amb els detalls del llibre seleccionat
function obrirDetall(llibre) {
    llibreSeleccionat.value = llibre
    tipusDialog.value = 'detalls'
    dialogObert.value = true
}

//Funcions per a editar un llibre existent, demanar si es vol eliminar i confirmar que s'elimina un llibre
function editarLlibre(llibre) {
    router.push(`/biblioteca/llibres/${llibre.id}/editar`)
}

function demanarEliminacio(llibre) {
    llibrePerEliminar.value = llibre
    tipusDialog.value = 'eliminar'
    dialogObert.value = true
}

async function confirmarEliminacio() {
    if (!llibrePerEliminar.value) return

    eliminant.value = true
    try {
        await deleteLlibre(llibrePerEliminar.value.id)
        libros.value = libros.value.filter(
            (llibre) => llibre.id !== llibrePerEliminar.value.id,
        )
        dialogObert.value = false
    } finally {
        eliminant.value = false
    }
}

// Funció per a carregar més llibres un cop hem arribat al final
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