<template>
    <LoadingComponent :props="loading" />
    <section class="pt-8 pb-16">
        <div class="container max-w-[360px] py-6 p-4 sm:px-6 shadow-xs rounded-2xl bg-white">
            <h2 class="capitalize mb-6 text-center text-[22px] font-semibold leading-[34px] text-heading">
                {{ $t('label.guest_login') }}
            </h2>
            <form @submit.prevent="save">
                <div class="mb-6">
                    <label class="text-sm capitalize mb-1 text-heading">{{ $t('label.mobile_number') }}</label>
                    <div class="w-full h-12 rounded-lg border px-4 flex items-center border-[#D9DBE9]">
                        <div class="w-fit flex-shrink-0 dropdown-group">
                            <button type="button" class="flex items-center gap-1">
                                {{ flag }}
                                <span class="whitespace-nowrap flex-shrink-0 text-sm">{{ props.form.code }}</span>
                                <input type="hidden" v-model="props.form.code">
                            </button>
                        </div>
                        <input v-model="props.form.phone" v-on:keypress="phoneNumber($event)" v-on:keyup.enter="save" :class="errors.phone
                            ? 'invalid' : ''" type="text" id="phone"
                               class="pl-4 text-sm w-full h-full text-heading"/>
                    </div>
                    <small class="db-field-alert" v-if="errors.phone">
                        {{ errors.phone[0] }}
                    </small>
                </div>
                <button type="submit"
                        class="w-full h-12 text-center capitalize font-medium rounded-3xl mb-6 text-white bg-primary">
                    {{ $t('label.next') }}
                </button>
                <div class="flex items-center justify-center gap-2">
                    <span class="text-base text-[#6E7191]">{{ $t('label.already_have_an_account') }}</span>
                    <router-link :to="{ name: 'auth.login' }" class="text-base font-medium text-primary">
                        {{ $t('label.login') }}
                    </router-link>
                </div>
            </form>
        </div>
    </section>
</template>

<script>

import appService from "../../../services/appService";
import activityEnum from "../../../enums/modules/activityEnum"
import alertService from "../../../services/alertService";
import LoadingComponent from "../components/LoadingComponent";

export default {
    name: "GuestLoginComponent",
    components: {LoadingComponent},
    data() {
        return {
            loading: {
                isActive: false,
            },
            props: {
                form: {
                    phone: "",
                    code: "",
                },
            },
            flag: "",
            country_code: "",
            errors: {},
            phone_verification: "",
        };
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch('frontendSetting/lists').then(res => {
            this.defaultCountryCode = res.data.data.company_country_code;
            this.$store.dispatch('frontendCountryCode/show', this.defaultCountryCode).then(res => {
                this.props.form.code = res.data.data.calling_code;
                this.country_code = res.data.data.calling_code;
                this.flag = res.data.data.flag_emoji;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },
    computed: {
        countryCode: function () {
            return this.$store.getters['frontendCountryCode/show'];
        },
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        carts: function () {
            return this.$store.getters['frontendCart/lists'];
        }
    },
    methods: {
        phoneNumber(e) {
            return appService.phoneNumber(e);
        },
        save: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("GuestSignup/otp", this.props.form).then((res) => {
                    if (this.setting.site_phone_verification === activityEnum.DISABLE) {
                        this.props.form.token = "1000";
                        this.$store.dispatch("GuestLoginVerify", this.props.form).then((LoginRes) => {
                            this.$store.dispatch("GuestSignup/reset").then().catch();
                            this.loading.isActive = false;
                            this.errors = {};
                            this.props.form = {
                                phone: "",
                                code: this.country_code,
                            };
                            alertService.success(LoginRes.data.message);
                            if (this.carts.length > 0) {
                                this.$router.push({name: "frontend.checkout"});
                            } else {
                                this.$router.push({name: "frontend.home"});
                            }
                        }).catch((err) => {
                            this.loading.isActive = false;
                            this.errors = err.response.data.errors;
                        });
                    } else {
                        this.loading.isActive = false;
                        this.errors = {};
                        this.props.form = {
                            phone: "",
                            code: this.country_code,
                        };
                        alertService.success(res.data.message, 'bottom-center');
                        this.$router.push({
                            name: "auth.guestLoginVerify",
                        });
                    }
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
}
</script>
