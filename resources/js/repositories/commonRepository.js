export default $axios => resource => ({

   index(page) {
        return $axios.get(`${resource}?page=${page}`);
    },

    show(id) {
        return $axios.get(`${resource}/${id}`)
    },

    create(payload) {
        return $axios.post(`${resource}`, payload)
    },

    update(id, payload) {
        return $axios.patch(`${resource}/${id}`, payload)
    },

    delete(id) {
        return $axios.delete(`${resource}/${id}`)
    }
})
