import axios from "axios";

export const frontendSignup = {
    namespaced: true,
    state: {
        phone: {},
    },
    getters: {
        phone: function (state) {
            return state.phone;
        },
    },
    actions: {
        otp: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "auth/signup/otp";
                axios.post(url,payload).then((res) => {
                    context.commit("phone", payload);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        verify: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "auth/signup/verify";
                axios.post(url,payload).then((res) => {
                    context.commit("verify",true)
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        signup: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "auth/signup/register";
                axios.post(url,payload).then((res) => {
                    context.commit('reset');
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        reset: function (context) {
            context.commit('reset');
        },
    },
    mutations: {
        phone: function (state, payload) {
            state.phone.otp = payload;
        },
        verify: function (state, payload) {
            state.phone.status = payload;
        },
        reset: function(state) {
            state.phone = {};
        }
    },
};
