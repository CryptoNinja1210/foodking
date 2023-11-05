<template>
    <LoadingComponent :props="loading"/>
    <section class="pt-8 pb-16">
        <div class="container max-w-[360px] py-6 p-4 sm:px-6 shadow-xs rounded-2xl bg-white">
            <h2 class="capitalize mb-6 text-center text-[22px] font-semibold leading-[34px] text-heading">{{
                $t('label.forget_password') }}</h2>
            <form @submit.prevent="forgetPassword">
                <label class="text-sm capitalize mb-1 text-heading">{{ $t('label.email') }}</label>
                <input :class="errors.email ? 'invalid' : ''" v-model="form.email" type="email"
                       class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9]">
                <small class="db-field-alert" v-if="errors.email">{{ errors.email[0] }}</small>
                <button type="submit"
                        class="w-full margin-top-6 h-12 mb-6 text-center capitalize font-medium rounded-3xl text-white bg-primary">
                    {{ $t('label.next') }}
                </button>
                <div class="flex items-center justify-center gap-2">
                    <span class="text-base text-[#6E7191]">{{ $t('label.already_have_an_account') }}</span>
                    <router-link class="text-base font-medium text-primary" :to="{name : 'auth.login' }">
                        {{ $t('button.login') }}
                    </router-link>
                </div>
            </form>
        </div>
    </section>
</template>
<script>

import alertService from "../../../services/alertService";
import LoadingComponent from "../components/LoadingComponent";

export default {
    name: "ForgetPasswordComponent",
    components: {LoadingComponent},
    data() {
        return {
            loading: {
                isActive: false,
            },
            form: {
                email: ""
            },
            errors: {}
        }
    },
    methods: {
        forgetPassword: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch('forgetPassword', this.form).then((res) => {
                    this.loading.isActive = false;
                    alertService.success(res.data.message, 'bottom-center');
                    this.$router.push({name: 'auth.verifyEmail'});
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                })
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        }
    }
}
</script>
