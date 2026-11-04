<template>
    <q-dialog
        :model-value="modelValue"
        persistent
        @update:model-value="actualitzarObert"
    >
        <q-card style="min-width: 350px">
            <q-card-section>
                <div class="text-h6">Nou autor</div>
            </q-card-section>

            <q-card-section>
                <AutorForm @submit="crearAutor" />
            </q-card-section>

            <q-card-actions align="right">
                <q-btn
                    flat
                    label="Cancel·lar"
                    color="primary"
                    @click="tancar"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

<script setup>
import AutorForm from './AutorForm.vue'

defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits([
    'update:modelValue',
    'autor-creat',
])

function actualitzarObert(valor) {
    emit('update:modelValue', valor)
}

function crearAutor(autor) {
    emit('autor-creat', autor)
}

function tancar() {
    emit('update:modelValue', false)
}
</script>