<template>
    <LoadingComponent :props="loading" />
    <SmSidebarModalCreateComponent :props="addButton" />

    <div id="sidebar" class="drawer">
        <div class="drawer-header">
            <h3 class="drawer-title">{{ $t("menu.coupons") }}</h3>
            <button class="fa-solid fa-xmark close-btn" @click="reset"></button>
        </div>
        <div class="drawer-body">
            <form @submit.prevent="save">
                <div class="form-row">
                    <div class="form-col-12 sm:form-col-6">
                        <label for="name" class="db-field-title required">{{ $t("label.name") }}</label>
                        <input v-model="props.form.name" v-bind:class="errors.name ? 'invalid' : ''" type="text"
                            id="name" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.name">{{ errors.name[0] }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="code" class="db-field-title required">{{ $t("label.code") }}</label>
                        <input v-model="props.form.code" v-bind:class="errors.code ? 'invalid' : ''" type="text"
                            id="code" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.code">{{
                            errors.code[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="discount" class="db-field-title required">{{
                            $t("label.discount")
                        }}</label>
                        <input v-model="props.form.discount" v-on:keypress="floatNumber($event)"
                            v-bind:class="errors.discount ? 'invalid' : ''" type="text" id="discount"
                            class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.discount">{{ errors.discount[0] }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="active">{{ $t("label.discount_type") }}</label>
                        <div class="db-field-radio-group">
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.taxTypeEnum.FIXED" v-model="props.form.discount_type"
                                        id="fixed" type="radio" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="fixed" class="db-field-label">{{
                                    $t("label.fixed")
                                }}</label>
                            </div>
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.taxTypeEnum.PERCENTAGE" v-model="props.form.discount_type"
                                        type="radio" id="percentage" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="percentage" class="db-field-label">{{ $t("label.percentage") }}</label>
                            </div>
                        </div>
                        <small class="db-field-alert" v-if="errors.discount_type">{{ errors.discount_type[0] }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="start_date" class="db-field-title required">{{ $t("label.start_date") }}</label>
                        <Datepicker hideInputIcon autoApply v-model="props.form.start_date" :enableTimePicker="true"
                            :is24="false" :monthChangeOnScroll="false" utc="false"
                            :input-class-name="errors.start_date ? 'invalid' : ''">
                            <template #am-pm-button="{ toggle, value }">
                                <button @click="toggle">{{ value }}</button>
                            </template>
                        </Datepicker>
                        <small class="db-field-alert" v-if="errors.start_date">{{ errors.start_date[0] }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="end_date" class="db-field-title required">{{ $t("label.end_date") }}</label>
                        <Datepicker hideInputIcon autoApply v-model="props.form.end_date" :enableTimePicker="true"
                            :is24="false" :monthChangeOnScroll="false" utc="false"
                            :input-class-name="errors.end_date ? 'invalid' : ''">
                            <template #am-pm-button="{ toggle, value }">
                                <button @click="toggle">{{ value }}</button>
                            </template>
                        </Datepicker>
                        <small class="db-field-alert" v-if="errors.end_date">{{ errors.end_date[0] }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="minimum_order" class="db-field-title required">{{
                            $t("label.minimum_order")
                        }}</label>
                        <input v-model="props.form.minimum_order" v-on:keypress="floatNumber($event)"
                            v-bind:class="errors.minimum_order ? 'invalid' : ''" type="text" id="minimum_order"
                            class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.minimum_order">{{ errors.minimum_order[0] }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="maximum_discount" class="db-field-title required">{{
                            $t("label.maximum_discount")
                        }}</label>
                        <input v-model="props.form.maximum_discount" v-on:keypress="floatNumber($event)"
                            v-bind:class="errors.maximum_discount ? 'invalid' : ''" type="text" id="maximum_discount"
                            class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.maximum_discount">{{
                            errors.maximum_discount[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="limit_per_user" class="db-field-title">{{ $t("label.limit_per_user") }}</label>
                        <input v-model="props.form.limit_per_user" v-on:keypress="floatNumber($event)" v-bind:class="
                            errors.limit_per_user ? 'invalid' : ''
                        " type="text" id="limit_per_user" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.limit_per_user">{{
                            errors.limit_per_user[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required">{{ $t("label.image") }}</label>
                        <input @change="changeImage" v-bind:class="errors.image ? 'invalid' : ''" id="image" type="file"
                            class="db-field-control" ref="imageProperty" accept="image/png, image/jpeg, image/jpg" />
                        <small class="db-field-alert" v-if="errors.image">{{ errors.image[0] }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-12">
                        <label for="description" class="db-field-title">{{
                            $t("label.description")
                        }}</label>
                        <textarea v-model="props.form.description" v-bind:class="errors.description ? 'invalid' : ''"
                            id="description" class="db-field-control"></textarea>
                        <small class="db-field-alert" v-if="errors.description">{{ errors.description[0] }}</small>
                    </div>
                    <div class="form-col-12">
                        <div class="flex flex-wrap gap-3 mt-4">
                            <button type="submit" class="db-btn py-2 text-white bg-primary">
                                <i class="lab lab-save"></i>
                                <span>{{ $t("label.save") }}</span>
                            </button>
                            <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                <i class="lab lab-close"></i>
                                <span>{{ $t("button.close") }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
import SmSidebarModalCreateComponent from "../components/buttons/SmSidebarModalCreateComponent";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import LoadingComponent from "../components/LoadingComponent";
import taxTypeEnum from "../../../enums/modules/taxTypeEnum";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";

export default {
    name: "CouponCreateComponent",
    components: { SmSidebarModalCreateComponent, LoadingComponent, Datepicker },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            addButton: {
                title: this.$t("button.add_coupon"),
            },
            enums: {
                taxTypeEnum: taxTypeEnum,
                taxTypeEnumArray: {
                    [taxTypeEnum.FIXED]: this.$t("label.fixed"),
                    [taxTypeEnum.PERCENTAGE]: this.$t("label.percentage"),
                },
            },
            image: "",
            errors: {},
        };
    },
    computed: {},
    methods: {
        floatNumber(e) {
            return appService.floatNumber(e);
        },
        changeImage: function (e) {
            this.image = e.target.files[0];
        },
        reset: function () {
            appService.sideDrawerHide();
            this.$store.dispatch("coupon/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                name: "",
                description: "",
                code: "",
                discount: "",
                discount_type: taxTypeEnum.FIXED,
                start_date: "",
                end_date: "",
                minimum_order: "",
                maximum_discount: "",
                limit_per_user: "",
            };
            if (this.image) {
                this.image = "";
                this.$refs.imageProperty.value = null;
            }
        },

        save: function () {
            try {
                const fd = new FormData();
                fd.append("name", this.props.form.name);
                fd.append("description", this.props.form.description);
                fd.append("code", this.props.form.code);
                fd.append("discount", this.props.form.discount);
                fd.append("discount_type", this.props.form.discount_type);
                fd.append("start_date", this.props.form.start_date);
                fd.append("end_date", this.props.form.end_date);
                fd.append("minimum_order", this.props.form.minimum_order);
                fd.append("maximum_discount", this.props.form.maximum_discount);
                fd.append("limit_per_user", this.props.form.limit_per_user);
                if (this.image) {
                    fd.append("image", this.image);
                }
                const tempId = this.$store.getters["coupon/temp"].temp_id;
                this.loading.isActive = true;
                this.$store
                    .dispatch("coupon/save", {
                        form: fd,
                        search: this.props.search,
                    })
                    .then((res) => {
                        appService.sideDrawerHide();
                        this.loading.isActive = false;
                        alertService.successFlip(
                            tempId === null ? 0 : 1,
                            this.$t("menu.coupons")
                        );
                        this.props.form = {
                            name: "",
                            description: "",
                            code: "",
                            discount: "",
                            discount_type: taxTypeEnum.FIXED,
                            start_date: "",
                            end_date: "",
                            minimum_order: "",
                            maximum_discount: "",
                            limit_per_user: "",
                        };
                        this.image = "";
                        this.errors = {};
                        this.$refs.imageProperty.value = null;
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
    },
};
</script>
