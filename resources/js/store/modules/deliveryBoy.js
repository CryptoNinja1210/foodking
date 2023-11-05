import axios from "axios";
import appService from "../../services/appService";

export const deliveryBoy = {
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
        myOrders: [],
        orderPage: {},
        orderPagination: [],
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
        myOrders: function (state) {
            return state.myOrders;
        },
        orderPagination: function (state) {
            return state.orderPagination;
        },
        orderPage: function (state) {
            return state.orderPage;
        },
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "admin/delivery-boy";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit("lists", res.data.data);
                        context.commit("page", res.data.meta);
                        context.commit("pagination", res.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        save: function (context, payload) {
            return new Promise((resolve, reject) => {
                let method = axios.post;
                let url = "/admin/delivery-boy";
                if (this.state["deliveryBoy"].temp.isEditing) {
                    method = axios.put;
                    url = `/admin/delivery-boy/${this.state["deliveryBoy"].temp.temp_id}`;
                }
                method(url, payload.form).then((res) => {
                    context.dispatch("lists", payload.search).then().catch();
                    context.commit("reset");
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        edit: function (context, payload) {
            context.commit("temp", payload);
        },
        destroy: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.delete(`admin/delivery-boy/${payload.id}`).then((res) => {
                    context.dispatch("lists", payload.search).then().catch();
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        show: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`admin/delivery-boy/show/${payload}`).then((res) => {
                    context.commit("show", res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        reset: function (context) {
            context.commit("reset");
        },
        export: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = 'admin/delivery-boy/export';
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url, {responseType: 'blob'}).then((res) => {
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        changePassword: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post(`/admin/delivery-boy/change-password/${payload.id}`, payload.form).then((res) => {
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        changeImage: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post(`/admin/delivery-boy/change-image/${payload.id}`, payload.form, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                ).then((res) => {
                    context.commit("show", res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        myOrders: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = `admin/delivery-boy/my-order/${payload.id}`;
                if (payload.search) {
                    url = url + appService.requestHandler(payload.search);
                }
                axios.get(url).then((res) => {
                        if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                            context.commit("myOrders", res.data.data);
                            context.commit("orderPage", res.data.meta);
                            context.commit("orderPagination", res.data);
                        }

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
        myOrders: function (state, payload) {
            state.myOrders = payload;
        },
        orderPagination: function (state, payload) {
            state.orderPagination = payload;
        },
        orderPage: function (state, payload) {
            if (typeof payload !== "undefined" && payload !== null) {
                state.orderPage = {
                    from: payload.from,
                    to: payload.to,
                    total: payload.total,
                };
            }
        },
    },
};
