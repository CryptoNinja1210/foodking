<template>
    <LoadingComponent :props="loading"/>
    <section class="pt-8 pb-16">
        <div class="container max-w-[360px] py-6 p-4 sm:px-6 shadow-xs rounded-2xl bg-white">
            <h2 class="capitalize mb-6 text-center text-[22px] font-semibold leading-[34px] text-heading">
                {{ $t('label.verify_number') }}
            </h2>
            <form @submit.prevent="save">
                <label class="text-sm first-letter:uppercase mb-1 text-heading">{{ $t('label.enter_the_code_sent_to') }}
                    <span class="font-medium">
                        {{ props.form.code + '' + props.form.phone }}
                    </span>
                </label>
                <input :class="errors !== '' ? 'invalid' : ''" type="text" v-model="props.form.token"
                       class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9]">
                <small class="db-field-alert" v-if="errors">{{ errors }}</small>
                <br>
                <button @click.prevent="resendCode" type="button"
                        class="capitalize mb-6 mt-2 text-xs font-medium transition text-primary hover:underline">
                    {{ $t('button.resend_code') }}
                </button>
                <button type="submit"
                        class="w-full h-12 text-center capitalize font-medium rounded-3xl text-white bg-primary">
                    {{ $t('label.continue') }}
                </button>
            </form>
        </div>
    </section>
</template>

<script>

import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";

export default {
    name: "SignupVerifyComponent",
    components: {LoadingComponent},
    data() {
        return {
            loading: {
                isActive: false,
            },
            props: {
                form: {
                    phone: "",
                    token: "",
                    code: "",
                },
            },
            errors: "",
            message: null,
        };
    },
    mounted() {
        this.phoneChecking();
    },
    methods: {
        phoneChecking: function () {
            const otp = this.$store.getters['frontendSignup/phone'];
            if (Object.keys(otp).length > 0) {
                this.props.form.phone = otp.otp.phone;
                this.props.form.code = otp.otp.code;
            } else {
                this.$router.push({name: 'auth.signupPhone'});
            }
        },
        resendCode: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("frontendSignup/otp", this.props.form).then((res) => {
                    this.loading.isActive = false;
                    this.errors = "";
                    alertService.success(res.data.message, 'bottom-center');
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.message;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
        save: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("frontendSignup/verify", this.props.form).then((res) => {
                    this.loading.isActive = false;
                    alertService.success(res.data.message, 'bottom-center');
                    this.props.form = {
                        token: "",
                        phone: "",
                    };
                    this.errors = '';
                    this.$router.push({
                        name: "auth.signupRegister",
                    });
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.message;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
}
</script>
