import { createApp } from 'vue';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import Toaster from '@meforma/vue-toaster';
import App from './App.vue';
import router from './router';

createApp(App)
    .use(router)
    .use(Toaster)
    .mount('#app');
