<template>
    <LoadingComponent :props="loading"/>
    <header class="shadow-xs bg-white ff-header">
        <div class="container flex flex-col lg:flex-row items-center justify-between">
            <div class="w-full flex items-center justify-between gap-5 xl:gap-8 lg:justify-start lg:w-fit">
                <router-link :to="{ name: 'frontend.home' }">
                    <img class="w-32" :src="setting.theme_logo" alt="logo">
                </router-link>

                <div class="relative dropdown-group" v-if="branches.length > 1">
                    <button class="flex items-center text-left gap-2 dropdown-btn">
                        <h3 class="capitalize text-xs font-medium text-heading">
                            <span class="block font-normal mb-0.5">{{ $t('label.branch') }}</span>
                            {{ branch.name }}
                        </h3>
                        <i class="lab lab-arrow-down-2 lab-font-size-15 text-xs text-primary"></i>
                    </button>
                    <ul v-if="branches.length > 0"
                        class="p-2 w-fit rounded-lg shadow-xl absolute top-14 right-0 z-10 border border-gray-200 bg-white hidden dropdown-list">
                        <li v-for="branch in branches"
                            class="flex items-center gap-2 w-full px-2.5 rounded-md transition hover:bg-gray-100">
                            <input @click="changeBranch(branch.id)" v-model="defaultBranch" type="radio"
                                   :id="'branch_id_' + branch.id" :value="branch.id" name="area"
                                   class="w-3 cursor-pointer mb-[1px] accent-primary">
                            <label :for="'branch_id_' + branch.id"
                                   class="capitalize leading-8 text-sm min-w-[150px] cursor-pointer text-heading">
                                {{ branch.name }}
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
            <nav class="items-center justify-center gap-6 hidden lg:flex">
                <router-link :to="{ name: 'frontend.home' }"
                    :class="checkIsPathAndRoutePathSame('/home') ? 'text-primary' : ''"
                    class="capitalize text-sm font-medium text-heading">
                    {{ $t('menu.home') }}
                </router-link>
                <router-link :to="{ name: 'frontend.menu', query: { s: categoryProps.slug } }"
                             :class="checkIsPathAndRoutePathSame('/menu') ? 'text-primary' : ''"
                             class="capitalize text-sm font-medium text-heading">
                    {{ $t('label.menu') }}
                </router-link>
                <router-link :to="{ name: 'frontend.offers' }"
                             :class="checkIsPathAndRoutePathSame('/offers') ? 'text-primary' : ''"
                             class="capitalize text-sm font-medium text-heading">
                    {{ $t('label.offers') }}
                </router-link>
            </nav>

            <div class="flex flex-col items-center justify-end gap-3 w-full mt-4 lg:flex-row lg:w-fit lg:mt-0">
                <form @submit.prevent="search"
                      class="header-search-group group flex items-center justify-center border border-solid gap-2 px-2 w-full lg:w-52 h-8 rounded-3xl transition border-[#EFF0F6] bg-[#EFF0F6] focus-within:bg-white focus-within:border-primary">
                    <button type="submit" class="header-search-submit">
                        <i class="lab lab-search-normal"></i>
                    </button>
                    <input type="search" v-model="searchItem" :placeholder="$t('button.search')"
                           class="header-search-field w-full h-full text-xs appearance-none placeholder:font-normal placeholder:text-paragraph text-heading">
                    <button type="button" @click.prevent="searchReset"
                            class="header-search-button transition invisible group-focus-within:visible">
                        <i class="lab lab-close-circle-line lab-font-size-16 lab-font-weight-600 text-red-500"></i>
                    </button>
                </form>
                <div v-if="setting.site_language_switch === enums.activityEnum.ENABLE"
                     class="hidden lg:block relative dropdown-group w-full sm:w-fit">
                    <button
                        class="flex items-center justify-center gap-1.5 w-fit rounded-3xl capitalize text-sm font-medium h-8 px-3 border transition text-heading bg-white border-gray-200 dropdown-btn">
                        <img :src="language.image" alt="flag" class="w-4 h-4 rounded-full">
                        <span class="whitespace-nowrap">{{ language.name }}</span>
                    </button>
                    <ul v-if="languages.length > 0"
                        class="p-2 min-w-[180px] rounded-lg shadow-xl absolute top-14 ltr:right-0 rtl:left-0 z-10 border border-gray-200 bg-white hidden dropdown-list">
                        <li @click="changeLanguage(language.id, language.code)" v-for="language in languages"
                            class="flex items-center gap-2 py-1.5 px-2.5 rounded-md cursor-pointer hover:bg-gray-100">
                            <img :src="language.image" alt="flag" class="w-4 h-4 rounded-full">
                            <span class="text-heading capitalize text-sm">{{ language.name }}</span>
                        </li>
                    </ul>
                </div>
                <button
                    class="webcart hidden lg:flex items-center justify-center gap-1.5 w-fit rounded-3xl capitalize text-sm font-medium h-8 px-3 transition text-white bg-heading">
                    <i class="lab lab-bag-2 lab-font-size-17"></i>
                    <span class="whitespace-nowrap">{{
                            currencyFormat(subtotal, setting.site_digit_after_decimal_point,
                                setting.site_default_currency_symbol, setting.site_currency_position)
                        }}</span>
                </button>
                <router-link v-if="!logged"
                             class="hidden lg:flex items-center justify-center gap-1 w-fit rounded-3xl capitalize text-sm font-medium h-8 px-3 transition text-white bg-primary"
                             :to="{ name: 'auth.login' }">
                    <i class="lab lab-profile-circle"></i>
                    <span class="whitespace-nowrap">{{ $t('label.login') }}</span>
                </router-link>

                <div v-else class="dropdown-group">
                    <button
                        class="dropdown-btn hidden lg:flex items-center justify-center gap-1 w-fit rounded-3xl capitalize text-sm font-medium h-8 px-3 transition text-white bg-primary">
                        <i class="lab lab-profile-circle"></i>
                        <span class="whitespace-nowrap">{{ $t('label.account') }}</span>
                        <i class="lab lab-arrow-down-2 text-xs ml-1.5 lab-font-size-15"></i>
                    </button>
                    <div
                        class="dropdown-list absolute top-12 right-0 z-[60] rounded-xl w-[360px] p-4 shadow-paper bg-white">
                        <div class="w-fit mx-auto text-center mb-3">
                            <figure
                                class="relative z-10 w-[98px] h-[98px] border-2 border-dashed rounded-full inline-flex items-center justify-center border-white bg-gradient-to-t from-[#FF7A00] to-[#FF016C]
                                                                                                                                                                                            before:absolute before:top-1/2 before:left-1/2 before:-translate-x-1/2 before:-translate-y-1/2 before:w-24 before:h-24 before:rounded-full before:-z-10 before:bg-white">
                                <img class="w-[90px] h-[90px] rounded-full shadow-avatar" :src="profile.image"
                                     alt="avatar">
                            </figure>
                            <label for="imageProperty"
                                   class="block w-11 h-11 mx-auto -mt-7 mb-1 relative z-10 rounded-full border-2 cursor-pointer bg-heading border-white">
                                <input @change="saveImage" id="imageProperty" ref="imageProperty"
                                       accept="image/png, image/jpeg, image/jpg" type="file"
                                       class="w-full h-full rounded-full opacity-0 cursor-pointer">
                                <i
                                    class="lab lab-edit-2 absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 -z-10 lab-font-size-24 lab-font-color-1"></i>
                            </label>
                            <h3 class="font-medium text-sm leading-6 capitalize">{{
                                    textShortener(profile.name, 20)
                                }}</h3>
                            <p class="text-xs mb-0.5">{{ profile.email }}</p>
                            <p class="text-xs">{{ profile.phone }}</p>
                            <h3 class="font-medium text-sm leading-6 capitalize mb-0.5">{{
                                    profile.currency_balance
                                }}</h3>
                        </div>
                        <nav>
                            <router-link
                                v-if="profile.role_id !== enums.roleEnum.CUSTOMER && Object.keys(authDefaultPermission).length > 0"
                                :to="{ name: 'admin.dashboard' }"
                                class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
                                <i class="lab lab-dashboard lab-font-size-17"></i>
                                <span class="text-sm leading-6 capitalize">{{ $t('menu.dashboard') }}</span>
                            </router-link>

                            <router-link :to="{ name: 'frontend.myOrder' }"
                                         class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
                                <i class="lab lab-reserve-line lab-font-size-17"></i>
                                <span class="text-sm leading-6 capitalize">{{ $t('button.my_orders') }}</span>
                            </router-link>

                            <router-link :to="{ name: 'frontend.editProfile' }"
                                         class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
                                <i class="lab lab-edit lab-font-size-17"></i>
                                <span class="text-sm leading-6 capitalize">{{ $t('button.edit_profile') }}</span>
                            </router-link>

                            <router-link :to="{ name: 'frontend.chat' }"
                                         class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
                                <i class="lab lab-messages-line lab-font-size-17"></i>
                                <span class="text-sm leading-6 capitalize">{{ $t('button.chat') }}</span>
                            </router-link>

                            <router-link :to="{ name: 'frontend.address' }"
                                         class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
                                <i class="lab lab-map lab-font-size-17"></i>
                                <span class="text-sm leading-6 capitalize">{{ $t('button.address') }}</span>
                            </router-link>

                            <router-link :to="{ name: 'frontend.changePassword' }"
                                         class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
                                <i class="lab lab-key lab-font-size-17"></i>
                                <span class="text-sm leading-6 capitalize">{{ $t('button.change_password') }}</span>
                            </router-link>
                            <button @click="logout()"
                                    class="paper-link transition w-full flex items-center gap-3.5 py-2.5 border-b last:border-none border-[#EFF0F6]">
                                <i class="lab lab-logout lab-font-size-17"></i>
                                <span class="text-sm leading-6 capitalize">{{ $t('button.logout') }}</span>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div id="order" v-if="orderNotificationStatus" ref="orderNotificationModal" class="modal active ff-modal">
        <div class="modal-dialog max-w-[360px] p-6 text-center relative">
            <button @click.prevent="closeOrderNotificationModal" class="modal-close absolute top-4 right-4">
                <i class="fa-regular fa-circle-xmark"></i>
            </button>
            <h3 class="text-[18px] font-semibold leading-8 mb-6">
                {{ orderNotificationMessage }}
                <span class="block">{{ $t('message.please_check_your_order_list') }}</span>
            </h3>
            <router-link :to="{ path: '/admin/' + orderNotification.url }" class="db-btn h-[38px] shadow-[0px_6px_10px_rgba(255,_0,_107,_0.24)] bg-primary text-white">
                {{ $t('button.let_me_check')}}
            </router-link>
        </div>
    </div>
</template>

<script>
import statusEnum from "../../../enums/modules/statusEnum";
import appService from "../../../services/appService";
import alertService from "../../../services/alertService";
import LoadingComponent from "../../frontend/components/LoadingComponent";
import axios from 'axios'
import {initializeApp} from "firebase/app";
import {getMessaging, getToken, onMessage} from "firebase/messaging";
import activityEnum from "../../../enums/modules/activityEnum";
import roleEnum from "../../../enums/modules/roleEnum";
import _ from "lodash";

export default {
    name: "FrontendNavbarComponent",
    components: {LoadingComponent},
    data() {
        return {
            loading: {
                isActive: false,
            },
            orderNotificationStatus: false,
            orderNotificationMessage: "",
            currentRoute: "",
            defaultBranch: null,
            defaultLanguage: null,
            defaultCountryCode: null,
            enums: {
                activityEnum: activityEnum,
                roleEnum: roleEnum
            },
            branchProps: {
                paginate: 0,
                order_column: "id",
                order_type: "asc",
                status: statusEnum.ACTIVE
            },
            languageProps: {
                paginate: 0,
                order_column: "id",
                order_type: "asc",
                status: statusEnum.ACTIVE
            },
            categoryProps: {
                search: {
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'asc',
                    status: statusEnum.ACTIVE
                },
                slug: '',
            },
            orderNotification: {
                permission: false,
                url: ""
            },
            searchItem: "",

        }
    },
    computed: {
        logged: function () {
            return this.$store.getters.authStatus;
        },
        authDefaultPermission: function () {
            return this.$store.getters.authDefaultPermission;
        },
        profile: function () {
            return this.$store.getters.authInfo;
        },
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        branch: function () {
            return this.$store.getters['frontendBranch/show'];
        },
        branches: function () {
            return this.$store.getters['frontendBranch/lists'];
        },
        language: function () {
            return this.$store.getters['frontendLanguage/show'];
        },
        languages: function () {
            return this.$store.getters['frontendLanguage/lists'];
        },
        categories: function () {
            return this.$store.getters['frontendItemCategory/lists'];
        },
        subtotal: function () {
            return this.$store.getters['frontendCart/subtotal'];
        }
    },
    mounted() {
        this.currentRoute = this.$route.path;
        this.loading.isActive = true;
        this.orderPermissionCheck();
        this.$store.dispatch('frontendSetting/lists').then(res => {
            this.defaultBranch = res.data.data.site_default_branch;
            this.defaultLanguage = res.data.data.site_default_language;
            this.defaultCountryCode = res.data.data.company_country_code;

            const globalState = this.$store.getters['globalState/lists'];
            if (globalState.branch_id > 0) {
                this.defaultBranch = globalState.branch_id;
            }

            if (globalState.language_id > 0) {
                this.defaultLanguage = globalState.language_id;
            }

            this.$store.dispatch('frontendBranch/lists', this.branchProps).then().catch();
            this.$store.dispatch('frontendBranch/show', this.defaultBranch).then().catch();
            this.$store.dispatch('frontendLanguage/lists', this.languageProps).then().catch();
            this.$store.dispatch('frontendLanguage/show', this.defaultLanguage).then(res => {
                this.$i18n.locale = res.data.data.code;
                this.$store.dispatch("globalState/init", {
                    language_code: res.data.data.code
                });
            }).catch();
            this.$store.dispatch('frontendItemCategory/lists', this.categoryProps.search).then().catch();
            this.loading.isActive = false;

            window.setTimeout(() => {
                this.$store.dispatch('frontendCountryCode/show', this.defaultCountryCode).then().catch();
                this.$store.dispatch('frontendCart/initOrderType', {
                    order_setup_delivery: res.data.data.order_setup_delivery,
                    order_setup_takeaway: res.data.data.order_setup_takeaway
                });

                if (this.$store.getters.authStatus && res.data.data.notification_fcm_api_key && res.data.data.notification_fcm_auth_domain && res.data.data.notification_fcm_project_id && res.data.data.notification_fcm_storage_bucket && res.data.data.notification_fcm_messaging_sender_id && res.data.data.notification_fcm_app_id && res.data.data.notification_fcm_measurement_id) {
                    initializeApp({
                        apiKey: res.data.data.notification_fcm_api_key,
                        authDomain: res.data.data.notification_fcm_auth_domain,
                        projectId: res.data.data.notification_fcm_project_id,
                        storageBucket: res.data.data.notification_fcm_storage_bucket,
                        messagingSenderId: res.data.data.notification_fcm_messaging_sender_id,
                        appId: res.data.data.notification_fcm_app_id,
                        measurementId: res.data.data.notification_fcm_measurement_id
                    });
                    const messaging = getMessaging();

                    Notification.requestPermission().then((permission) => {
                        if (permission === 'granted') {
                            getToken(messaging, {vapidKey: res.data.data.notification_fcm_public_vapid_key}).then((currentToken) => {
                                if (currentToken) {
                                    axios.post('/frontend/device-token/web', {token: currentToken}).then().catch((error) => {
                                        if (error.response.data.message === 'Unauthenticated.') {
                                            this.$store.dispatch('loginDataReset');
                                        }
                                    });
                                }
                            }).catch();
                        }
                    });

                    onMessage(messaging, (payload) => {
                        const notificationTitle = payload.notification.title;
                        const notificationOptions = {
                            body: payload.notification.body,
                            icon: '/images/default/firebase-logo.png'
                        };
                        new Notification(notificationTitle, notificationOptions);

                        if(payload.data.topicName === 'new-order-found' && this.orderNotification.permission) {
                            this.orderNotificationStatus = true;
                            this.orderNotificationMessage = payload.notification.body;
                            const audio = new Audio(res.data.data.notification_audio);
                            audio.play();
                        }
                    });
                }
            }, 3000);
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },
    methods: {
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        checkIsPathAndRoutePathSame(path) {
            if (this.currentRoute === path) {
                return true;
            }
        },
        changeBranch: function (id) {
            this.$store.dispatch('frontendBranch/show', id);
            this.$store.dispatch("globalState/set", {branch_id: id});
        },
        changeLanguage: function (id, code) {
            this.defaultLanguage = id;
            this.$store.dispatch("globalState/set", {language_id: id, language_code: code}).then(res => {
                this.$store.dispatch('frontendLanguage/show', id).then(res => {
                    this.$i18n.locale = res.data.data.code;
                }).catch();
            }).catch();
        },
        logout: function () {
            this.$store.dispatch("logout").then(res => {
                this.$router.push({name: "frontend.home"});
            }).catch();
        },
        currencyFormat(amount, decimal, currency, position) {
            return appService.currencyFormat(amount, decimal, currency, position);
        },
        search: function () {
            if (typeof this.searchItem !== "undefined" && this.searchItem !== "") {
                this.$router.push({name: "frontend.search", query: {s: this.searchItem}});
                this.searchItem = "";
            }
        },
        searchReset: function () {
            this.searchItem = "";
        },
        saveImage: function () {
            if (this.$refs.imageProperty.files[0]) {
                try {
                    this.loading.isActive = true;
                    const formData = new FormData();
                    formData.append("image", this.$refs.imageProperty.files[0]);
                    this.$store.dispatch("frontendEditProfile/changeImage", {form: formData}).then((res) => {
                        this.$store.dispatch('updateAuthInfo', res.data.data).then(res => {
                            this.loading.isActive = false;
                            alertService.success(this.$t("message.photo_update"));
                            this.$refs.imageProperty.value = null;
                        }).catch((err) => {
                            this.loading.isActive = false;
                            alertService.error(err);
                        });
                    }).catch((err) => {
                        this.loading.isActive = false;
                        this.imageErrors = err.response.data.errors;
                    });
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }
        },
        orderPermissionCheck: function () {
            const permissions = this.$store.getters.authPermission;
            if (permissions.length > 0) {
                _.forEach(permissions, (permission) => {
                    if (permission.name === 'online-orders') {
                        if (permission.access === true) {
                            this.orderNotification.permission = true;
                            this.orderNotification.url = permission.url;
                        }
                    }
                });
            }
        },
        closeOrderNotificationModal: function () {
            const modalTarget = this.$refs.orderNotificationModal;
            modalTarget?.classList?.remove("active");
            document.body.style.overflowY = "auto";
            this.loading.isActive = false;
            this.orderNotificationStatus = false;
        },
    },
    watch: {
        $route(to, from) {
            this.currentRoute = to.path;
        },
        categories: {
            deep: true,
            handler(category) {
                if (category.length > 0) {
                    if (category[0].slug !== "undefined") {
                        this.categoryProps.slug = category[0].slug;
                    }
                }
            }
        }
    },
}
</script>
