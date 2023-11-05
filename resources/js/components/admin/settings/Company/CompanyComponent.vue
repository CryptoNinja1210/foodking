<template>
    <LoadingComponent :props="loading" />

    <div id="company" class="db-card db-tab-div active">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ $t("menu.company") }}</h3>
        </div>
        <div class="db-card-body">
            <form @submit.prevent="save">
                <div class="form-row">
                    <div class="form-col-12 sm:form-col-6">
                        <label for="name" class="db-field-title required">
                            {{ $t("label.name") }}
                        </label>
                        <input v-model="form.company_name" v-bind:class="errors.company_name ? 'invalid' : ''" type="text"
                            id="name" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.company_name">{{ errors.company_name[0] }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="email" class="db-field-title required">
                            {{ $t("label.email") }}
                        </label>
                        <input v-model="form.company_email" v-bind:class="errors.company_email ? 'invalid' : ''" type="text"
                            id="email" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.company_email">{{ errors.company_email[0] }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="phone" class="db-field-title required">
                            {{ $t("label.phone") }}
                        </label>
                        <input v-on:keypress="phoneNumber($event)" v-model="form.company_phone"
                            v-bind:class="errors.company_phone ? 'invalid' : ''" type="text" id="phone"
                            class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.company_phone">{{ errors.company_phone[0] }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="website" class="db-field-title">
                            {{ $t("label.website") }}
                        </label>
                        <input v-model="form.company_website" v-bind:class="errors.company_website ? 'invalid' : ''"
                            type="text" id="website" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.company_website">{{
                            errors.company_website[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="city" class="db-field-title required">
                            {{ $t("label.city") }}
                        </label>
                        <input v-model="form.company_city" v-bind:class="errors.company_city ? 'invalid' : ''" type="text"
                            id="city" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.company_city">{{ errors.company_city[0] }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="company_state" class="db-field-title required">
                            {{ $t("label.state") }}
                        </label>
                        <input v-model="form.company_state" v-bind:class="errors.company_state ? 'invalid' : ''" type="text"
                            id="company_state" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.company_state">{{ errors.company_state[0] }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="country_code" class="db-field-title required">
                            {{ $t("label.country_code") }}
                        </label>
                        <vue-select class="db-field-control f-b-custom-select" id="country_code"
                            v-bind:class="errors.company_country_code ? 'is-invalid' : ''"
                            v-model="form.company_country_code" :options="countryCodes" label-by="country_name"
                            value-by="country_code" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                            placeholder="--" search-placeholder="--" />
                        <small class="db-field-alert" v-if="errors.company_country_code">{{
                            errors.company_country_code[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="company_zip_code" class="db-field-title required">
                            {{ $t("label.zip_code") }}</label>
                        <input v-model="form.company_zip_code" v-bind:class="errors.company_zip_code ? 'invalid' : ''"
                            type="text" id="company_zip_code" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.company_zip_code">{{
                            errors.company_zip_code[0]
                        }}</small>
                    </div>

                    <div class="form-col-12">
                        <label for="address" class="db-field-title required">
                            {{ $t("label.address") }}
                        </label>
                        <textarea v-model="form.company_address" v-bind:class="errors.company_address ? 'invalid' : ''"
                            id="address" class="db-field-control">
                                                                                                            </textarea>
                        <small class="db-field-alert" v-if="errors.company_address">{{
                            errors.company_address[0]
                        }}</small>
                    </div>

                    <div class="form-col-12">
                        <button type="submit" class="db-btn text-white bg-primary">
                            <i class="lab lab-save"></i>
                            <span>{{ $t("button.save") }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";

export default {
    name: "CompanyComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            form: {
                company_name: "",
                company_email: "",
                company_phone: "",
                company_website: "",
                company_city: "",
                company_state: "",
                company_country_code: null,
                company_zip_code: "",
                company_address: "",
            },
            errors: {},
        };
    },
    mounted() {
        try {
            this.loading.isActive = true;
            this.$store.dispatch('countryCode/lists');
            this.$store
                .dispatch("company/lists")
                .then((res) => {
                    this.form = {
                        company_name: res.data.data.company_name,
                        company_email: res.data.data.company_email,
                        company_phone: res.data.data.company_phone,
                        company_website: res.data.data.company_website,
                        company_city: res.data.data.company_city,
                        company_state: res.data.data.company_state,
                        company_country_code:
                            res.data.data.company_country_code,
                        company_zip_code: res.data.data.company_zip_code,
                        company_address: res.data.data.company_address,
                    };
                    this.loading.isActive = false;
                })
                .catch((err) => {
                    this.loading.isActive = false;
                });
        } catch (err) {
            this.loading.isActive = false;
            alertService.error(err);
        }
    },
    computed: {
        countryCodes: function () {
            return this.$store.getters['countryCode/lists'];
        },
    },
    methods: {
        phoneNumber(e) {
            return appService.phoneNumber(e);
        },
        save: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("company/save", this.form).then((res) => {
                    this.loading.isActive = false;
                    alertService.successFlip(
                        res.config.method === "put" ?? 0,
                        this.$t("menu.company")
                    );
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
    },
};
</script>
