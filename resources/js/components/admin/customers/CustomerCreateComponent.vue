<template>
    <LoadingComponent :props="loading"/>
    <SmSidebarModalCreateComponent :props="addButton"/>

    <div id="sidebar" class="drawer">
        <div class="drawer-header">
            <h3 class="drawer-title">{{ $t("menu.customers") }}</h3>
            <button class="fa-solid fa-xmark close-btn" @click="reset"></button>
        </div>
        <div class="drawer-body">
            <form @submit.prevent="save">
                <div class="form-row">
                    <div class="form-col-12 sm:form-col-6">
                        <label for="name" class="db-field-title required">{{
                                $t("label.name")
                            }}</label>
                        <input v-model="props.form.name" v-bind:class="errors.name ? 'invalid' : ''" type="text"
                               id="name"
                               class="db-field-control"/>
                        <small class="db-field-alert" v-if="errors.name">{{
                                errors.name[0]
                            }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="email" class="db-field-title required">{{
                                $t("label.email")
                            }}</label>
                        <input v-model="props.form.email" v-bind:class="errors.email ? 'invalid' : ''" type="text"
                               id="email" class="db-field-control"/>
                        <small class="db-field-alert" v-if="errors.email">{{
                                errors.email[0]
                            }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="phone" class="text-xs capitalize mb-1 text-heading">{{ $t('label.phone') }}</label>
                        <div :class="errors.phone ? 'invalid' : ''" class="db-field-control flex items-center">
                            <div class="w-fit flex-shrink-0 dropdown-group">
                                <button type="button" class="flex items-center gap-1 dropdown-btn">
                                    {{ flag }}
                                    <span class="whitespace-nowrap flex-shrink-0 text-xs">
                                        {{ props.form.country_code }}
                                    </span>
                                    <input type="hidden" v-model="props.form.country_code">
                                </button>
                            </div>
                            <input v-model="props.form.phone" v-on:keypress="phoneNumber($event)" v-bind:class="errors.phone
                                ? 'invalid' : ''" type="text" id="phone"
                                   class="pl-2 text-sm w-full h-full"/>
                        </div>
                        <small class="db-field-alert" v-if="errors.phone">
                            {{ errors.phone[0] }}
                        </small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="active">
                            {{ $t("label.status") }}
                        </label>
                        <div class="db-field-radio-group">
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.statusEnum.ACTIVE" v-model="props.form.status" id="active"
                                           type="radio" class="custom-radio-field"/>
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="active" class="db-field-label">{{
                                        $t("label.active")
                                    }}</label>
                            </div>
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.statusEnum.INACTIVE" v-model="props.form.status" type="radio"
                                           id="inactive" class="custom-radio-field"/>
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="inactive" class="db-field-label">{{
                                        $t("label.inactive")
                                    }}</label>
                            </div>
                        </div>
                        <small class="db-field-alert" v-if="errors.status">{{
                                errors.status[0]
                            }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="password" class="db-field-title required">{{
                                $t("label.password")
                            }}</label>
                        <input v-model="props.form.password" v-bind:class="errors.password ? 'invalid' : ''"
                               type="password"
                               id="password" class="db-field-control" autocomplete="off"/>
                        <small class="db-field-alert" v-if="errors.password">{{
                                errors.password[0]
                            }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="password_confirmation" class="db-field-title required">{{
                                $t("label.confirm_password")
                            }}</label>
                        <input v-model="props.form.password_confirmation" v-bind:class="
                            errors.password_confirmation ? 'invalid' : ''
                        " type="password" id="password_confirmation" class="db-field-control" autocomplete="off"/>
                        <small class="db-field-alert" v-if="errors.password_confirmation">{{
                                errors.password_confirmation[0]
                            }}</small>
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
import LoadingComponent from "../components/LoadingComponent";
import statusEnum from "../../../enums/modules/statusEnum";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";

export default {
    name: "CustomerCreateComponent",
    components: {SmSidebarModalCreateComponent, LoadingComponent},
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            addButton: {
                title: this.$t("button.add_customer"),
            },
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
            errors: {},
            flag: "",
            country_code: "",
        };
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch('company/lists').then(companyRes => {
            this.$store.dispatch('countryCode/show', companyRes.data.data.company_country_code).then(res => {

                if (this.props.form.country_code === "") {
                    this.props.form.country_code = res.data.data.calling_code;
                    this.country_code = res.data.data.calling_code;
                }
                this.flag = res.data.data.flag_emoji;
                this.loading.isActive = false;

            }).catch((err) => {
                this.loading.isActive = false;

            });
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },
    methods: {
        phoneNumber(e) {
            return appService.phoneNumber(e);
        },
        reset: function () {
            appService.sideDrawerHide();
            this.$store.dispatch("customer/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                name: "",
                email: "",
                phone: "",
                password: "",
                password_confirmation: "",
                status: statusEnum.ACTIVE,
                country_code: this.country_code,
            };
        },

        save: function () {
            try {

                const tempId = this.$store.getters["customer/temp"].temp_id;
                this.loading.isActive = true;
                this.$store
                    .dispatch("customer/save", this.props)
                    .then((res) => {
                        appService.sideDrawerHide();
                        this.loading.isActive = false;
                        alertService.successFlip(
                            tempId === null ? 0 : 1,
                            this.$t("menu.customers")
                        );
                        this.props.form = {
                            name: "",
                            email: "",
                            phone: "",
                            password: "",
                            password_confirmation: "",
                            status: statusEnum.ACTIVE,
                            country_code: this.country_code,
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
    },
};
</script>
