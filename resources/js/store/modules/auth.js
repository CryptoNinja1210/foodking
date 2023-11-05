import axios from 'axios'


export const auth = {
    state: {
        authStatus: false,
        authToken: null,
        authBranchId: '',
        authInfo: {},
        authMenu: [],
        resetInfo: {
            email: null
        },
        authPermission: {},
        authDefaultPermission: {}
    },
    getters: {
        authStatus: function (state) {
            return state.authStatus;
        },
        authToken: function (state) {
            return state.authToken;
        },
        authBranchId: function (state) {
            return state.authBranchId;
        },
        authInfo: function (state) {
            return state.authInfo;
        },
        authMenu: function (state) {
            return state.authMenu;
        },
        authPermission: function (state) {
            return state.authPermission;
        },
        authDefaultPermission: function (state) {
            return state.authDefaultPermission;
        },
        resetInfo: function (state) {
            return state.resetInfo;
        }
    },
    actions: {
        login: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post('auth/login', payload).then((res) => {
                    context.commit('authLogin', res.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        logout: function (context) {
            return new Promise((resolve, reject) => {
                axios.post('auth/logout').then((res) => {
                    context.commit('authLogout');
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        forgetPassword: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post('auth/forgot-password', payload).then((res) => {
                    context.commit('forgetPassword', payload);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        verifyCode: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post('auth/forgot-password/verify-code', payload).then((res) => {
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        resetPassword: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post('auth/forgot-password/reset-password', payload).then((res) => {
                    context.commit('resetPassword', res.data.token);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        updateAuthInfo: function (context, payload) {
            return new Promise((resolve, reject) => {
                if (context.state.authInfo.id === payload.id) {
                    context.commit('authInfo', payload);
                    resolve(payload);
                } else {
                    reject('user data not match');
                }
            });
        },
        GuestLoginVerify: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post('auth/guest-signup/verify', payload).then((res) => {
                    context.commit('authLogin', res.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        loginDataReset: function (context) {
            context.commit('authLogout');
        }
    },
    mutations: {
        authLogin: function (state, payload) {
            state.authStatus = true;
            state.authToken = payload.token;
            state.authBranchId = payload.branch_id;
            state.authInfo = payload.user;
            state.authMenu = payload.menu;
            state.authPermission = payload.permission;
            state.authDefaultPermission = payload.defaultPermission;
        },
        authLogout: function (state) {
            state.authStatus = false;
            state.authToken = null;
            state.authBranchId = '';
            state.authInfo = {};
            state.authMenu = [];
            state.authPermission = {};
            state.authDefaultPermission = {};
        },
        forgetPassword: function (state, payload) {
            state.resetInfo = {
                email: payload.email
            }
        },
        resetPassword: function (state) {
            state.resetInfo = {
                email: null
            }
        },
        authInfo: function (state, payload) {
            state.authInfo = payload;
        }
    },
}
