import axios from 'axios'
import appService from "../../../services/appService";


export const frontendOrder = {
    namespaced: true,
    state: {
        activeOrder: [],
        previousOrder: [],
        show: {},
        orderItems: {},
        orderBranch: {},
        orderUser: {},
        page: {},
        pagination: [],
    },

    getters: {
        activeOrder: function (state) {
            return state.activeOrder;
        },
        previousOrder: function (state) {
            return state.previousOrder;
        },
        show: function (state) {
            return state.show;
        },
        orderItems: function (state) {
            return state.orderItems;
        },
        orderBranch: function (state) {
            return state.orderBranch;
        },
        orderUser: function (state) {
            return state.orderUser;
        },
        orderAddress: function (state) {
            return state.orderAddress;
        },
        orderDeliveryBoy: function (state) {
            return state.orderDeliveryBoy;
        },
        pagination: function (state) {
            return state.pagination;
        },
        page: function (state) {
            return state.page;
        },
    },
    actions: {
        activeOrder: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = 'frontend/order';
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit('activeOrder', res.data.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        previousOrder: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = 'frontend/order';
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit('previousOrder', res.data.data);
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
                axios.post("/frontend/order", payload).then((res) => {
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        show: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`frontend/order/show/${payload}`).then((res) => {
                    context.commit("show", res.data.data);
                    context.commit("orderItems", res.data.data.order_items);
                    context.commit("orderBranch", res.data.data.branch);
                    context.commit("orderUser", res.data.data.user);
                    context.commit("orderAddress", res.data.data.order_address);
                    context.commit("orderDeliveryBoy", res.data.data.delivery_boy);

                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        changeStatus: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post(`frontend/order/change-status/${payload.id}`,payload).then((res) => {
                    context.commit('show', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
    },
    mutations: {
        activeOrder: function (state, payload) {
            state.activeOrder = payload
        },
        previousOrder: function (state, payload) {
            state.previousOrder = payload
        },
        show: function (state, payload) {
            state.show = payload;
        },
        orderItems: function (state, payload) {
            state.orderItems = payload;
        },
        orderBranch: function (state, payload) {
            state.orderBranch = payload;
        },
        orderUser: function (state, payload) {
            state.orderUser = payload;
        },
        orderAddress: function (state, payload) {
            state.orderAddress = payload;
        },
        orderDeliveryBoy: function (state, payload) {
            state.orderDeliveryBoy = payload;
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
    },
}
