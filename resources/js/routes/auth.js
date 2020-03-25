import {lazy} from './utility';

export default [
    {
        path: '/register',
        name: 'auth.register',
        component: lazy('auth/Register')
    },
    {
        path: '/login',
        name: 'auth.login',
        component: lazy('auth/Login')
    },
    {
        path: '/logout',
        name: 'auth.logout',
        component: lazy('auth/Logout')
    }
]