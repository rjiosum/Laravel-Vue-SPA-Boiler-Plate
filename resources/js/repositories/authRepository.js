export default $axios => resource => ({

    login(payload) {
        return $axios.post(`${resource}/login`, payload);
    },

    logout() {
        return $axios.post(`${resource}/logout`);
    },

    register(payload) {
        return $axios.post(`${resource}/register`, payload);
    },

    verifyEmailResend(payload) {
        return $axios.post(`${resource}/email/resend`, payload);
    },

    verifyEmail(id, hash, query) {
        return $axios.get(`${resource}/email/verify/${id}/${hash}?${query}`);
    },

    sendResetPasswordLinkEmail(payload){
        return $axios.post(`${resource}/password/email`, payload);
    },

    resetPassword(payload){
        return $axios.post(`${resource}/password/reset`, payload);
    }
})
