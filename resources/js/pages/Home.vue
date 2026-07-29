<template>
  <q-page class="flex column">
    <!-- <q-icon name="check_circle" color="positive" size="80px" />
    <div class="text-h4 q-mt-md">Laravel + Vue + Quasar</div>
    <div class="text-subtitle1 text-grey">Tot funciona correctament</div>
    <q-btn color="primary" label="Botó de prova" class="q-mt-lg" @click="count++" />
    <div class="q-mt-sm">Clics: {{ count }}</div> -->

    <div class="row col-12 q-ma-md">
      <div class="col-4">
        <Autors />
      </div>
      <div class="col-4 text-center">
        <Idiomas />
      </div>
      <div class="col-4">
        <Categorias />
      </div>
    </div>

    <div class="col-12 q-mx-lg">
        <Libros />
    </div>
    

  </q-page>
</template>

<script setup>
import { ref } from 'vue';
import Autors from '@/components/Autors.vue'
import Idiomas from '@/components/Idiomas.vue'
import Categorias from '@/components/Categorias.vue'
import Libros from '@/components/Libros.vue'

const count = ref(0);
</script>