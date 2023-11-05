export const globalState = {
    namespaced: true,
    state: {
        lists: {},
    },
    getters: {
        lists: function (state) {
            return state.lists;
        }
    },
    actions: {
        init: function (context, payload) {
            return new Promise((resolve, reject) => {
                if(typeof payload === 'object') {
                    context.commit("init", payload);
                    resolve(payload);
                } else {
                    reject("object not found");
                }
            });
        },
        set: function (context, payload) {
            return new Promise((resolve, reject) => {
                if(typeof payload === 'object') {
                    context.commit("lists", payload);
                    resolve(payload);
                } else {
                    reject("object not found");
                }
            });
        }
    },
    mutations: {
        init: function(state, payload) {
            if (typeof payload === 'object') {
                for (const key in payload) {
                    if (payload.hasOwnProperty(key)) {
                        if(!state.lists[key]) {
                            state.lists[key] = payload[key];
                        }
                    }
                }
            }
        },
        lists: function (state, payload) {
            if (typeof payload === 'object') {
                for (const key in payload) {
                    if (payload.hasOwnProperty(key)) {
                        state.lists[key] = payload[key];
                    }
                }
            }
        }
    },
};
