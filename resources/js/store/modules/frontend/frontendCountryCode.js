import axios from "axios";

export const frontendCountryCode = {
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
        show: function (context,payload) {
            return new Promise((resolve, reject) => {
                let url = `frontend/country-code/show/${payload}`;
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
        show: function (state, payload) {
            state.show = payload;
        },
    },
};
