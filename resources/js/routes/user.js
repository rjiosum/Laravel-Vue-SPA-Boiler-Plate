import {lazy} from './utility';

import auth from '../middleware/auth';
import checkUser from '../middleware/checkUser';

export default [
    {
        path: '/user',
        component: lazy('user/UserIndex'),
        children: [
            {path: '', redirect: {name: 'user.dashboard'}},
            {
                path: 'dashboard',
                name: 'user.dashboard',
                component: lazy('user/UserDashboard'),
                meta: {
                    middleware: [checkUser, auth],
                    title: 'Dashboard'
                }
            },
            {
                path: 'profile',
                name: 'user.profile',
                component: lazy('user/UserProfile'),
                meta: {
                    middleware: [checkUser, auth],
                    title: 'Update Profile'
                }
            },
            {
                path: 'password',
                name: 'user.password',
                component: lazy('user/UserPassword'),
                meta: {
                    middleware: [checkUser, auth],
                    title: 'Change Password'
                }
            },
            {
                path: 'avatar',
                name: 'user.avatar',
                component: lazy('user/UserAvatar'),
                meta: {
                    middleware: [checkUser, auth],
                    title: 'Change Avatar'
                }
            },

            {
                path: 'article/create',
                name: 'user.create.article',
                component: lazy('user/article/CreateArticle'),
                meta: {
                    middleware: [checkUser, auth],
                    title: 'Create Article'
                }
            },
            {
                path: 'article/:id?/edit',
                name: 'user.edit.article',
                component: lazy('user/article/EditArticle'),
                meta: {
                    middleware: [checkUser, auth],
                    title: 'Update Article'
                }
            },
            {
                path: 'article/:id?/view',
                name: 'user.view.article',
                component: lazy('user/article/ViewArticle'),
                meta: {
                    middleware: [checkUser, auth]
                }
            },
            {
                path: 'articles',
                name: 'user.articles',
                component: lazy('user/article/ListArticles'),
                meta: {
                    middleware: [checkUser, auth],
                    title: 'Articles'
                }
            },
        ]
    }
]