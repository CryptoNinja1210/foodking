<template>
    <LoadingComponent :props="loading" />
    <section class="pt-7 pb-16">
        <div class="container max-w-[550px]">
            <router-link :to="{ name: 'frontend.home' }" class="mb-3 inline-flex items-center gap-2 text-primary">
                <i class="lab lab-undo lab-font-size-16"></i>
                <span class="text-xs font-medium leading-6">{{ $t('label.back_to_home') }}</span>
            </router-link>
            <div class="py-6 p-4 sm:px-6 shadow-xs rounded-2xl bg-white">
                <h2 class="capitalize mb-6 text-left text-[22px] font-semibold leading-[34px] text-heading">
                    {{ $t('menu.edit_profile') }}
                </h2>
                <form @submit.prevent="save" id="formElem">
                    <div class="row">
                        <div class="col-12 sm:col-6">
                            <label for="first_name" class="text-xs capitalize mb-1 text-heading">{{ $t('label.first_name') }}</label>
                            <input id="first_name" type="text" v-model="form.first_name" :class="errors.first_name ? 'invalid' : ''"
                                class="w-full h-12 text-sm rounded-lg border px-4 text-heading border-[#D9DBE9]">
                            <small class="db-field-alert" v-if="errors.first_name">
                                {{ errors.first_name[0] }}
                            </small>
                        </div>
                        <div class="col-12 sm:col-6">
                            <label for="last_name" class="text-xs capitalize mb-1 text-heading">{{ $t('label.last_name') }}</label>
                            <input id="last_name" type="text" v-model="form.last_name" :class="errors.last_name ? 'invalid' : ''"
                                class="w-full h-12 text-sm rounded-lg border px-4 text-heading border-[#D9DBE9]">
                            <small class="db-field-alert" v-if="errors.last_name">
                                {{ errors.last_name[0] }}
                            </small>
                        </div>
                        <div class="col-12 sm:col-6">
                            <label for="email" class="text-xs capitalize mb-1 text-heading">{{ $t('label.email') }}</label>
                            <input id="email" type="email" v-model="form.email" :class="errors.email ? 'invalid' : ''"
                                class="w-full h-12 text-sm rounded-lg border px-4 text-heading border-[#D9DBE9]">
                            <small class="db-field-alert" v-if="errors.email">
                                {{ errors.email[0] }}
                            </small>
                        </div>
                        <div class="col-12 sm:col-6">
                            <label for="phone" class="text-xs capitalize mb-1 text-heading">{{ $t('label.phone') }}</label>
                            <div class="w-full h-12 rounded-lg border px-4 flex items-center border-[#D9DBE9]" :class="errors.phone ? 'invalid' : ''">
                                <div class="w-fit flex-shrink-0 dropdown-group">
                                    <button type="button" class="flex items-center gap-1 dropdown-btn">
                                        {{ flag }}
                                        <span class="whitespace-nowrap flex-shrink-0 text-xs">
                                            {{ form.country_code }}
                                        </span>
                                        <input type="hidden" v-model="form.country_code">
                                    </button>
                                </div>
                                <input id="phone" type="text" v-on:keypress="phoneNumber($event)" v-model="form.phone"
                                    class="pl-4 text-sm w-full h-full text-heading">
                            </div>
                            <small class="db-field-alert" v-if="errors.phone">
                                {{ errors.phone[0] }}
                            </small>
                        </div>
                        <div class="col-12">
                            <button
                                class="w-full h-12 text-center capitalize font-medium rounded-3xl text-white bg-primary">
                                {{ $t('button.update_profile') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<script>
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";
import LoadingComponent from "../../components/LoadingComponent";

export default {
    name: "EditProfileComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            form: {
                first_name: "",
                last_name: "",
                email: "",
                phone: "",
                country_code: ""
            },
            flag: "",
            errors: {},
        };
    },
    mounted() {
        try {
            this.loading.isActive = true;
            const profile = this.$store.getters.authInfo;
            const countryCode = this.$store.getters['frontendCountryCode/show'];
            this.form = {
                first_name: profile.first_name,
                last_name: profile.last_name,
                email: profile.email,
                phone: profile.phone,
                country_code: countryCode.calling_code,
            };
            this.flag = countryCode.flag_emoji;
            this.loading.isActive = false;
        } catch (err) {
            this.loading.isActive = false;
            alertService.error(err);
        }
    },
    computed: {
        countryCode: function () {
            return this.$store.getters['frontendCountryCode/show'];
        }
    },
    methods: {
        phoneNumber(e) {
            return appService.phoneNumber(e);
        },
        save: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("frontendEditProfile/updateProfile", this.form).then((res) => {
                    this.$store.dispatch('updateAuthInfo', res.data.data).then(res => {
                        this.loading.isActive = false;
                        alertService.successFlip(1, this.$t("menu.profile"));
                        this.errors = {};
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err);
                    });
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
    watch: {
        countryCode: {
            deep: true,
            handler(country) {
                this.flag = country.flag_emoji;
                this.form.country_code = country.calling_code;
            }
        }
    }
}
</script>
