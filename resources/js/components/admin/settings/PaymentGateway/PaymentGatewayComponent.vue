<template>
    <LoadingComponent :props="loading"/>
    <div id="payment" class="db-tab-div active">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 mb-5">
            <button @click="selectActive(index)"
                    class="db-tab-sub-btn w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5"
                    :data-tab="'#' + paymentGateway.slug" v-for="(paymentGateway, index) in paymentGateways.slice(0,3)"
                    :key="paymentGateway" :class="index === selectIndex ? 'active' : ''">
                <span class="capitalize whitespace-nowrap text-[15px]">
                    {{ paymentGateway.name }}
                </span>
            </button>

            <div v-if="paymentGateways.length > 3" class="dropdown-group w-full">
                <button
                    class="dropdown-btn w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                    <i class="fa-solid fa-circle-chevron-down text-sm"></i>
                    <span class="capitalize whitespace-nowrap text-[15px]"> {{ $t('label.more_gateway') }}</span>
                </button>
                <div class="w-full dropdown-list absolute top-[42px] right-0 z-30 p-2 rounded-md bg-white shadow-lg">
                    <button @click="selectActive(index+3)"
                            class="db-tab-sub-btn w-full flex items-center whitespace-nowrap justify-start my-0.5 gap-2.5 pl-3 pr-6 py-1.5 text-sm rounded-md capitalize transition text-gray-500 hover:text-primary hover:bg-primary/5"
                            :data-tab="'#' + paymentGateway.slug"
                            v-for="(paymentGateway, index) in paymentGateways.slice(3, paymentGateways.length)"
                            :key="paymentGateway" :class="index+3 === selectIndex ? 'active' : ''">
                        {{ paymentGateway.name }}
                    </button>
                </div>
            </div>
        </div>
        <div :id="paymentGateway.slug" class="db-card db-tab-sub-div" v-for="(paymentGateway, index) in paymentGateways"
             :key="paymentGateway" :class="index === selectIndex ? 'active' : ''">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ paymentGateway.name }}</h3>
            </div>
            <div class="db-card-body">
                <form @submit.prevent="save(index)" :id="'formElem' + index">
                    <div class="form-row">
                        <input type="hidden" :value="paymentGateway.slug" name="payment_type">

                        <div class="form-col-12 sm:form-col-6" v-for="paymentGatewayOption in paymentGateway.options"
                             :key="paymentGatewayOption">
                            <label :for="paymentGatewayOption.option" class="db-field-title">
                                {{ $t("label." + paymentGatewayOption.option) }}
                            </label>
                            <input v-if="paymentGatewayOption.type === enums.inputTypeEnum.TEXT" type="text"
                                   :value="paymentGatewayOption.value"
                                   v-bind:class="errors[paymentGatewayOption.option] ? 'invalid' : ''"
                                   :name="paymentGatewayOption.option" :id="paymentGatewayOption.option"
                                   class="db-field-control"/>

                            <select v-else class="db-field-control" :id="paymentGatewayOption.option"
                                    :name="paymentGatewayOption.option"
                                    v-bind:class="errors[paymentGatewayOption.option] ? 'invalid' : ''">
                                <option :value="index" :selected="index === paymentGatewayOption.value"
                                        v-for="(activity, index) in paymentGatewayOption.activities">
                                    {{ $t("label." + activity) }}
                                </option>
                            </select>

                            <small class="db-field-alert" v-if="errors[paymentGatewayOption.option]">{{
                                    errors[paymentGatewayOption.option][0]
                                }}</small>
                        </div>
                        <div class="form-col-12">
                            <button type="submit" :id="'formButton' + index" class="db-btn text-white bg-primary">
                                <i class="lab lab-save"></i>
                                <span>{{ $t("button.save") }}</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent";
import appService from "../../../../services/appService";
import alertService from "../../../../services/alertService";
import inputTypeEnum from "../../../../enums/modules/inputTypeEnum";

export default {
    name: "PaymentGatewayComponent",
    components: {LoadingComponent},
    data() {
        return {
            loading: {
                isActive: false,
            },
            search: {
                paginate: 0,
                order_column: "id",
                order_type: "asc",
                excepts: "1|2"
            },
            selectIndex: 0,
            enums: {
                inputTypeEnum: inputTypeEnum
            },
            errors: {},

        };
    },
    computed: {
        paymentGateways: function () {
            return this.$store.getters["paymentGateway/lists"];
        },
    },
    mounted() {
        try {
            this.loading.isActive = true;
            this.$store.dispatch("paymentGateway/lists", this.search).then((res) => {
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
        save: function (index) {
            try {
                let form = document.getElementById("formElem" + index);
                let formDataObj = {};
                [...form.elements].filter((el) => el.tagName !== 'BUTTON').forEach((item) => {
                    formDataObj[item.name] = item.value;
                });

                this.loading.isActive = true;
                this.$store.dispatch("paymentGateway/save", {form: formDataObj, search: this.search}).then((res) => {
                    this.loading.isActive = false;
                    alertService.successFlip(res.config.method === "put" ?? 0, this.$t("menu.payment_gateway"));
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
        selectActive: function (index) {
            this.selectIndex = index;
        }
    }
};
</script>
