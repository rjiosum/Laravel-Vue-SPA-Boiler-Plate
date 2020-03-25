import store from "./store";
import router from './routes/routes';
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Accept'] = 'application/json';

// Add a request interceptor
window.axios.interceptors.request.use(function (config) {
    store.dispatch("errors/clearErrors");
    return config;
}, function (error) {
    return Promise.reject(error);
});

// Add a response interceptor
window.axios.interceptors.response.use(function (response) {
    return response;

}, async function (error) {
    if (error.response) {

        if (error.response.status === 404) {
            store.dispatch('notify/setNotice', {
                msg: error.response.data.message,
                color: "error",
                icon: "mdi-close-octagon"
            });
            router.replace({name: 'not.found'});

        } else if (error.response.status === 401) {
            try {
                await store.dispatch('auth/getUser');
            }catch (e) {
                store.dispatch('notify/setNotice', {
                    msg: "Your session has expired, please login again!!",
                    color: "error",
                    icon: "mdi-close-octagon"
                });
                router.replace({name: 'auth.login'});
            }

        } else {
            if (error.response.data.errors) {
                store.dispatch("errors/setErrors", error.response.data.errors);

            } else if (error.response.data.message) {
                store.dispatch("errors/setErrors", error.response.data.message);
                store.dispatch('notify/setNotice', {
                    msg: error.response.data.message,
                    color: "error",
                    icon: "mdi-close-octagon"
                });
            }
        }
    }
    return Promise.reject(error);
});