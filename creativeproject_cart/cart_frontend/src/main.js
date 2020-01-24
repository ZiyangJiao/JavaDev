import Vue from 'vue'
import App from './App.vue'
import BootstrapVue from 'bootstrap-vue'
import axios from 'axios'

const http = axios.create({
    baseURL: process.env.BACKEND_URL ? process.env.BACKEND_URL : 'http://localhost:8888/cart_backend',
});

Vue.prototype.$http = http;

Vue.use(BootstrapVue);

Vue.config.productionTip = false;

axios.defaults.withCredentials = true;

new Vue({
    render: h => h(App),
}).$mount('#app');
