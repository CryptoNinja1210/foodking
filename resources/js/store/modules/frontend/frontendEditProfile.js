import axios from "axios";
import appService from "../../../services/appService";

export const frontendEditProfile = {
    namespaced: true,
    state: {
        profile : {},
    },
    getters: {
        profile: function (state) {
            return state.profile;
        },
    },
    actions: {
        updateProfile: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.put('/profile', payload).then(res => {
                    context.commit('profile', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        changeImage: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post(`/profile/change-image`, payload.form, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                ).then((res) => {
                    context.commit("profile", res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        changePassword: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.put(`/profile/change-password`,payload).then((res) => {
                    context.commit("profile", res.data.data);
                    resolve(res);
                })
                .catch((err) => {
                    reject(err);
                 });
            });
        },
    },
    mutations: {
        profile: function (state, payload) {
            state.profile = payload;
        },
    },
};
