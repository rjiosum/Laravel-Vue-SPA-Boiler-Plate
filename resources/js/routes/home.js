import {lazy} from './utility';
import checkUser from '../middleware/checkUser';

export default [
    {
        path: '/',
        name: 'home',
        component: lazy('home/Home'),
        meta: {
            middleware: [checkUser],
            title: 'Home Page'
        }
    },
    {
        path: '/article/:id',
        name: 'home.article',
        component: lazy('home/Article'),
        meta: {
            middleware: [checkUser]
        }
    }
]