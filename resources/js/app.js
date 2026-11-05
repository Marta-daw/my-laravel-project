import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { Quasar, Notify } from 'quasar';
import 'quasar/src/css/index.sass';
import '@quasar/extras/material-icons/material-icons.css';

import App from './App.vue';
import router from './router/Index.js';
import {api} from './boot/axios.js';
import charts from './boot/apexcharts.js';

const app = createApp(App);

app.config.globalProperties.$api = api;

app
    .use(Quasar, { plugins: { Notify, } })
    .use(createPinia())
    .use(router)
    .use(charts)
    .mount('#app');