<template>
    <div class="col-12">
        <BreadcrumbComponent />
    </div>

    <LoadingComponent :props="loading" />
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ $t("label.change_password") }}</h3>
            </div>
            <div class="db-card-body">
                <form @submit.prevent="changePassword">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="old_password" class="db-field-title required">
                                {{ $t('label.old_password') }}
                            </label>
                            <input v-model="form.old_password" v-bind:class="errors.old_password ? 'invalid' : ''"
                                id="old_password" type="password" class="db-field-control">
                            <small class="db-field-alert" v-if="errors.old_password">
                                {{ errors.old_password[0] }}
                            </small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="password" class="db-field-title required">
                                {{ $t("label.password") }}
                            </label>
                            <input v-model="form.password" v-bind:class="errors.password ? 'invalid' : ''" type="password"
                                id="password" class="db-field-control" autocomplete="off" />
                            <small class="db-field-alert" v-if="errors.password">
                                {{ errors.password[0] }}
                            </small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="confirm_password" class="db-field-title required">
                                {{ $t("label.confirm_password") }}
                            </label>
                            <input v-model="form.password_confirmation"
                                v-bind:class="errors.password_confirmation ? 'invalid' : ''" type="password"
                                id="confirm_password" class="db-field-control" autocomplete="off" />
                            <small class="db-field-alert" v-if="errors.password_confirmation">
                                {{ errors.password_confirmation[0] }}
                            </small>
                        </div>

                        <div class="form-col-12">
                            <div class="flex flex-wrap gap-3">
                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-save"></i>
                                    <span>{{ $t("button.save") }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import BreadcrumbComponent from "../components/BreadcrumbComponent";
import alertService from "../../../services/alertService";
import LoadingComponent from "../components/LoadingComponent";

export default {
    name: "ProfileChangePasswordComponent",
    components: {
        BreadcrumbComponent,
        LoadingComponent
    },
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
            errors: {}
        }
    },
    methods: {
        changePassword: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("frontendEditProfile/changePassword", this.form).then((res) => {
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
