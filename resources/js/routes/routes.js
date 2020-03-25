import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

function lazy (component) {
    return () => import(/* webpackChunkName: '' */ `@/components/views/${component}`).then(c => c.default || c)
}

const routes = [
    { path: '/', name: 'home', component: lazy('home/Home') },


    { path: '/404', name: 'not.found', component: lazy('errors/NotFound') },
    { path: '*', component: lazy('errors/NotFound') }
];

const router = new VueRouter({
    routes,
    hashbang: false,
    mode: 'history'
});



export default router;


