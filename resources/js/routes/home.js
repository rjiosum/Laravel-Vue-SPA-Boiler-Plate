import {lazy} from './utility';

export default [
    {
        path: '/',
        name: 'home',
        component: lazy('home/Home')
    },
    {
        path: '/article/:id',
        name: 'home.article',
        component: lazy('home/Article')
    }
]