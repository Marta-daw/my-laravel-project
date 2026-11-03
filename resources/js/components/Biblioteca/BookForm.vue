<template>
    <q-form @submit.prevent="enviar">
        <q-input v-model="form.titulo" label="Títol" filled />
        <q-input v-model.number="form.paginas" label="Pàgines" type="number" filled />
        <q-input v-model="form.sinopsis" label="Sinopsi" type="textarea" filled />

        <!-- Afegir aquí els q-select d'idioma, autors i categories
             utilitzant les opcions carregades per la pàgina. -->

        <q-btn
            class="q-mt-md"
            color="primary"
            type="submit"
            :label="props.esEdicio ? 'Actualitzar' : 'Crear'"
        />
    </q-form>
</template>

<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
    llibre: {
        type: Object,
        default: null,
    },
    esEdicio: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits(['submit'])

const form = reactive({
    titulo: '',
    paginas: null,
    sinopsis: '',
    idioma_id: null,
    leido: 'no',
    puntuacion: null,
    portada: '',
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
            portada: llibre.portada ?? '',
            autor_id: (llibre.autores ?? []).map((autor) => autor.id),
            categoria_id: (llibre.categorias ?? []).map((categoria) => categoria.id),
        })
    },
    { immediate: true },
)

function enviar() {
    emit('submit', {
        ...form,
        paginas: Number(form.paginas),
        idioma_id: Number(form.idioma_id),
        autor_id: form.autor_id.map(Number),
        categoria_id: form.categoria_id.map(Number),
    })
}
</script>