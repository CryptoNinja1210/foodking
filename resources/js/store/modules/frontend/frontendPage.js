import axios from "axios";
import appService from "../../../services/appService";

export const frontendPage = {
    namespaced: true,
    state: {
        lists: [],
        show: {},
        pageInfo: {}
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        show: function (state) {
            return state.show;
        },
        pageInfo: function(state) {
            return state.pageInfo;
        }
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "frontend/page";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    context.commit("lists", res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        show: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`frontend/page/show/${payload}`).then((res) => {
                    context.commit("show", res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        pageInfo: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`frontend/page/page-info/${payload}`).then((res) => {
                    context.commit("pageInfo", res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
    },
    mutations: {
        lists: function (state, payload) {
            state.lists = payload;
        },
        show: function (state, payload) {
            state.show = payload;
        },
        pageInfo: function (state, payload) {
            state.pageInfo = payload;
        },
    },
};
