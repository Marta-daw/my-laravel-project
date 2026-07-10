import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { Quasar } from 'quasar';
import 'quasar/src/css/index.sass';
import '@quasar/extras/material-icons/material-icons.css';

import App from './App.vue';
import router from './router/Index.js';

createApp(App)
    .use(Quasar, { plugins: {} })
    .use(createPinia())
    .use(router)
    .mount('#app');