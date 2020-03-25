import {lazy} from './utility';

export default [
    {
        path: '/404',
        name: 'not.found',
        component: lazy('errors/NotFound')
    },
    {
        path: '*',
        component: lazy('errors/NotFound')
    }
]