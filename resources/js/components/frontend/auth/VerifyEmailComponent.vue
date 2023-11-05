<template>
    <LoadingComponent :props="loading"/>
    <section class="pt-8 pb-16">
        <div class="container max-w-[360px] py-6 p-4 sm:px-6 shadow-xs rounded-2xl bg-white">
            <h2 class="capitalize mb-6 text-center text-[22px] font-semibold leading-[34px] text-heading">
                {{ $t('label.verify_email') }}</h2>
            <form @submit.prevent="verifyCode">
                <label class="text-sm mb-1 first-letter:uppercase text-heading">{{
                        $t('message.enter_the_code_sent_to')
                    }} <span class="font-medium">{{ resetInfo.email }}</span></label>
                <input :class="errors.code ? 'invalid' : ''" v-model="form.code" type="number"
                       class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9]">
                <small class="db-field-alert" v-if="errors.code">{{ errors.code[0] }}</small>
                <br>
                <button @click.prevent="resendCode" type="button"
                        class="capitalize mb-6 mt-2 text-xs font-medium transition text-primary hover:underline">
                    {{ $t('button.resend_code') }}
                </button>
                <button type="submit"
                        class="w-full h-12 text-center capitalize font-medium rounded-3xl text-white bg-primary">
                    {{ $t('button.continue') }}
                </button>
            </form>
        </div>
    </section>
</template>

<script>
import alertService from "../../../services/alertService";
import LoadingComponent from "../components/LoadingComponent";

export default {
    name: "VerifyEmailComponent",
    components: {LoadingComponent},
    data() {
        return {
            loading: {
                isActive: false,
            },
            form: {
                email: null,
                code: null
            },
            errors: {}
        }
    },
    computed: {
        resetInfo: function () {
            return this.$store.getters.resetInfo;
        }
    },
    mounted() {
        this.emailChecking();
    },
    methods: {
        emailChecking: function () {
            if (this.$store.getters.resetInfo.email) {
                this.form.email = this.$store.getters.resetInfo.email;
            } else {
                this.$router.push({name: 'auth.forgetPassword'});
            }
        },
        resendCode: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch('forgetPassword', this.form).then((res) => {
                    this.loading.isActive = false;
                    alertService.success(res.data.message, 'bottom-center');
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                })
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            }
        },
        verifyCode: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch('verifyCode', this.form).then((res) => {
                    this.loading.isActive = false;
                    alertService.success(res.data.message, 'bottom-center');
                    this.$router.push({name: 'auth.resetPassword'});
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                })
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            }
        }
    }
}
</script>
