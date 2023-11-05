import axios from "axios";
import appService from "../../services/appService";

export const language = {
    namespaced: true,
    state: {
        lists: [],
        page: {},
        pagination: [],
        show: {},
        temp: {
            temp_id: null,
            isEditing: false,
        },
        fileList: [],
        fileText: {}
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        pagination: function (state) {
            return state.pagination;
        },
        page: function (state) {
            return state.page;
        },
        show: function (state) {
            return state.show;
        },
        temp: function (state) {
            return state.temp;
        },
        fileList: function (state) {
            return state.fileList;
        },
        fileText: function (state) {
            return state.fileText;
        }
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "admin/setting/language";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios
                    .get(url)
                    .then((res) => {
                        if (
                            typeof payload.vuex === "undefined" ||
                            payload.vuex === true
                        ) {
                            context.commit("lists", res.data.data);
                            context.commit("page", res.data.meta);
                            context.commit("pagination", res.data);
                        }
                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },
        save: function (context, payload) {
            return new Promise((resolve, reject) => {
                let method = axios.post;
                let url = "/admin/setting/language";
                if (this.state["language"].temp.isEditing) {
                    method = axios.post;
                    url = `/admin/setting/language/update/${this.state["language"].temp.temp_id}`;
                }
                method(url, payload.form)
                    .then((res) => {
                        context.dispatch("lists", payload.search).then().catch();
                        context.commit("reset");
                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },
        edit: function (context, payload) {
            context.commit("temp", payload);
        },
        destroy: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`admin/setting/language/${payload.id}`)
                    .then((res) => {
                        context
                            .dispatch("lists", payload.search)
                            .then()
                            .catch();
                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },
        show: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`admin/setting/language/show/${payload}`).then((res) => {
                    context.commit("show", res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        fileList: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = `/admin/setting/language/file-list/${payload}`;
                axios.get(url).then((res) => {
                    context.commit("fileList", res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        fileText: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = `/admin/setting/language/file-text`;
                axios.post(url, payload).then((res) => {
                    context.commit("fileText", res.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        fileStore: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = `/admin/setting/language/file-text/store`;
                axios.post(url, payload).then((res) => {
                    context.commit("resetFileText");
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        reset: function (context) {
            context.commit("reset");
        },
    },
    mutations: {
        lists: function (state, payload) {
            state.lists = payload;
        },
        pagination: function (state, payload) {
            state.pagination = payload;
        },
        page: function (state, payload) {
            if (typeof payload !== "undefined" && payload !== null) {
                state.page = {
                    from: payload.from,
                    to: payload.to,
                    total: payload.total,
                };
            }
        },
        show: function (state, payload) {
            state.show = payload;
        },
        temp: function (state, payload) {
            state.temp.temp_id = payload;
            state.temp.isEditing = true;
        },
        reset: function (state) {
            state.temp.temp_id = null;
            state.temp.isEditing = false;
        },
        fileList: function (state, payload) {
            state.fileList = payload;
        },
        fileText: function (state, payload) {
            state.fileText = payload;
        },
        resetFileText: function (state, payload) {
            state.fileText = {};
        }
    }
};
