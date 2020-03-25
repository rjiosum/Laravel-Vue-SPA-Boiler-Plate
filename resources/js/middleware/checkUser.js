export default async function checkUser({next, store}) {
    if (!store.getters['auth/authenticated']) {
        try {
            await store.dispatch('auth/getUser');
        } catch (e) {}
    }
    return next();
}