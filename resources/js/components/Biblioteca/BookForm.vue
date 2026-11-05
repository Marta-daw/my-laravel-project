<template>
    <h1 class="text-h4 q-mb-md text-bold">{{ props.esEdicio ? 'Editar llibre' : 'Crear llibre' }}</h1>
    <q-form @submit.prevent="enviar">
        <q-input v-model="form.titulo" label="Títol" filled class="q-mb-sm" />
        <q-input v-model="form.sinopsis" label="Sinopsi" type="textarea" filled class="q-mb-sm"/>
        <div class="row q-col-gutter-md q-mb-sm">
            <div class="col-4">
                <q-input v-model.number="form.paginas" label="Pàgines" type="number" filled />
            </div>
            <div class="col-4">
                <q-select
                    v-model="form.idioma_id"
                    :options="idiomas"
                    option-label="idioma"
                    option-value="id"
                    label="Idioma"
                    emit-value
                    map-options
                    filled
                />
            </div>
            <div class="col-4">
                <q-select
                    v-model="form.categoria_id"
                    :options="categorias"
                    option-label="nombre"
                    option-value="id"
                    label="Categories"
                    multiple
                    emit-value
                    map-options
                    filled
                />
            </div>
        </div>

        <div class="row q-col-gutter-md q-mb-sm">
            <div class="col-10">
                <q-select
                    v-model="form.autor_id"
                    :options="autors"
                    option-label="nom_complet"
                    option-value="id"
                    multiple
                    emit-value
                    map-options
                    label="Autors"
                    filled
                />
            </div>

            <div class="col-2 flex">
                <q-btn
                    class="full-width"
                    label="Nou autor"
                    icon="add"
                    @click="mostrarAutorDialog = true"
                />
            </div>
        </div>
        
        <div class="row q-col-gutter-md">
            <div class="col-4">
                <q-select
                    v-model="form.leido"
                    :options="[
                        { label: 'Sí', value: 'si' },
                        { label: 'No', value: 'no' },
                        { label: 'En procés', value: 'en_proceso' },
                    ]"
                    label="Llibre llegit?"
                    emit-value
                    map-options
                    filled
                />
            </div>
            <div class="col-4">
                <q-input v-model.number="form.puntuacion" label="Puntuació (1-5)" type="number" filled />
            </div>
            <div class="col-4">
                <q-img
                    v-if="portadaActual"
                    :src="`/storage/${portadaActual}`"
                    alt="Portada actual"
                    style="max-width: 150px"
                />
                <!-- <q-input v-model="form.portada" label="Portada (URL)" filled /> -->
                <q-file v-model="form.portada" label="Portada" filled counter :max-files="1" accept=".jpg, .jpeg, .png, .avif, .webp">
                    <!-- Icona de clip opcional a l'esquerra -->
                    <template v-slot:prepend>
                        <q-icon name="attach_file" />
                    </template>
                </q-file>
            </div>
        </div>

        <q-btn
            class="q-mt-md"
            color="primary"
            type="submit"
            :label="props.esEdicio ? 'Actualitzar' : 'Crear'"
        />
    </q-form>

    <AutorDialog
        v-model="mostrarAutorDialog"
        @autor-creat="afegirAutor"
    />
</template>

<script setup>
import { reactive, watch, ref, onMounted } from 'vue'
import { getIdioma, getAutor, getCategoria, crearAutor } from '@/services/Api.js'

import AutorDialog from '@/components/Biblioteca/AutorDialog.vue'

const mostrarAutorDialog = ref(false)

const idiomas = ref([])
const autors = ref([])
const categorias = ref([])

const portadaActual = ref(null)

const props = defineProps({
    llibre: {
        type: Object,
        default: null,
    },
    esEdicio: {
        type: Boolean,
        default: false,
    },
    idiomas: { type: Array, default: () => [] },
    autors: { type: Array, default: () => [] },
    categorias: { type: Array, default: () => [] },
})

const emit = defineEmits(['submit'])

const form = reactive({
    titulo: '',
    paginas: null,
    sinopsis: '',
    idioma_id: null,
    leido: 'no',
    puntuacion: null,
    portada: null,
    autor_id: [],
    categoria_id: [],
})

watch(
    () => props.llibre,
    (llibre) => {
        if (!llibre) return

       Object.assign(form, {
            titulo: llibre.titulo ?? '',
            paginas: llibre.paginas ?? null,
            sinopsis: llibre.sinopsis ?? '',
            idioma_id: llibre.idioma_id ?? llibre.idioma?.id ?? null,
            leido: llibre.leido ?? 'no',
            puntuacion: llibre.puntuacion ?? null,
            portada: null,
            autor_id: (llibre.autores ?? []).map((autor) => autor.id),
            categoria_id: (llibre.categorias ?? []).map((categoria) => categoria.id),
        })

        portadaActual.value = llibre.portada ?? null
    },
    { immediate: true },
)

async function carregarOpcions() {
    try {
        const [idiomaResponse, autorResponse, categoriaResponse] = await Promise.all([
            getIdioma(),
            getAutor(),
            getCategoria(),
        ])

        idiomas.value = idiomaResponse.data
        autors.value = autorResponse.data.map((autor)=>({
            ...autor,
            nom_complet: [
                autor.nombre,
                autor.apellido_1,
                autor.apellido_2,
            ].filter(Boolean).join(' '),
        }))
        categorias.value = categoriaResponse.data
    } catch (error) {
        console.error('Error carregant les opcions:', error)
    }
}

function enviar() {
    const data = new FormData()

    data.append('titulo', form.titulo)
    data.append('paginas', String(Number(form.paginas)))
    data.append('sinopsis', form.sinopsis || '')
    data.append('idioma_id', String(Number(form.idioma_id)))
    data.append('leido', form.leido)
    data.append('puntuacion', form.puntuacion === null ? '' : String(Number(form.puntuacion)))
    form.autor_id.forEach(id => data.append('autor_id[]', String(Number(id))))
    form.categoria_id.forEach(id => data.append('categoria_id[]', String(Number(id))))

    if (form.portada instanceof File) {
        data.append('portada', form.portada)
    }

    emit('submit', data)
}

async function afegirAutor(autor) {
    try {
        const response = await createAutor(autor)
        const autorCreat = response.data

        const autorNou = {
            ...autorCreat,
            nom_complet: [
                autorCreat.nombre,
                autorCreat.apellido_1,
                autorCreat.apellido_2,
            ].filter(Boolean).join(' '),
        }

        autors.value.push(autorNou)
        form.autor_id.push(autorNou.id)

        mostrarAutorDialog.value = false
    } catch (error) {
        console.error('Error creant l’autor:', error)
    }
}

onMounted(carregarOpcions)
</script>