<template>
    <LoadingComponent :props="loading"/>
    <section class="pt-8 pb-16">
        <div class="container max-w-[550px] py-6 p-4 sm:px-6 shadow-xs rounded-2xl bg-white">
            <h2 class="capitalize mb-6 text-center text-[22px] font-semibold leading-[34px] text-heading">
                {{ $t('label.create_account') }}
            </h2>
            <form @submit.prevent="save">
                <div class="row">
                    <div class="col-12 sm:col-6">
                        <label class="text-sm capitalize mb-1 text-heading">{{ $t('label.first_name') }}</label>
                        <input type="text" v-model="props.form.first_name"
                               class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9]">
                        <small class="db-field-alert" v-if="errors.first_name">
                            {{ errors.first_name[0] }}
                        </small>
                    </div>
                    <div class="col-12 sm:col-6">
                        <label class="text-sm capitalize mb-1 text-heading">{{ $t('label.last_name') }}</label>
                        <input type="text" v-model="props.form.last_name"
                               class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9]">
                        <small class="db-field-alert" v-if="errors.last_name">
                            {{ errors.last_name[0] }}
                        </small>
                    </div>
                    <div class="col-12 sm:col-6">
                        <label class="text-sm capitalize mb-1 text-heading">{{ $t('label.email') }}</label>
                        <input type="email" v-model="props.form.email"
                               class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9]">
                        <small class="db-field-alert" v-if="errors.email">
                            {{ errors.email[0] }}
                        </small>
                    </div>
                    <div class="col-12 sm:col-6">
                        <label class="text-sm capitalize mb-1 text-heading">{{ $t('label.password') }}</label>
                        <input type="password" v-model="props.form.password"
                               class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9]">
                        <small class="db-field-alert" v-if="errors.password">
                            {{ errors.password[0] }}
                        </small>
                    </div>
                    <div class="col-12">
                        <button type="submit"
                                class="w-full h-12 text-center capitalize font-medium rounded-3xl text-white bg-primary">
                            {{ $t('button.sign_up') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</template>

<script>

import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";

export default {
    name: "SignupRegisterComponent",
    components: {LoadingComponent},
    data() {
        return {
            loading: {
                isActive: false,
            },
            props: {
                form: {
                    first_name: "",
                    last_name: "",
                    email: "",
                    password: "",
                    phone: "",
                    country_code: "",
                },
            },
            errors: {},
        };
    },
    computed: {
        carts: function () {
            return this.$store.getters['frontendCart/lists'];
        }
    },
    mounted() {
        this.phoneChecking();
    },
    methods: {
        phoneChecking: function () {
            const otp = this.$store.getters['frontendSignup/phone'];
            if (Object.keys(otp).length > 0) {
                this.props.form.phone = otp.otp.phone;
                this.props.form.country_code = otp.otp.phone;
            } else {
                this.$router.push({name: 'auth.signupPhone'});
            }
        },
        save: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("frontendSignup/signup", this.props.form).then((res) => {
                    this.errors = {};
                    this.$store.dispatch('login', {
                        email: this.props.form.email,
                        password: this.props.form.password
                    }).then(LoginRes => {
                        this.loading.isActive = false;
                        alertService.success(LoginRes.data.message);
                        this.props.form = {
                            first_name: "",
                            last_name: "",
                            email: "",
                            password: "",
                            phone: "",
                        };
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
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    }
}
</script>
