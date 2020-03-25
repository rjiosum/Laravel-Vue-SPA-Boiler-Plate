import Vue from 'vue';
import VueRouter from 'vue-router';

import home from './home';
import auth from "./auth";
import errors from "./errors";

Vue.use(VueRouter);


const routes = [
    ...home,
    ...auth,
    ...errors
];

const router = new VueRouter({
    routes,
    hashbang: false,
    mode: 'history'
});


export default router;


