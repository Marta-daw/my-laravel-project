<template>
  <q-layout view="lHh Lpr lff">
    <q-header elevated>
      <q-toolbar>
        <q-avatar>
          <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg" />
        </q-avatar>

        <q-toolbar-title>[Codi de Butxaca]</q-toolbar-title>

        <q-btn dense flat round icon="account_circle" @click="leftDrawerOpen = !leftDrawerOpen" class="q-mr-sm" />
      </q-toolbar>

      <q-tabs
        dense
        class="text-grey bg-grey-2"
        active-color="primary"
        indicator-color="primary"
      >
        <q-route-tab name="home" label="Home" to="/" />
        <q-route-tab name="test" label="Test Connexió" to="/Home2.vue" />
        <q-route-tab name="about" label="About" to="/about" />
        <q-route-tab name="contact" label="Contact" to="/contact" />
      </q-tabs>
    </q-header>

    <q-page-container class="bg-fons-personalitzat">
      <router-view />
    </q-page-container>

    <Footer />
  </q-layout>
</template>

<script setup>
import { ref } from 'vue';
import Footer from '@/components/Footer.vue'

const tab = ref('home');
</script>