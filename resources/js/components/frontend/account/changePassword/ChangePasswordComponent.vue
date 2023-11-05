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
                    {{ $t('label.change_password') }}

                </h2>
                <form @submit.prevent="changePassword">
                    <div class="row">
                        <div class="col-12 sm:col-12">
                            <label for="old_password" class="text-sm capitalize mb-1 text-heading">
                                {{ $t('label.old_password') }}
                            </label>
                            <input v-model="form.old_password" v-bind:class="errors.old_password ? 'invalid' : ''"
                                id="old_password" type="password"
                                class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9]">
                            <small class="db-field-alert" v-if="errors.old_password">{{
                                errors.old_password[0]
                            }}</small>
                        </div>
                        <div class="col-12 sm:col-6">
                            <label for="password" class="text-sm capitalize mb-1 text-heading">{{
                                $t('label.new_password')
                            }}
                            </label>
                            <input v-model="form.password" v-bind:class="errors.password ? 'invalid' : ''" id="password"
                                type="password" class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9]">
                            <small class="db-field-alert" v-if="errors.password">{{
                                errors.password[0]
                            }}</small>
                        </div>
                        <div class="col-12 sm:col-6">
                            <label for="password_confirmation" class="text-sm capitalize mb-1 text-heading">
                                {{ $t('label.retype_new_password') }}</label>
                            <input v-model="form.password_confirmation"
                                v-bind:class="errors.password_confirmation ? 'invalid' : ''" id="password_confirmation"
                                type="password" class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9]">
                            <small class="db-field-alert" v-if="errors.password_confirmation">{{
                                errors.password_confirmation[0]
                            }}</small>
                        </div>
                        <div class="col-12">
                            <button type="submit"
                                class="w-full h-12 text-center capitalize font-medium rounded-3xl text-white bg-primary">
                                {{ $t('button.change_password') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<script>


import alertService from "../../../../services/alertService";
import LoadingComponent from "../../components/LoadingComponent";
export default {
    name: "ChangePasswordComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            form: {
                old_password: "",
                password: "",
                password_confirmation: "",
            },
            errors: {},
        };
    },
    methods: {
        changePassword: function () {
            try {
                this.loading.isActive = true;
                this.$store
                    .dispatch("frontendEditProfile/changePassword", this.form).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(
                            res.config.method === "put" ?? 0,
                            this.$t("menu.password")
                        );
                        this.form = {
                            old_password: "",
                            password: "",
                            password_confirmation: "",
                        };
                        this.errors = {};
                    })
                    .catch((err) => {
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
