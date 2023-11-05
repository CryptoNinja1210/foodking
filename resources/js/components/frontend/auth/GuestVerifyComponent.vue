<template>
    <LoadingComponent :props="loading"/>
    <section class="pt-8 pb-16">
        <div class="container max-w-[360px] py-6 p-4 sm:px-6 shadow-xs rounded-2xl bg-white">
            <h2 class="capitalize mb-6 text-center text-[22px] font-semibold leading-[34px] text-heading">
                {{ $t('label.verify_number') }}
            </h2>
            <form @submit.prevent="save">
                <label class="text-sm first-letter:uppercase mb-1 text-heading">
                    {{ $t('label.enter_the_code_sent_to') }}
                    <span class="font-medium">{{ props.form.code + '' + props.form.phone }}</span>
                </label>
                <input type="text" v-model="props.form.token"
                       class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9]">
                <small class="db-field-alert" v-if="errors">{{ errors }}</small>
                <br>
                <button @click.prevent="resendCode" type="button"
                        class="capitalize mt-2 mb-6 text-xs font-medium transition text-primary hover:underline">
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
import alertService from "../../../services/alertService";
import LoadingComponent from "../components/LoadingComponent";

export default {
    name: "GuestVerifyComponent",
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
            errors: '',
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
            const otp = this.$store.getters['GuestSignup/phone'];
            if (Object.keys(otp).length > 0) {
                this.props.form.phone = otp.otp.phone;
                this.props.form.code = otp.otp.code;
            } else {
                this.$router.push({name: 'auth.guestLogin'});
            }
        },
        resendCode: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("GuestSignup/otp", this.props.form).then((res) => {
                    this.loading.isActive = false;
                    this.errors = '';
                    alertService.success(res.data.message, 'bottom-center');
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
        save: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("GuestLoginVerify", this.props.form).then((LoginRes) => {
                    this.$store.dispatch("GuestSignup/reset").then().catch();
                    this.loading.isActive = false;
                    this.props.form = {
                        phone: "",
                        code: "",
                    };
                    this.errors = '';
                    alertService.success(LoginRes.data.message);
                    if (this.carts.length > 0) {
                        this.$router.push({name: "frontend.checkout"});
                    } else {
                        this.$router.push({name: "frontend.home"});
                    }
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.message;
                })
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
}
</script>
