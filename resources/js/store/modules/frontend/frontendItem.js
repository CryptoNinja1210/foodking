import axios from "axios";
import appService from "../../../services/appService";

export const frontendItem = {
    namespaced: true,
    state: {
        lists: [],
        featured: [],
        popular: {},
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        featured: function (state) {
            return state.featured;
        },
        popular: function (state) {
            return state.popular;
        },
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = 'frontend/item';
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if(typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit('lists', res.data.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        featured: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "frontend/item/featured-items";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit("featured", res.data.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        popular: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "frontend/item/popular-items";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit("popular", res.data.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
    },
    mutations: {
        lists: function (state, payload) {
            state.lists = payload
        },
        featured: function (state, payload) {
            state.featured = payload;
        },
        popular: function (state, payload) {
            state.popular = payload;
        }
    },
};
