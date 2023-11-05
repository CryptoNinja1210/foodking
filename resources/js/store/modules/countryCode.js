import axios from "axios";

export const countryCode = {
    namespaced: true,
    state: {
        lists: [],
        show: [],
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
        lists: function (context) {
            return new Promise((resolve, reject) => {
                axios
                    .get("admin/country-code")
                    .then((res) => {
                        context.commit("lists", res.data.data);
                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },
        show: function (context,payload) {
            return new Promise((resolve, reject) => {
                let url = `admin/country-code/show/${payload}`;
                axios
                    .get(url)
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
        lists: function (state, payload) {
            state.lists = payload;
        },
        show: function (state, payload) {
            state.show = payload;
        },
    },
};
