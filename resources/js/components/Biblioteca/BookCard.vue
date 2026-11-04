<template>
    <q-card class="full-height column">
        <q-img
            :src="`/${llibre.portada}`"
            :alt="`Portada de ${llibre.titulo}`"
            :ratio="2 / 3"
            fit="cover"
        />
        <q-card-section>
            <div class="text-h6">{{ llibre.titulo }}</div>
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