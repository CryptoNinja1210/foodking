import axios from "axios";
import appService from "../../../services/appService";

export const frontendCoupon = {
    namespaced: true,
    state: {
        lists: [],
        show: {},
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        show: function (state) {
            return state.show;
        },
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "frontend/coupon";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit("lists", res.data.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        show: function (context, payload) {
            if(payload) {
                return new Promise((resolve, reject) => {
                    axios.get(`frontend/coupon/show/${payload}`).then((res) => {
                        context.commit("show", res.data.data);
                        resolve(res);
                    }).catch((err) => {
                        reject(err);
                    });
                });
            }
        },
        checking: function(context, payload) {
            if(payload) {
                return new Promise((resolve, reject) => {
                    axios.post(`frontend/coupon/coupon-checking`, payload).then((res) => {
                        resolve(res);
                    }).catch((err) => {
                        reject(err);
                    });
                });
            }
        }
    },
    mutations: {
        lists: function (state, payload) {
            state.lists = payload;
        },
        show: function (state, payload) {
            state.show = payload;
        }
    },
};
