import axios from "axios";
import appService from "../../services/appService";

export const backendGlobalState = {
    namespaced: true,
    state: {
        branches: [],
        branchShow: {},
    },
    getters: {
        branches: function (state) {
            return state.branches;
        },
        branchShow: function (state) {
            return state.branchShow;
        },
    },
    actions: {
        branches: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "admin/setting/branch";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit("branches", res.data.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        branchShow: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`admin/setting/branch/show/${payload}`).then((res) => {
                    context.commit("branchShow", res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        }
    },
    mutations: {
        branches: function (state, payload) {
            state.branches = payload;
        },
        branchShow: function (state, payload) {
            state.branchShow = payload;
        }
    },
};
