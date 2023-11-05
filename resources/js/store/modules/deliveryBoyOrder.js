import axios from "axios";
import appService from "../../services/appService";

export const deliveryBoyOrder = {
    namespaced: true,
    state: {
        show: {},
        temp: {
            temp_id: null,
            isEditing: false,
        },
        deliveredOrders: [],
        deliveredOrderPage: {},
        deliveredOrderPagination: [],

        deliveredOrderDetails: {},
        orderItems: {},
        orderBranch: {},
        orderUser: {},
        orderAddress: {},
    },
    getters: {
        show: function (state) {
            return state.show;
        },
        temp: function (state) {
            return state.temp;
        },
        deliveredOrders: function (state) {
            return state.deliveredOrders;
        },
        deliveredOrderPagination: function (state) {
            return state.deliveredOrderPagination;
        },
        deliveredOrderPage: function (state) {
            return state.deliveredOrderPage;
        },

        deliveredOrderDetails: function (state) {
            return state.deliveredOrderDetails;
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
    },
    actions: {
        deliveredOrders: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = `admin/delivery-boy/delivered-order/${payload.id}`;
                if (payload.search) {
                    url = url + appService.requestHandler(payload.search);
                }
                axios.get(url).then((res) => {
                        if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                            context.commit("deliveredOrders", res.data.data);
                            context.commit("deliveredOrderPage", res.data.meta);
                            context.commit("deliveredOrderPagination", res.data);
                        }

                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },

        deliveredOrderDetails: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`admin/delivery-boy/delivered-order/show/${payload.id}/${payload.orderId}`).then((res) => {
                    context.commit("deliveredOrderDetails", res.data.data);
                    context.commit("orderItems", res.data.data.order_items);
                    context.commit("orderBranch", res.data.data.branch);
                    context.commit("orderUser", res.data.data.user);
                    context.commit("orderAddress", res.data.data.order_address);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
    },
    mutations: {
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
        deliveredOrders: function (state, payload) {
            state.deliveredOrders = payload;
        },
        deliveredOrderPagination: function (state, payload) {
            state.deliveredOrderPagination = payload;
        },
        deliveredOrderPage: function (state, payload) {
            if (typeof payload !== "undefined" && payload !== null) {
                state.deliveredOrderPage = {
                    from: payload.from,
                    to: payload.to,
                    total: payload.total,
                };
            }
        },

        deliveredOrderDetails: function (state, payload) {
            state.deliveredOrderDetails = payload;
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
    },
};
