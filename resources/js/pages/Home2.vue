<template>
    <q-page class="flex column">
        <q-card flat class="col-12 bg-fons-personalitzat" style="display:block">
            <div class="row col-12">
                <q-card-section class="col-2">
                    <div class="item-center">
                        <q-btn icon="arrow_left" color="positive" size="40px" to="/"></q-btn>
                    </div>
                </q-card-section>
                <q-card-section class="col-10">
                    <h3 class="text-center q-ma-md">Test de  connexió</h3>
                    <h5 class="text-center">Recuperació de dades que tenim a la BBDD dels llibres</h5>    
                </q-card-section>
            </div>
        </q-card>
        
       
        <q-separator class="q-my-md" color="secondary" size="8px" />
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
import Autors from '@/components/Autors.vue'
import Idiomas from '@/components/Idiomas.vue'
import Categorias from '@/components/Categorias.vue'
import Libros from '@/components/Libros.vue'
</script>

<style scoped></style>