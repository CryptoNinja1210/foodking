<template>
    <LoadingComponent :props="loading" />

    <div id="company" class="db-card db-tab-div active">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ $t('menu.site') }}</h3>
        </div>
        <div class="db-card-body">
            <form @submit.prevent="save">
                <div class="form-row">
                    <div class="form-col-12 sm:form-col-6">
                        <label for="site_date_format" class="db-field-title required">{{
                            $t("label.date_format")
                        }}</label>

                        <vue-select class="db-field-control f-b-custom-select" id="site_date_format"
                            v-bind:class="errors.site_date_format ? 'is-invalid' : ''" v-model="form.site_date_format"
                            :options="enums.dateFormatEnum" label-by="name" value-by="id" :closeOnSelect="true"
                            :searchable="true" :clearOnClose="true" placeholder="--" search-placeholder="--" />
                        <small class="db-field-alert" v-if="errors.site_date_format">{{
                            errors.site_date_format[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="site_time_format" class="db-field-title required">{{
                            $t("label.time_format")
                        }}</label>

                        <vue-select class="db-field-control f-b-custom-select" id="site_time_format"
                            v-bind:class="errors.site_time_format ? 'is-invalid' : ''" v-model="form.site_time_format"
                            :options="enums.timeFormatEnum" label-by="name" value-by="id" :closeOnSelect="true"
                            :searchable="true" :clearOnClose="true" placeholder="--" search-placeholder="--" />
                        <small class="db-field-alert" v-if="errors.site_time_format">{{
                            errors.site_time_format[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="site_default_timezone" class="db-field-title required">{{
                            $t("label.default_timezone")
                        }}</label>

                        <vue-select class="db-field-control f-b-custom-select" id="site_default_timezone"
                            v-bind:class="errors.site_default_timezone ? 'is-invalid' : ''"
                            v-model="form.site_default_timezone" :options="timezones" label-by="name" value-by="name"
                            :closeOnSelect="true" :searchable="true" :clearOnClose="true" placeholder="--"
                            search-placeholder="--" />
                        <small class="db-field-alert" v-if="errors.site_default_timezone">{{
                            errors.site_default_timezone[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="site_default_branch" class="db-field-title required">{{
                            $t("label.default_branch")
                        }}</label>

                        <vue-select class="db-field-control f-b-custom-select" id="site_default_branch"
                            v-bind:class="errors.site_default_branch ? 'invalid' : ''" v-model="form.site_default_branch"
                            :options="branches" label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"
                            :clearOnClose="true" placeholder="--" search-placeholder="--" />
                        <small class="db-field-alert" v-if="errors.site_default_branch">{{
                            errors.site_default_branch[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="site_default_language" class="db-field-title required">{{
                            $t("label.default_language")
                        }}</label>

                        <vue-select class="db-field-control f-b-custom-select" id="site_default_language"
                            v-bind:class="errors.site_default_language ? 'is-invalid' : ''"
                            v-model="form.site_default_language" :options="languages" label-by="name" value-by="id"
                            :closeOnSelect="true" :searchable="true" :clearOnClose="true" placeholder="--"
                            search-placeholder="--" />
                        <small class="db-field-alert" v-if="errors.site_default_language">{{
                            errors.site_default_language[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="site_default_sms_gateway" class="db-field-title">{{
                            $t("label.default_sms_gateway")
                        }}</label>

                        <vue-select class="db-field-control f-b-custom-select" id="site_default_sms_gateway"
                            v-bind:class="errors.site_default_sms_gateway ? 'invalid' : ''"
                            v-model="form.site_default_sms_gateway" :options="smsGateways" label-by="name" value-by="id"
                            :closeOnSelect="true" :searchable="true" :clearOnClose="true" placeholder="--"
                            search-placeholder="--" />
                        <small class="db-field-alert" v-if="errors.site_default_sms_gateway">{{
                            errors.site_default_sms_gateway[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="site_android_app_link" class="db-field-title">
                            {{ $t("label.android_app_link") }}
                        </label>
                        <input v-model="form.site_android_app_link"
                            v-bind:class="errors.site_android_app_link ? 'invalid' : ''" type="text"
                            id="site_android_app_link" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.site_android_app_link">{{
                            errors.site_android_app_link[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="site_ios_app_link" class="db-field-title">
                            {{ $t("label.ios_app_link") }}
                        </label>
                        <input v-model="form.site_ios_app_link" v-bind:class="errors.site_ios_app_link ? 'invalid' : ''"
                            type="text" id="site_ios_app_link" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.site_ios_app_link">{{
                            errors.site_ios_app_link[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="site_copyright" class="db-field-title required">
                            {{ $t("label.copyright") }}
                        </label>
                        <input v-model="form.site_copyright" v-bind:class="errors.site_copyright ? 'invalid' : ''"
                            type="text" id="site_copyright" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.site_copyright">{{
                            errors.site_copyright[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="site_google_map_key" class="db-field-title required">
                            {{ $t("label.google_map_key") }}
                        </label>
                        <input v-model="form.site_google_map_key" v-bind:class="errors.site_google_map_key ? 'invalid' : ''"
                            type="text" id="site_google_map_key" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.site_google_map_key">{{
                            errors.site_google_map_key[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="site_digit_after_decimal_point" class="db-field-title required">
                            {{ $t("label.digit_after_decimal_point") }}
                            <span class="text-primary">{{ $t("label.ex") }}</span>
                        </label>
                        <input v-on:keypress="floatNumber($event)" v-model="form.site_digit_after_decimal_point"
                            v-bind:class="errors.site_digit_after_decimal_point ? 'invalid' : ''" type="text"
                            id="site_digit_after_decimal_point" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.site_digit_after_decimal_point">{{
                            errors.site_digit_after_decimal_point[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="site_default_currency" class="db-field-title required">{{
                            $t("label.default_currency")
                        }}</label>

                        <vue-select class="db-field-control f-b-custom-select" id="site_default_currency"
                            v-bind:class="errors.site_default_currency ? 'is-invalid' : ''"
                            v-model="form.site_default_currency" :options="currencies" label-by="name_symbol" value-by="id"
                            :closeOnSelect="true" :searchable="true" :clearOnClose="true" placeholder="--"
                            search-placeholder="--" />
                        <small class="db-field-alert" v-if="errors.site_default_currency">{{
                            errors.site_default_currency[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="enable">
                            {{ $t("label.currency_position") }}
                        </label>
                        <div class="db-field-radio-group">
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.currencyPositionEnum.LEFT" v-model="form.site_currency_position"
                                        id="left" type="radio" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="left" class="db-field-label"> ({{ form.site_default_currency_symbol }}) {{
                                    $t("label.left")
                                }}</label>
                            </div>
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.currencyPositionEnum.RIGHT" v-model="form.site_currency_position"
                                        type="radio" id="right" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="right" class="db-field-label">
                                    {{ $t("label.right") }} ({{ form.site_default_currency_symbol }})
                                </label>
                            </div>
                        </div>
                        <small class="db-field-alert" v-if="errors.site_currency_position">{{
                            errors.site_currency_position[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="enable">{{ $t("label.online_payment_gateway") }}</label>
                        <div class="db-field-radio-group">
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.activityEnum.ENABLE" v-model="form.site_online_payment_gateway"
                                        id="online_payment_gateway_enable" type="radio" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="online_payment_gateway_enable" class="db-field-label">{{
                                    $t("label.enable")
                                }}</label>
                            </div>
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.activityEnum.DISABLE" v-model="form.site_online_payment_gateway"
                                        type="radio" id="online_payment_gateway_disable" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="online_payment_gateway_disable" class="db-field-label">{{
                                    $t("label.disable")
                                }}</label>
                            </div>
                        </div>
                        <small class="db-field-alert" v-if="errors.site_online_payment_gateway">{{
                            errors.site_online_payment_gateway[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="enable">{{ $t("label.language_switch") }}</label>
                        <div class="db-field-radio-group">
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.activityEnum.ENABLE" v-model="form.site_language_switch"
                                        id="language_switch_enable" type="radio" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="language_switch_enable" class="db-field-label">{{
                                    $t("label.enable")
                                }}</label>
                            </div>
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.activityEnum.DISABLE" v-model="form.site_language_switch"
                                        type="radio" id="language_switch_disable" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="language_switch_disable" class="db-field-label">{{
                                    $t("label.disable")
                                }}</label>
                            </div>
                        </div>
                        <small class="db-field-alert" v-if="errors.site_language_switch">{{
                            errors.site_language_switch[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="enable">{{ $t("label.email_verification") }}</label>
                        <div class="db-field-radio-group">
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.activityEnum.ENABLE" v-model="form.site_email_verification"
                                        id="email_verification_enable" type="radio" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="email_verification_enable" class="db-field-label">{{
                                    $t("label.enable")
                                }}</label>
                            </div>
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.activityEnum.DISABLE" v-model="form.site_email_verification"
                                        type="radio" id="email_verification_disable" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="email_verification_disable" class="db-field-label">{{
                                    $t("label.disable")
                                }}</label>
                            </div>
                        </div>
                        <small class="db-field-alert" v-if="errors.site_email_verification">{{
                            errors.site_email_verification[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="enable">{{ $t("label.phone_verification") }}</label>
                        <div class="db-field-radio-group">
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.activityEnum.ENABLE" v-model="form.site_phone_verification"
                                        id="phone_verification_enable" type="radio" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="phone_verification_enable" class="db-field-label">{{
                                    $t("label.enable")
                                }}</label>
                            </div>
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.activityEnum.DISABLE" v-model="form.site_phone_verification"
                                        type="radio" id="phone_verification_disable" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="phone_verification_disable" class="db-field-label">{{
                                    $t("label.disable")
                                }}</label>
                            </div>
                        </div>
                        <small class="db-field-alert" v-if="errors.site_phone_verification">{{
                            errors.site_phone_verification[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="app_debug">{{ $t("label.app_debug") }}</label>
                        <div class="db-field-radio-group">
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.activityEnum.ENABLE" v-model="form.site_app_debug"
                                        id="debug_enable" type="radio" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="debug_enable" class="db-field-label">{{ $t("label.enable") }}</label>
                            </div>
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.activityEnum.DISABLE" v-model="form.site_app_debug" type="radio"
                                        id="debug_disable" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="debug_disable" class="db-field-label">{{ $t("label.disable") }}</label>
                            </div>
                        </div>
                        <small class="db-field-alert" v-if="errors.site_app_debug">{{
                            errors.site_app_debug[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="auto_update">{{ $t("label.auto_update") }}</label>
                        <div class="db-field-radio-group">
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.activityEnum.ENABLE" v-model="form.site_auto_update"
                                        id="auto_update_enable" type="radio" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="auto_update_enable" class="db-field-label">{{ $t("label.enable") }}</label>
                            </div>
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.activityEnum.DISABLE" v-model="form.site_auto_update" type="radio"
                                        id="auto_update_disable" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="auto_update_disable" class="db-field-label">{{
                                    $t("label.disable")
                                }}</label>
                            </div>
                        </div>
                        <small class="db-field-alert" v-if="errors.site_auto_update">{{
                            errors.site_auto_update[0]
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

import dateFormatEnum from "../../../../enums/modules/dateFormatEnum";
import timeFormatEnum from "../../../../enums/modules/timeFormatEnum";
import activityEnum from "../../../../enums/modules/activityEnum";
import currencyPositionEnum from "../../../../enums/modules/currencyPositionEnum";
import statusEnum from "../../../../enums/modules/statusEnum";
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";

export default {
    name: "SiteComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false
            },
            form: {
                site_date_format: null,
                site_time_format: null,
                site_default_timezone: null,
                site_default_branch: null,
                site_default_currency: null,
                site_default_currency_symbol: null,
                site_default_language: null,
                site_language_switch: null,
                site_app_debug: null,
                site_auto_update: null,
                site_currency_position: null,
                site_email_verification: null,
                site_phone_verification: null,
                site_digit_after_decimal_point: null,
                site_google_map_key: null,
                site_android_app_link: null,
                site_ios_app_link: null,
                site_copyright: null,
                site_online_payment_gateway: null,
                site_default_sms_gateway: null,
            },
            enums: {
                dateFormatEnum: dateFormatEnum,
                timeFormatEnum: timeFormatEnum,
                activityEnum: activityEnum,
                currencyPositionEnum: currencyPositionEnum,
            },
            errors: {}
        }
    },
    computed: {
        timezones: function () {
            return this.$store.getters['timezone/lists'];
        },
        branches: function () {
            return this.$store.getters['branch/lists'];
        },
        currencies: function () {
            return this.$store.getters['currency/lists'];
        },
        languages: function () {
            return this.$store.getters['language/lists'];
        },
        smsGateways: function () {
            return this.$store.getters["smsGateway/lists"];
        },
    },
    mounted() {
        try {
            this.loading.isActive = true;
            this.list();
            this.$store.dispatch("smsGateway/lists", {
                status: statusEnum.ACTIVE
            });
            this.$store.dispatch('timezone/lists');
            this.$store.dispatch('branch/lists', {
                order_column: 'id',
                order_type: 'asc',
                status: statusEnum.ACTIVE
            });
            this.$store.dispatch('currency/lists', {
                order_column: 'id',
                order_type: 'asc',
                status: statusEnum.ACTIVE
            });
            this.$store.dispatch('language/lists', {
                order_column: 'id',
                order_type: 'asc',
                status: statusEnum.ACTIVE
            });

        } catch (err) {
            this.loading.isActive = false;
        }
    },
    methods: {
        floatNumber(e) {
            return appService.floatNumber(e);
        },
        list: function () {
            this.loading.isActive = true;
            this.$store.dispatch('site/lists').then(res => {
                this.form = {
                    site_date_format: res.data.data.site_date_format,
                    site_time_format: res.data.data.site_time_format,
                    site_default_timezone: res.data.data.site_default_timezone,
                    site_default_branch: res.data.data.site_default_branch,
                    site_default_currency: res.data.data.site_default_currency,
                    site_default_currency_symbol: res.data.data.site_default_currency_symbol,
                    site_default_language: res.data.data.site_default_language,
                    site_language_switch: res.data.data.site_language_switch,
                    site_app_debug: res.data.data.site_app_debug,
                    site_auto_update: res.data.data.site_auto_update,
                    site_currency_position: res.data.data.site_currency_position,
                    site_email_verification: res.data.data.site_email_verification,
                    site_phone_verification: res.data.data.site_phone_verification,
                    site_digit_after_decimal_point: res.data.data.site_digit_after_decimal_point,
                    site_google_map_key: res.data.data.site_google_map_key,
                    site_android_app_link: res.data.data.site_android_app_link,
                    site_ios_app_link: res.data.data.site_ios_app_link,
                    site_copyright: res.data.data.site_copyright,
                    site_online_payment_gateway: res.data.data.site_online_payment_gateway,
                    site_default_sms_gateway: res.data.data.site_default_sms_gateway === 0 ? null : res.data.data.site_default_sms_gateway,
                }
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });

        },
        save: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("site/save", this.form).then((res) => {
                    this.loading.isActive = false;
                    alertService.successFlip(res.config.method === "put" ?? 0, this.$t("menu.site"));
                    this.list();
                    this.$store.dispatch('frontendSetting/lists').then().catch();
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
