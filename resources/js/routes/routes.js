import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store'
import home from './home';
import auth from "./auth";
import user from "./user";
import errors from "./errors";
import {pipeline} from "./utility";

Vue.use(VueRouter);


const routes = [
    ...home,
    ...auth,
    ...user,
    ...errors
];

const router = new VueRouter({
    routes,
    hashbang: false,
    mode: 'history'
});

router.beforeEach((to, from, next) => {
    if(to.meta.title){
        document.title = to.meta.title;
    }
    if (!to.meta.middleware) {
        return next();
    }
    const middleware = to.meta.middleware;
    const context = {to, from, next, store};

    return middleware[0]({
        ...context,
        next: pipeline(context, middleware, 1)
    });
});

export default router;


