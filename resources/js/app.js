import Vue from 'vue';

import vuetify from './plugins/vuetify';
import store from './store';
import router from './routes/routes';


window.EventBus = new Vue();
Vue.component('app-default', require('./components/layouts/AppDefault.vue').default);


const app = new Vue({
    el: '#app',
    vuetify,
    store,
    router
});
