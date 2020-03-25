import {lazy} from './utility';
import guest from '../middleware/guest';
import auth from '../middleware/auth';
import checkUser from '../middleware/checkUser';

export default [
    {
        path: '/register',
        name: 'auth.register',
        component: lazy('auth/Register'),
        meta: {
            middleware: [guest]
        }
    },
    {
        path: '/login',
        name: 'auth.login',
        component: lazy('auth/Login'),
        meta: {
            middleware: [guest]
        }
    },
    {
        path: '/logout',
        name: 'auth.logout',
        component: lazy('auth/Logout'),
        meta: {
            middleware: [checkUser, auth]
        }
    },
    {
        path: '/password/email',
        name: 'password.reset.email',
        component: lazy('auth/password/PasswordResetEmail'),
        meta: {
            middleware: [checkUser, guest]
        }
    },
    {
        path: '/password/reset/:token',
        name: 'password.reset',
        component: lazy('auth/password/PasswordReset'),
        meta: {
            middleware: [checkUser, guest]
        }
    },

    {
        path: '/email/verify/:id/:hash',
        name: 'verification.verify',
        component: lazy('auth/verification/VerifyEmail'),
        meta: {
            middleware: [checkUser, guest]
        }
    },
    {
        path: '/email/resend',
        name: 'verification.resend',
        component: lazy('auth/verification/VerifyEmailResend'),
        meta: {
            middleware: [checkUser, guest]
        }
    },


]