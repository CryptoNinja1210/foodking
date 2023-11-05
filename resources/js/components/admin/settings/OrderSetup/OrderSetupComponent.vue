<template>
    <LoadingComponent :props="loading" />
    <div id="order_setup" class="db-card db-tab-div active">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ $t('menu.order_setup') }}</h3>
        </div>
        <div class="db-card-body">
            <form @submit.prevent="save">
                <fieldset class="p-4 mb-6 border border-[#DBDEE0]">
                    <legend class="py-1.5 px-4 text-base font-semibold capitalize border border-[#DBDEE0] text-primary">
                        {{ $t('menu.order') }}
                    </legend>
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="order_setup_food_preparation_time" class="db-field-title required">
                                {{ $t("label.food_preparation_time") }}
                                <span class="text-primary">{{ $t("label.in_minute") }}</span>
                            </label>
                            <input v-on:keypress="floatNumber($event)" v-model="form.order_setup_food_preparation_time"
                                v-bind:class="errors.order_setup_food_preparation_time ? 'invalid' : ''" type="text"
                                id="order_setup_food_preparation_time" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.order_setup_food_preparation_time">{{
                                errors.order_setup_food_preparation_time[0]
                            }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="order_setup_schedule_order_slot_duration" class="db-field-title required">
                                {{ $t("label.schedule_order_slot_duration") }}
                                <span class="text-primary">{{ $t("label.in_minute") }}</span>
                            </label>
                            <input v-on:keypress="floatNumber($event)"
                                v-model="form.order_setup_schedule_order_slot_duration"
                                v-bind:class="errors.order_setup_schedule_order_slot_duration ? 'invalid' : ''"
                                type="text" id="order_setup_schedule_order_slot_duration" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.order_setup_schedule_order_slot_duration">{{
                                errors.order_setup_schedule_order_slot_duration[0]
                            }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="enable">{{ $t("label.takeaway") }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.activityEnum.ENABLE" v-model="form.order_setup_takeaway"
                                            id="takeaway-enable" type="radio" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="takeaway-enable" class="db-field-label">{{ $t("label.enable") }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.activityEnum.DISABLE" v-model="form.order_setup_takeaway"
                                            type="radio" id="takeaway-disable" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="takeaway-disable" class="db-field-label">{{ $t("label.disable") }}</label>
                                </div>
                            </div>
                            <small class="db-field-alert" v-if="errors.order_setup_takeaway">{{
                                errors.order_setup_takeaway[0]
                            }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="enable">{{ $t("label.delivery") }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.activityEnum.ENABLE" v-model="form.order_setup_delivery"
                                            id="deliver-enable" type="radio" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="deliver-enable" class="db-field-label">{{ $t("label.enable") }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.activityEnum.DISABLE" v-model="form.order_setup_delivery"
                                            type="radio" id="deliver-disable" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="deliver-disable" class="db-field-label">{{ $t("label.disable") }}</label>
                                </div>
                            </div>
                            <small class="db-field-alert" v-if="errors.order_setup_delivery">{{
                                errors.order_setup_delivery[0]
                            }}</small>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="p-4 mb-6 border border-[#DBDEE0]">
                    <legend class="py-1.5 px-4 text-base font-semibold capitalize border border-[#DBDEE0] text-primary">
                        {{ $t('menu.delivery_charge') }}
                    </legend>
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="order_setup_free_delivery_kilometer" class="db-field-title required">
                                {{ $t("label.free_delivery_kilometer") }}
                            </label>
                            <input v-on:keypress="floatNumber($event)"
                                v-model="form.order_setup_free_delivery_kilometer"
                                v-bind:class="errors.order_setup_free_delivery_kilometer ? 'invalid' : ''" type="text"
                                id="order_setup_free_delivery_kilometer" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.order_setup_free_delivery_kilometer">{{
                                errors.order_setup_free_delivery_kilometer[0]
                            }}</small>
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label for="order_setup_basic_delivery_charge" class="db-field-title required">
                                {{ $t("label.basic_delivery_charge") }}
                            </label>
                            <input v-on:keypress="floatNumber($event)" v-model="form.order_setup_basic_delivery_charge"
                                v-bind:class="errors.order_setup_basic_delivery_charge ? 'invalid' : ''" type="text"
                                id="order_setup_basic_delivery_charge" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.order_setup_basic_delivery_charge">{{
                                errors.order_setup_basic_delivery_charge[0]
                            }}</small>
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label for="order_setup_charge_per_kilo" class="db-field-title required">
                                {{ $t("label.charge_per_kilo") }}
                            </label>
                            <input v-on:keypress="floatNumber($event)" v-model="form.order_setup_charge_per_kilo"
                                v-bind:class="errors.order_setup_charge_per_kilo ? 'invalid' : ''" type="text"
                                id="order_setup_charge_per_kilo" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.order_setup_charge_per_kilo">{{
                                errors.order_setup_charge_per_kilo[0]
                            }}</small>
                        </div>
                    </div>
                </fieldset>
                <button type="submit" class="db-btn text-white bg-primary">
                    <i class="lab lab-save"></i>
                    <span>{{ $t("button.save") }}</span>
                </button>
            </form>
        </div>
    </div>
</template>

<script>

import activityEnum from "../../../../enums/modules/activityEnum";
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";

export default {
    name: "OrderSetupComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false
            },
            form: {
                order_setup_food_preparation_time: null,
                order_setup_schedule_order_slot_duration: null,
                order_setup_takeaway: null,
                order_setup_delivery: null,
                order_setup_free_delivery_kilometer: null,
                order_setup_basic_delivery_charge: null,
                order_setup_charge_per_kilo: null
            },
            enums: {
                activityEnum: activityEnum
            },
            errors: {}
        }
    },
    computed: {
    },
    mounted() {
        try {
            this.loading.isActive = true;
            this.$store.dispatch('orderSetup/lists').then(res => {
                this.form = {
                    order_setup_food_preparation_time: res.data.data.order_setup_food_preparation_time,
                    order_setup_schedule_order_slot_duration: res.data.data.order_setup_schedule_order_slot_duration,
                    order_setup_takeaway: res.data.data.order_setup_takeaway,
                    order_setup_delivery: res.data.data.order_setup_delivery,
                    order_setup_free_delivery_kilometer: res.data.data.order_setup_free_delivery_kilometer,
                    order_setup_basic_delivery_charge: res.data.data.order_setup_basic_delivery_charge,
                    order_setup_charge_per_kilo: res.data.data.order_setup_charge_per_kilo
                }
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        } catch (err) {
            this.loading.isActive = false;
        }
    },
    methods: {
        floatNumber(e) {
            return appService.floatNumber(e);
        },
        save: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("orderSetup/save", this.form).then((res) => {
                    this.loading.isActive = false;
                    alertService.successFlip(res.config.method === "put" ?? 0, this.$t("menu.order_setup"));
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
