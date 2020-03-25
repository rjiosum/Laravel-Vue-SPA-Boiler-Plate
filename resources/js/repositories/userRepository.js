export default $axios => resource => ({

    async getUser() {
        return await $axios.get(`${resource}/profile`);
    },

    updateProfile(payload) {
        return $axios.patch(`${resource}/profile/update`, payload)
    },

    updatePassword(payload) {
        return $axios.patch(`${resource}/password/update`, payload)
    },

    updateAvatar(payload) {
        return $axios.post(`${resource}/avatar/update`, payload, { headers: { 'Content-Type': 'multipart/form-data' } })
    },

})
