<template>
  <q-page class="q-pa-none">
    <Hover class="full-width"/>
    
    <div class="q-pa-md">
      <h1 class="text-h4 q-mb-md">Projectes</h1>
      <!--Biblioteca - Targeta gran: ocupa les 12 columnes -->
      <Card :index="0" layout="horizontal" class="full-width"/>

      <!-- Tres targetes petites: cadascuna ocupa 4 columnes en pantalles mitjanes (12 / 3 = 4) -->
      <div class="row q-col-gutter-md">
        <div class="col-12 col-md-4 col-lg-4 q-mb-sm" v-for="n in 3" :key="n">
          <Card :index="n" layout="vertical"/>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { ref } from 'vue';
import Hover from '@/components/Hover.vue'
import Card from '@/components/Card.vue'
</script>

<style lang="scss" scoped>
</style>