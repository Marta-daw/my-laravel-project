<template>
    <q-layout view="hHh Lpr fFf">
        <q-header elevated>
            <q-toolbar>
                <q-btn
                    flat
                    @click="drawerLeft = !drawerLeft"
                    round
                    dense
                    icon="menu"
                />
                <q-avatar>
                    <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg" />
                </q-avatar>

                <q-toolbar-title>[Codi de Butxaca]</q-toolbar-title>

                <q-btn dense flat round icon="account_circle" class="q-mr-sm" />
            </q-toolbar>

            <q-tabs
                dense
                class="text-grey bg-grey-2"
                active-color="primary"
                indicator-color="primary"
            >
                <q-route-tab name="home" label="Home" to="/" />
                <q-route-tab name="test" label="Test Connexió" to="/Home2.vue" />
                <q-route-tab name="about" label="About me" to="/about" />
                <q-route-tab name="contact" label="Contact" to="/contact" />
            </q-tabs>
        </q-header>

        <q-drawer
            v-model="drawer"
            show-if-above
            :mini="miniState"
            @mouseenter="miniState = false"
            @mouseleave="miniState = true"
            :width="200"
            :breakpoint="500"
            bordered
            :class="$q.dark.isActive ? 'bg-grey-9' : 'bg-grey-3'"
        >
            <q-scroll-area class="fit" :horizontal-thumb-style="{ opacity: 0 }">
            <q-list padding>
                <q-item clickable v-ripple to="/biblioteca">
                <q-item-section avatar>
                    <q-icon name="dashboard" />
                </q-item-section>

                <q-item-section> Dashboard </q-item-section>
                </q-item>

                <q-item clickable v-ripple to="/llibres">
                    <q-item-section avatar>
                        <q-icon name="book" />
                    </q-item-section>
                    <q-item-section> Llibres </q-item-section>
                </q-item>

                <q-item active clickable v-ripple>
                <q-item-section avatar>
                    <q-icon name="add" />
                </q-item-section>

                <q-item-section> Afegir llibre </q-item-section>
                </q-item>

                <!-- <q-item clickable v-ripple>
                <q-item-section avatar>
                    <q-icon name="send" />
                </q-item-section>

                <q-item-section> Send </q-item-section>
                </q-item>

                <q-separator />

                <q-item clickable v-ripple>
                <q-item-section avatar>
                    <q-icon name="drafts" />
                </q-item-section>

                <q-item-section> Drafts </q-item-section>
                </q-item> -->
            </q-list>
            </q-scroll-area>
        </q-drawer>


        <q-page-container class="bg-fons-personalitzat">
            <router-view />
        </q-page-container>

        <Footer />
    </q-layout>
</template>

<script setup>
import { ref } from 'vue'
import Footer from '@/components/Footer.vue'

const drawerLeft = ref(false)
const drawer = ref(true)
const miniState = ref(false)
</script>