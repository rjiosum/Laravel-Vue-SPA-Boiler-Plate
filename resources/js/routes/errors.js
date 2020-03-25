import {lazy} from './utility';
import checkUser from '../middleware/checkUser';

export default [
    {
        path: '/404',
        name: 'not.found',
        component: lazy('errors/NotFound'),
        meta: {
            middleware: [checkUser]
        }
    },
    {
        path: '*',
        component: lazy('errors/NotFound'),
        meta: {
            middleware: [checkUser]
        }
    }
]