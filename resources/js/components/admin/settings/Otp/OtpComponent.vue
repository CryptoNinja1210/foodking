<template>
    <LoadingComponent :props="loading" />

    <div id="company" class="db-card db-tab-div active">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ $t("menu.otp") }}</h3>
        </div>
        <div class="db-card-body">
            <form @submit.prevent="save">
                <div class="form-row">
                    <div class="form-col-12 sm:form-col-6">
                        <label for="otp_type" class="db-field-title required">{{ $t("label.otp_type") }}</label>

                        <vue-select class="db-field-control f-b-custom-select" id="otp_type"
                            v-bind:class="errors.otp_type ? 'invalid' : ''" v-model="form.otp_type"
                            :options="enums.otpTypeEnum" label-by="name" value-by="id" :closeOnSelect="true"
                            :searchable="true" :clearOnClose="true" placeholder="--" search-placeholder="--" />
                        <small class="db-field-alert" v-if="errors.otp_type">{{ errors.otp_type[0] }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="otp_digit_limit" class="db-field-title required">{{
                            $t("label.otp_digit_limit")
                        }}</label>

                        <vue-select class="db-field-control f-b-custom-select" id="otp_digit_limit"
                            v-bind:class="errors.otp_digit_limit ? 'invalid' : ''" v-model="form.otp_digit_limit"
                            :options="enums.otpDigitLimitEnum" label-by="name" value-by="id" :closeOnSelect="true"
                            :searchable="true" :clearOnClose="true" placeholder="--" search-placeholder="--" />
                        <small class="db-field-alert" v-if="errors.otp_digit_limit">{{
                            errors.otp_digit_limit[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="otp_expire_time" class="db-field-title required">{{
                            $t("label.otp_expire_time")
                        }}</label>

                        <vue-select class="db-field-control f-b-custom-select" id="otp_expire_time"
                            v-bind:class="errors.otp_expire_time ? 'invalid' : ''" v-model="form.otp_expire_time"
                            :options="enums.otpExpireTimeEnum" label-by="name" value-by="id" :closeOnSelect="true"
                            :searchable="true" :clearOnClose="true" placeholder="--" search-placeholder="--" />
                        <small class="db-field-alert" v-if="errors.otp_expire_time">{{
                            errors.otp_expire_time[0]
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
import otpTypeEnum from "../../../../enums/modules/otpTypeEnum";
import otpDigitLimitEnum from "../../../../enums/modules/otpDigitLimitEnum";
import otpExpireTimeEnum from "../../../../enums/modules/otpExpireTimeEnum";

export default {
    name: "OtpComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            form: {
                otp_type: null,
                otp_digit_limit: null,
                otp_expire_time: null,
            },
            enums: {
                otpTypeEnum: otpTypeEnum,
                otpDigitLimitEnum: otpDigitLimitEnum,
                otpExpireTimeEnum: otpExpireTimeEnum,
            },
            errors: {},
        };
    },
    mounted() {
        try {
            this.loading.isActive = true;
            this.$store.dispatch("otp/lists").then((res) => {
                this.form.otp_type = res.data.data.otp_type;
                this.form.otp_digit_limit = res.data.data.otp_digit_limit;
                this.form.otp_expire_time = res.data.data.otp_expire_time;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        } catch (err) {
            this.loading.isActive = false;
            alertService.error(err);
        }
    },
    methods: {
        save: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("otp/save", this.form).then((res) => {
                    this.loading.isActive = false;
                    alertService.successFlip(res.config.method === "put" ?? 0, this.$t("menu.otp"));
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
