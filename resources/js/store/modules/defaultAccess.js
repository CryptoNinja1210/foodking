import axios from "axios";

export const defaultAccess = {
    namespaced: true,
    state: {
        show: [],
    },
    getters: {
        show: function (state) {
            return state.show;
        },
    },
    actions: {
        show: function (context) {
            return new Promise((resolve, reject) => {
                axios
                    .get("admin/default-access")
                    .then((res) => {
                        context.commit("show", res.data.data);
                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },
        saveOrUpdate: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios
                    .post("admin/default-access", payload)
                    .then((res) => {
                        context.commit("show", res.data.data);
                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },
    },
    mutations: {
        show: function (state, payload) {
            state.show = payload;
        },
    },
};
