<template>
    <q-dialog
        v-if="tipus === 'detalls'"
        :model-value="obert"
        @update:model-value="$emit('update:obert', $event)"
    >
       <q-card v-if="llibre" style="width: 900px; max-width: 90vw;">
            <div class="row no-wrap-sm">
                <div class="col-12 col-sm-5">
                    <q-img
                        :src="`/${llibre.portada}`"
                        :alt="`Portada de ${llibre.titulo}`"
                        :ratio="2/3"
                        fit="cover"
                    />
                </div>
                <div class="col-12 col-sm-7 flex column justify-between">
                    <q-card-section class="row items-center q-pb-none">
                        <div class="text-h6">{{ llibre.titulo }}</div>
                        <q-space />
                        <q-btn icon="close" flat round dense @click="$emit('update:obert', false)" />
                    </q-card-section>
                    <q-separator />
                    <q-card-section>
                        <div class="col">
                            <div class="text-subtitle1 q-mb-md">
                                Autor(s): 
                                <span v-for="(autor, index) in llibre.autores" :key="autor.id">
                                    {{ autor.nombre }} {{ autor.apellido_1 }} {{ autor.apellido_2 }}<span v-if="index < llibre.autores.length - 1">, </span>
                                </span>
                            </div>
                            <div class="text-subtitle2 q-mb-md">Pàgines: {{ llibre.paginas }}</div>
                            <div class="text-subtitle2 q-mb-md">Sinopsis: {{ llibre.sinopsis }}</div>
                            <div class="text-subtitle2 q-mb-md">Idioma: {{ idiomas.find(idioma => idioma.id === llibre.idioma_id)?.idioma }}</div>
                            <div class="text-subtitle2 q-mb-md">Llegit? {{ llibre.leido }}</div>
                            <div class="text-subtitle2 q-mb-md">Puntuació: {{ llibre.puntuacion }}</div>
                            <div class="text-subtitle2">
                                Categoria(s): 
                                <span v-for="(categoria, index) in llibre.categorias" :key="categoria.id">
                                    {{ categoria.nombre }}<span v-if="index < llibre.categorias.length - 1">, </span>
                                </span>
                            </div>
                        </div>
                    </q-card-section>
                    <q-separator />
                    <q-card-actions align="right" class="text-primary">
                        <q-btn flat label="Editar llibre" icon="edit" @click="$emit('edit', llibre)" />
                        <q-btn color="primary" label="Eliminar llibre" icon="delete" @click="$emit('delete', llibre)" />
                    </q-card-actions>
                </div>
            </div>
        </q-card>
    </q-dialog>

    <q-dialog
        v-else-if="tipus === 'eliminar'"
        :model-value="obert"
        @update:model-value="$emit('update:obert', $event)"
    >
        <q-card>
            <q-card-section>
                Vols eliminar aquest llibre?
            </q-card-section>
            <q-card-actions align="right">
                <q-btn flat label="Cancel·lar" @click="$emit('update:obert', false)" />
                <q-btn
                    color="negative"
                    label="Eliminar"
                    :loading="loading" 
                    @click="$emit('confirm')"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

<script setup>
defineProps({
    llibre: Object,
    obert: Boolean,
    tipus: String, // 'detalls' o 'eliminar'
    loading: Boolean,
    idiomas: Array,
})

defineEmits(['update:obert', 'edit', 'delete', 'confirm'])
</script>