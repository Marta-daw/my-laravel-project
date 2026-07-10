<template>
  <q-page class="flex flex-center column">
    <q-icon name="check_circle" color="positive" size="80px" />
    <div class="text-h4 q-mt-md">Laravel + Vue + Quasar</div>
    <div class="text-subtitle1 text-grey">Tot funciona correctament</div>
    <q-btn color="primary" label="Botó de prova" class="q-mt-lg" @click="count++" />
    <div class="q-mt-sm">Clics: {{ count }}</div>
  </q-page>
</template>

<script setup>
import { ref } from 'vue';
const count = ref(0);
</script>