import { RepositoryFactory } from '@/repositories/RepositoryFactory';
const AuthRepository = RepositoryFactory.get('auth');
const UserRepository = RepositoryFactory.get('user');
import * as types from '../mutation-types';

const state ={
    user: null
};

const getters = {
    authenticated (state) {
        return !!state.user;
    },
    user (state) {
        return state.user;
    },
};

const mutations = {
    [types.LOGIN](state, user) {
        state.user = user;
    },
    [types.LOGOUT](state) {
        state.user = null;
    },
};

const actions = {
    login(context, credentials) {
        return new Promise((resolve, reject) => {
            AuthRepository.login(credentials)
                .then(response => {
                    if(!response.data.verify) {
                        context.commit(types.LOGIN, response.data.user);
                    }
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    logout(context) {
        return new Promise((resolve, reject) => {
            AuthRepository.logout()
                .then(response => {
                    context.commit(types.LOGOUT);
                    resolve(response)
                })
                .catch(error => {
                    reject(error)
                });
        });
    },
    register(context, data){
       return new Promise((resolve, reject)=>{
            AuthRepository.register(data)
                .then((response)=>{
                    if(!response.data.verify){
                        context.commit(types.LOGIN, response.data.user);
                    }
                     resolve(response);
                }).catch((error) => {
                    reject(error);
                });
        });
    },
    async getUser (context) {
        return await new Promise((resolve, reject)=>{
             UserRepository.getUser()
                .then((response)=>{
                    context.commit(types.LOGIN, response.data.user);
                    resolve(response);
                }).catch((error) => {
                    context.commit(types.LOGOUT);
                    reject(error);
            });
        });
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}