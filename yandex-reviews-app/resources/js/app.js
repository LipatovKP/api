import { createApp } from 'vue';
import axios from 'axios';
import router from './router';

axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

const app = createApp({ template: '<router-view />' });
app.use(router);
app.mount('#app');
