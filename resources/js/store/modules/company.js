import axios from 'axios'


export const company = {
    namespaced: true,
    state: {
        lists: [],
    },

    getters: {
        lists: function (state) {
            return state.lists;
        }
    },

    actions: {
        lists: function (context) {
            return new Promise((resolve, reject) => {
                axios.get('admin/setting/company').then((res) => {
                    context.commit('lists', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        save: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.put(`/admin/setting/company`, payload).then(res => {
                    context.commit('lists', payload);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        }
    },

    mutations: {
        lists: function (state, payload) {
            state.lists = payload
        }
    },
}
