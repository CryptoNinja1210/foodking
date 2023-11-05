<template>
    <LoadingComponent :props="loading"/>
    <section class="pt-8 pb-16">
        <div class="container max-w-[360px] py-6 p-4 mb-6 sm:px-6 shadow-xs rounded-2xl bg-white">
            <h2 class="capitalize mb-6 text-center text-[22px] font-semibold leading-[34px] text-heading">
                {{ $t('label.welcome_back') }}
            </h2>
            <div v-if="errors.validation"
                 class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-5 rounded relative" role="alert">
                <span class="block sm:inline">{{ errors.validation }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" @click="close">
                    <i class="lab lab-close-circle-line margin-top-5-px"></i>
                </span>
            </div>
            <form @submit.prevent="login">
                <div class="mb-4">
                    <label for="formEmail" class="text-sm capitalize mb-1 text-heading">{{ $t('label.email') }}</label>
                    <input type="text" :class="errors.email ? 'invalid' : ''" v-model="form.email"
                           class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9]" id="formEmail">
                    <small class="db-field-alert" v-if="errors.email">{{ errors.email[0] }}</small>
                </div>
                <div class="mb-4">
                    <label for="formPassword" class="text-sm capitalize mb-1 text-heading">{{
                            $t('label.password')
                        }}</label>
                    <input autocomplete="off" type="password" :class="errors.password ? 'invalid' : ''"
                           v-model="form.password"
                           class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9]" id="formPassword">
                    <small class="db-field-alert" v-if="errors.password">{{ errors.password[0] }}</small>
                </div>
                <div class="flex items-center justify-between mb-6">
                    <div class="db-field-checkbox p-0">
                        <div class="custom-checkbox w-3 h-3">
                            <input type="checkbox" id="checkbox2" class="custom-checkbox-field">
                            <i class="fa-solid fa-check custom-checkbox-icon leading-[9px] text-[9px] rounded-[3px] border-[#6E7191]"></i>
                        </div>
                        <label for="checkbox2" class="db-field-label text-xs text-heading">
                            {{ $t('label.remember_me') }}
                        </label>
                    </div>
                    <router-link :to="{ name: 'auth.forgetPassword' }"
                                 class="capitalize text-xs font-medium transition text-primary hover:underline">
                        {{ $t('button.forget_password') }}
                    </router-link>
                </div>
                <button type="submit"
                        class="w-full h-12 text-center capitalize font-medium rounded-3xl mb-6 text-white bg-primary">
                    {{ $t('button.login') }}
                </button>
                <div class="flex items-center justify-center gap-2 mb-4">
                    <span class="text-xs text-[#6E7191]">{{ $t('message.have_account') }}</span>
                    <router-link :to="{ name: 'auth.signupPhone' }" class="text-xs font-medium text-primary">
                        {{ $t('button.signup') }}
                    </router-link>
                </div>
                <p class="text-sm uppercase text-center mb-3 text-[#6E7191]">{{ $t('label.or') }}</p>
                <router-link :to="{ name: 'auth.guestLogin' }"
                             class="w-full h-12 leading-[46px] text-center capitalize font-medium rounded-3xl border text-primary border-primary bg-white">
                    {{ $t('button.login_as_guest') }}
                </router-link>
            </form>
        </div>

        <div v-if="demo === 'true' || demo === 'TRUE' || demo === 'True' || demo === '1' || demo === 1" class="container max-w-[360px] py-6 p-4 sm:px-6 shadow-xs rounded-2xl bg-white">
            <h2 class="mb-6 text-center text-lg font-medium text-heading">{{ $t('message.for_quick_demo') }}</h2>
            <nav class="grid grid-cols-2 gap-3">
                <button @click.prevent="setupCredit('admin')"
                        class="click-to-prop w-full h-10 leading-10 rounded-lg text-center text-sm capitalize text-white bg-orange-500"
                        id="adminClick">
                    {{ $t('label.admin') }}
                </button>
                <button @click.prevent="setupCredit('customer')"
                        class="click-to-prop w-full h-10 leading-10 rounded-lg text-center text-sm capitalize text-white bg-emerald-500"
                        id="customerClick">
                    {{ $t('label.customer') }}
                </button>
                <button @click.prevent="setupCredit('branchManager')"
                        class="click-to-prop w-full h-10 leading-10 rounded-lg text-center text-sm capitalize text-white bg-sky-600"
                        id="branchManagerClick">
                    {{ $t('label.branch_manager') }}
                </button>
                <button @click.prevent="setupCredit('posOperator')"
                        class="click-to-prop w-full h-10 leading-10 rounded-lg text-center text-sm capitalize text-white bg-purple-500"
                        id="posOperatorClick">
                    {{ $t('label.pos_operator') }}
                </button>
            </nav>
        </div>
    </section>
</template>

<script>
import router from "../../../router";
import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";
import ENV from "../../../config/env";

export default {
    name: "LoginComponent",
    components: {LoadingComponent},
    data() {
        return {
            loading: {
                isActive: false,
            },
            form: {
                email: "",
                password: ""
            },
            errors: {},
            permissions: {},
            firstMenu: null,
            demo : ENV.DEMO
        }
    },
    computed: {
        carts: function () {
            return this.$store.getters['frontendCart/lists'];
        }
    },
    methods: {
        login: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch('login', this.form).then((res) => {
                    this.loading.isActive = false;
                    alertService.success(res.data.message);
                    if (this.carts.length > 0) {
                        router.push({name: "frontend.checkout"});
                    } else {
                        router.push({name: "frontend.home"});
                    }
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                })
            } catch (err) {
                this.loading.isActive = false;
            }
        },
        close: function () {
            this.errors = {}
        },
        setupCredit: function (e) {
            if (e === 'admin') {
                this.form.email = 'admin@example.com';
                this.form.password = '123456';
            } else if (e === 'customer') {
                this.form.email = 'customer@example.com';
                this.form.password = '123456';
            } else if (e === 'branchManager') {
                this.form.email = 'branchmanager@example.com';
                this.form.password = '123456';
            } else if (e === 'posOperator') {
                this.form.email = 'posoperator@example.com';
                this.form.password = '123456';
            }
        }
    }
}
</script>
