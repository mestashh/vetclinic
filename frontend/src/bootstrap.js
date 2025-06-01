import axios from 'axios';
window.axios = axios;

window.axios.defaults.withCredentials = true;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Obtain the CSRF cookie required by Sanctum
window.axios.get('/sanctum/csrf-cookie');