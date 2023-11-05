<template>
    <LoadingComponent :props="loading" />
    <SmModalCreateComponent :props="addButton" />

    <div id="modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("menu.currencies") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="name" class="db-field-title required">{{
                                $t("label.name")
                            }}</label>
                            <input v-model="props.form.name" v-bind:class="errors.name ? 'invalid' : ''" type="text"
                                id="name" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.name">{{
                                errors.name[0]
                            }}</small>
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label for="symbol" class="db-field-title required">{{
                                $t("label.symbol")
                            }}</label>
                            <input v-model="props.form.symbol" v-bind:class="errors.symbol ? 'invalid' : ''" type="text"
                                id="symbol" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.symbol">{{ errors.symbol[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="code" class="db-field-title required">{{
                                $t("label.code")
                            }}</label>
                            <input v-model="props.form.code" v-bind:class="errors.code ? 'invalid' : ''" type="text"
                                id="code" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.code">{{
                                errors.code[0]
                            }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="yes">{{ $t("label.is_cryptocurrency") }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.askEnum.YES" v-model="props.form.is_cryptocurrency"
                                            id="yes" type="radio" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="yes" class="db-field-label">{{
                                        $t("label.yes")
                                    }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.askEnum.NO" v-model="props.form.is_cryptocurrency"
                                            type="radio" id="no" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="no" class="db-field-label">{{
                                        $t("label.no")
                                    }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="exchange_rate" class="db-field-title">{{
                                $t("label.exchange_rate")
                            }}</label>
                            <input v-model="props.form.exchange_rate" v-bind:class="
                                errors.exchange_rate ? 'invalid' : ''
                            " type="text" id="exchange_rate" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.exchange_rate">{{
                                errors.exchange_rate[0]
                            }}</small>
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
import SmModalCreateComponent from "../../components/buttons/SmModalCreateComponent";
import LoadingComponent from "../../components/LoadingComponent";
import askEnum from "../../../../enums/modules/askEnum";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";

export default {
    name: "CurrencyCreateComponent",
    components: { SmModalCreateComponent, LoadingComponent },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            addButton: {
                title: this.$t("button.add_currency"),
            },
            enums: {
                askEnum: askEnum,
                askEnumArray: {
                    [askEnum.YES]: this.$t("label.yes"),
                    [askEnum.NO]: this.$t("label.no"),
                },
            },
            errors: {},
        };
    },
    methods: {
        reset: function () {
            appService.modalHide();
            this.$store.dispatch("currency/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                name: "",
                symbol: "",
                code: "",
                is_cryptocurrency: askEnum.NO,
                exchange_rate: "",
            };
        },

        save: function () {
            try {
                const tempId = this.$store.getters["currency/temp"].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch("currency/save", this.props).then((res) => {
                    appService.modalHide();
                    this.loading.isActive = false;
                    alertService.successFlip(
                        tempId === null ? 0 : 1,
                        this.$t("menu.currencies")
                    );
                    this.props.form = {
                        name: "",
                        symbol: "",
                        code: "",
                        is_cryptocurrency: askEnum.NO,
                        exchange_rate: "",
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
    },
};
</script>
