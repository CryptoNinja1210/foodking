import _ from "lodash";
import activityEnum from "../../enums/modules/activityEnum";
import orderTypeEnum from "../../enums/modules/orderTypeEnum";


export const posCart = {
    namespaced: true,
    state: {
        lists: [],
        subtotal: 0,
        discount: 0,
        orderType: null
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        subtotal: function (state) {
            return state.subtotal;
        },
        discount: function (state) {
            return state.discount;
        },
        orderType: function (state) {
            return state.orderType;
        }
    },
    actions: {
        lists: function (context, payload) {
            context.commit("lists", payload);
            context.commit("subtotal");
        },
        quantity: function (context, payload) {
            context.commit("quantity", payload);
            context.commit("subtotal");
        },
        deleteCartItem: function (context, payload) {
            context.commit("deleteCartItem", payload);
            context.commit("subtotal");
            context.commit("discount",0);
        },
        discount: function (context, payload) {
            context.commit("discount", payload);
        },
        destroyDiscount: function (context) {
            context.commit('discount', 0);
        },
        resetCart: function (context) {
            context.commit('resetCart');
        },
    },
    mutations: {
        lists: function (state, payload) {
            if (payload.length > 0) {
                let isNew = false;
                let newChecker = [];
                let variationAndExtraChecker = [];
                _.forEach(payload, (pay) => {
                    if (state.lists.length === 0) {
                        isNew = true;
                    } else {
                        isNew = true;
                        _.forEach(state.lists, (list, listKey) => {
                            if (list.item_id === pay.item_id) {

                                if (state.lists[listKey].item_variations.variations !== "undefined") {
                                    if (Object.keys(state.lists[listKey].item_variations.variations).length !== 0) {
                                        _.forEach(state.lists[listKey].item_variations.variations, (variationId, variationKey) => {
                                            if (pay.item_variations.variations[variationKey] !== "undefined" && pay.item_variations.variations[variationKey] === variationId) {
                                                variationAndExtraChecker.push(true);
                                            } else {
                                                variationAndExtraChecker.push(false);
                                            }
                                        });
                                    }
                                }

                                if (pay.item_extras.extras.length !== 0 && state.lists[listKey].item_extras.extras.length !== 0) {
                                    _.forEach(pay.item_extras.extras, (payExtra) => {
                                        if (state.lists[listKey].item_extras.extras.includes(payExtra) && state.lists[listKey].item_extras.extras.length === pay.item_extras.extras.length) {
                                            variationAndExtraChecker.push(true);
                                        } else {
                                            variationAndExtraChecker.push(false);
                                        }
                                    });
                                } else {
                                    if (pay.item_extras.extras.length === state.lists[listKey].item_extras.extras.length) {
                                        variationAndExtraChecker.push(true);
                                    } else {
                                        variationAndExtraChecker.push(false);
                                    }
                                }

                                if (variationAndExtraChecker.includes(false)) {
                                    newChecker.push(false);
                                } else {
                                    newChecker.push(true);
                                    state.lists[listKey].quantity += pay.quantity;
                                }
                                variationAndExtraChecker = [];
                            } else {
                                newChecker.push(false);
                            }
                        });

                        _.forEach(newChecker, (check) => {
                            if (check) {
                                isNew = false;
                            }
                        });
                        newChecker = [];
                    }

                    if (isNew) {
                        state.lists.push({
                            discount: pay.discount,
                            image: pay.image,
                            instruction: pay.instruction,
                            item_extra_total: pay.item_extra_total,
                            item_extras: pay.item_extras,
                            item_id: pay.item_id,
                            item_variation_total: pay.item_variation_total,
                            item_variations: pay.item_variations,
                            name: pay.name,
                            currency_price: pay.currency_price,
                            convert_price: pay.convert_price,
                            quantity: pay.quantity
                        });
                        isNew = false;
                    }
                });
            }
        },
        subtotal: function (state) {
            if (state.lists.length > 0) {
                let subtotal = 0;
                _.forEach(state.lists, (list, listKey) => {
                    state.lists[listKey].total = ((list.convert_price + list.item_variation_total + list.item_extra_total) * list.quantity);
                    subtotal += state.lists[listKey].total;
                });
                state.subtotal = subtotal;
            } else {
                state.subtotal = 0;
            }
        },
        quantity: function (state, payload) {
            if (payload.status === "increment") {
                state.lists[payload.id].quantity++;
            } else if (payload.status === "decrement") {
                if (state.lists[payload.id].quantity === 1) {
                    state.lists.splice(payload.id, 1);
                    state.discount = 0;
                } else {
                    state.lists[payload.id].quantity--;
                }
            } else {
                state.lists[payload.id].quantity = payload.status;
            }
        },
        deleteCartItem: function (state, payload) {
            if (payload.status === "decrement") {
                state.lists.splice(payload.id,1);
            }
        },
        discount: function (state, payload) {
            state.discount = payload;
        },
        resetCart: function (state) {
            state.lists = [];
            state.subtotal = 0;
            state.discount = 0;
        }
    },
};
