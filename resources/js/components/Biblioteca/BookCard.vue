<template>
    <q-card class="full-height column">
        <q-img
            :src="llibre.portada_url || '/images/portada-placeholder.svg'"
            :alt="`Portada de ${llibre.titulo}`"
            :ratio="2 / 3"
            fit="cover"
        />
        <q-card-section>
            <div class="text-h6">{{ llibre.titulo }}</div>
            <div class="text-subtitle1 q-mb-md">
                Autor(s): 
                <span v-for="(autor, index) in llibre.autores" :key="autor.id">
                    {{ autor.nombre }} {{ autor.apellido_1 }} {{ autor.apellido_2 }}<span v-if="index < llibre.autores.length - 1">, </span>
                </span>
                <span v-if="!llibre.autores || llibre.autores.length === 0">Anònim</span>
            </div>
        </q-card-section>
        <q-card-actions class="q-mt-auto" align="right">
            <q-btn flat icon="edit" aria-label="Editar llibre" @click="$emit('edit', llibre)" />
            <q-btn flat icon="delete" aria-label="Eliminar llibre" @click="$emit('delete', llibre)" />
            <q-btn color="primary" icon="visibility" label="Veure detalls" @click="$emit('view', llibre)" />
        </q-card-actions>
    </q-card>
</template>

<script setup>
defineProps({
    llibre: {
        type: Object,
        required: true,
    },
})

defineEmits(['view', 'edit', 'delete'])
</script>