<template>
    <LoadingComponent :props="loading"/>
    <SmTimeSlotModalCreateComponent v-on:click="this.props.form.day = this.day" :props="addButton"/>

    <div id="modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("menu.time_slots") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                        @click="reset"></button>
            </div>
            <div class="modal-body">
                <div v-if="message"
                     class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-5 rounded relative" role="alert">
                    <span class="block sm:inline">{{ message }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" @click="close">
                        <i class="lab lab-close-circle-line margin-top-5-px"></i>
                    </span>
                </div>

                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-12">
                            <label for="opening_time" class="db-field-title required">
                                {{ $t("label.opening_time") }}
                            </label>

                            <Datepicker hideInputIcon @update:modelValue="handleOpeningTime" v-model="opening_time"
                                        :input-class-name="errors.opening_time ? 'invalid' : ''" time-picker/>

                            <small class="db-field-alert" v-if="errors.opening_time">
                                {{ errors.opening_time[0] }}
                            </small>
                        </div>
                        <div class="form-col-12 sm:form-col-12">
                            <label for="closing_time" class="db-field-title required">
                                {{ $t("label.closing_time") }}
                            </label>

                            <Datepicker hideInputIcon @update:modelValue="handleClosingTime" v-model="closing_time"
                                        :input-class-name="errors.opening_time ? 'invalid' : ''" time-picker/>
                            <small class="db-field-alert" v-if="errors.closing_time">
                                {{ errors.closing_time[0] }}
                            </small>
                        </div>

                        <div class="form-col-12">
                            <div class="modal-btns">
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                    <i class="lab lab-close"></i>
                                    <span>{{ $t("button.close") }}</span>
                                </button>

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
import SmTimeSlotModalCreateComponent from "../../components/buttons/SmTimeSlotModalCreateComponent";
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

export default {
    name: "TimeSlotCreateComponent",
    components: {SmTimeSlotModalCreateComponent, LoadingComponent, Datepicker},
    props: ["props", "day"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            addButton: {
                title: this.$t("button.add_time_slot"),
            },
            opening_time: "",
            closing_time: "",
            errors: {},
            message: null,
        };
    },
    methods: {
        handleOpeningTime: function (e) {
            if (e) {
                this.$props.props.form.opening_time = (e.hours < 10 ? "0" : "") + e.hours + ":" + (e.minutes < 10 ? "0" : "") + e.minutes;
            } else {
                this.$props.props.form.opening_time = "";
            }
        },
        handleClosingTime: function (e) {
            if (e) {
                this.$props.props.form.closing_time = (e.hours < 10 ? "0" : "") + e.hours + ":" + (e.minutes < 10 ? "0" : "") + e.minutes;
            } else {
                this.$props.props.form.closing_time = "";
            }
        },
        reset: function () {
            appService.modalHide();
            this.$store.dispatch("tax/reset").then().catch();
            this.errors = {};

            this.$props.props.form = {
                opening_time: "",
                closing_time: "",
                day: "",
            };
            this.message = null;
            this.opening_time = "";
            this.closing_time = "";
        },

        save: function () {
            try {
                const tempId = this.$store.getters["timeSlot/temp"].temp_id;
                this.loading.isActive = true;
                this.$store
                    .dispatch("timeSlot/save", this.$props.props)
                    .then((res) => {
                        appService.modalHide();
                        this.loading.isActive = false;
                        alertService.successFlip(tempId === null ? 0 : 1, this.$t("menu.time_slots"));
                        this.$props.props.form = {
                            opening_time: "",
                            closing_time: "",
                            day: "",
                        };

                        this.opening_time = "";
                        this.closing_time = "";

                        this.errors = {};
                        this.message = null;
                    })
                    .catch((err) => {
                        this.loading.isActive = false;
                        if (err.response.data.errors === undefined) {
                            if (err.response.data.message) {
                                this.errors = {};
                                this.message = err.response.data.message;
                            } else {
                                this.message = null;
                            }
                        } else {
                            this.message = null;
                            this.errors = err.response.data.errors;
                        }
                    });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
        close: function () {
            this.message = null;
        }
    },
};
</script>
