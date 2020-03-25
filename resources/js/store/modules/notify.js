import * as types from '../mutation-types';

const state = () => ({
    notice: {
        msg: '',
        color: '',
        icon: '',
        timeout: 6000
    },
    noticeColor: '',
    noticeIcon: ''
});

const getters = {
    getNotice(state) {
        return state.notice;
    }
};

const mutations = {
    [types.SET_NOTICE] (state, payload) {
        state.notice.msg = payload.msg;
        state.notice.color = payload.color;
        state.notice.icon = payload.icon;
        state.notice.timeout = (payload.timeout) ? payload.timeout : state.notice.timeout;
    }
};

const actions = {
    setNotice({ commit }, payload) {
        commit(types.SET_NOTICE, payload);
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}