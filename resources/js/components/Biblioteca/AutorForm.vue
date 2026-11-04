<template>
    <q-form @submit.prevent="enviar">
        <q-input
            v-model="form.nombre"
            label="Nom"
            filled
            class="q-mb-sm"
        />

        <q-input
            v-model="form.apellido_1"
            label="Primer cognom"
            filled
            class="q-mb-sm"
        />

        <q-input
            v-model="form.apellido_2"
            label="Segon cognom"
            filled
            class="q-mb-sm"
        />

        <div class="row justify-end q-gutter-sm">
            <q-btn
                type="submit"
                color="primary"
                label="Crear autor"
            />
        </div>
    </q-form>
</template>

<script setup>
import { reactive } from 'vue'

const emit = defineEmits(['submit'])

const form = reactive({
    nombre: '',
    apellido_1: '',
    apellido_2: '',
})

function enviar() {
    emit('submit', {
        nombre: form.nombre,
        apellido_1: form.apellido_1,
        apellido_2: form.apellido_2 || null,
    })
}
</script>