<template>
    <LoadingComponent :props="loading"/>
    <section class="pt-8 pb-16">
        <div class="container max-w-[360px] py-6 p-4 sm:px-6 shadow-xs rounded-2xl bg-white">
            <h2 class="capitalize mb-6 text-center text-[22px] font-semibold leading-[34px] text-heading">
                {{ $t('label.create_new_password') }}</h2>
            <form @submit.prevent="resetPassword">
                <div class="mb-4">
                    <label class="text-sm capitalize mb-1 text-heading">{{ $t('label.new_password') }}</label>
                    <input :class="errors.password ? 'invalid' : ''" v-model="form.password" type="password"
                           class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9]">
                    <small class="db-field-alert" v-if="errors.password">{{ errors.password[0] }}</small>
                </div>

                <div class="mb-4">
                    <label class="text-sm capitalize mb-1 text-heading">{{ $t('label.confirm_password') }}</label>
                    <input :class="errors.password_confirmation ? 'invalid' : ''" v-model="form.password_confirmation"
                           type="password"
                           class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9]">
                    <small class="db-field-alert" v-if="errors.password_confirmation">{{
                            errors.password_confirmation[0]
                        }}</small>
                </div>

                <button type="submit"
                        class="w-full h-12 text-center capitalize font-medium rounded-3xl text-white bg-primary">
                    {{ $t('button.submit') }}
                </button>
            </form>
        </div>
    </section>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";

export default {
    name: "ResetPasswordComponent",
    components: {LoadingComponent},
    data() {
        return {
            loading: {
                isActive: false,
            },
            form: {
                email: null,
                password: null,
                password_confirmation: null
            },
            errors: {}
        }
    },
    computed: {
        carts: function () {
            return this.$store.getters['frontendCart/lists'];
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
                this.$router.push({name: 'auth.verifyEmail'});
            }
        },
        resetPassword: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch('resetPassword', this.form).then((res) => {
                    this.$store.dispatch('login', {
                        email: this.form.email,
                        password: this.form.password
                    }).then(LoginRes => {
                        this.loading.isActive = false;
                        alertService.success(LoginRes.data.message);
                        if (this.carts.length > 0) {
                            this.$router.push({name: "frontend.checkout"});
                        } else {
                            this.$router.push({name: "frontend.home"});
                        }
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.success(res.data.message);
                        this.$router.push({name: "auth.login"});
                    });
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
