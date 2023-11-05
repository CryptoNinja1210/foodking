<template>
    <LoadingComponent :props="loading" />
    <div id="sms" class="db-tab-div active">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 mb-5">
            <button @click="selectActive(index)"
                class="db-tab-sub-btn w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5"
                :data-tab="'#' + smsGateway.slug" v-for="(smsGateway, index) in smsGateways.slice(0,3)" :key="smsGateway"
                :class="index === selectIndex ? 'active' : ''">
                <span class="capitalize whitespace-nowrap text-[15px]">
                    {{ smsGateway.name }}
                </span>
            </button>

            <div v-if="smsGateways.length > 3" class="dropdown-group w-full">
                <button
                    class="dropdown-btn w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                    <i class="fa-solid fa-circle-chevron-down text-sm"></i>
                    <span class="capitalize whitespace-nowrap text-[15px]"> {{ $t('label.more_gateway') }}</span>
                </button>
                <div class="w-full dropdown-list absolute top-[42px] right-0 z-30 p-2 rounded-md bg-white shadow-lg">
                    <button @click="selectActive(index+3)"
                            class="db-tab-sub-btn w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5"
                            :data-tab="'#' + smsGateway.slug" v-for="(smsGateway, index) in smsGateways.slice(3, smsGateways.length)" :key="smsGateway"
                            :class="index+3 === selectIndex ? 'active' : ''">
                        {{ smsGateway.name }}
                    </button>
                </div>
            </div>
        </div>
        <div :id="smsGateway.slug" class="db-card db-tab-sub-div" v-for="(smsGateway, index) in smsGateways"
            :key="smsGateway" :class="index === selectIndex ? 'active' : ''">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ smsGateway.name }}</h3>
            </div>
            <div class="db-card-body">
                <form @submit.prevent="save(index)" :id="'formElem' + index">
                    <div class="form-row">
                        <input type="hidden" :value="smsGateway.slug" name="sms_type">

                        <div class="form-col-12 sm:form-col-6" v-for="smsGatewayOption in smsGateway.options"
                            :key="smsGatewayOption">
                            <label :for="smsGatewayOption.option" class="db-field-title">
                                {{ $t("label." + smsGatewayOption.option) }}
                            </label>
                            <input v-if="smsGatewayOption.type === enums.inputTypeEnum.TEXT" type="text"
                                :value="smsGatewayOption.value"
                                v-bind:class="errors[smsGatewayOption.option] ? 'invalid' : ''"
                                :name="smsGatewayOption.option" :id="smsGatewayOption.option" class="db-field-control" />

                            <select v-else class="db-field-control" :id="smsGatewayOption.option"
                                :name="smsGatewayOption.option"
                                v-bind:class="errors[smsGatewayOption.option] ? 'invalid' : ''">
                                <option :value="index" :selected="index === smsGatewayOption.value"
                                    v-for="(activity, index) in smsGatewayOption.activities">
                                    {{ $t("label." + activity) }}
                                </option>
                            </select>

                            <small class="db-field-alert" v-if="errors[smsGatewayOption.option]">{{
                                errors[smsGatewayOption.option][0]
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
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent";
import appService from "../../../../services/appService";
import alertService from "../../../../services/alertService";
import inputTypeEnum from "../../../../enums/modules/inputTypeEnum";

export default {
    name: "SmsGatewayComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            search: {
                paginate: 0,
                order_column: "id",
                order_type: "asc"
            },
            selectIndex: 0,
            enums: {
                inputTypeEnum: inputTypeEnum
            },
            errors: {},
        };
    },
    computed: {
        smsGateways: function () {
            return this.$store.getters["smsGateway/lists"];
        },
    },
    mounted() {
        try {
            this.loading.isActive = true;
            this.$store.dispatch("smsGateway/lists", this.search).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        } catch (err) {
            this.loading.isActive = false;
        }
    },
    methods: {
        save: function (index) {
            try {
                const form = document.getElementById("formElem" + index);
                const formDataObj = {};
                [...form.elements].filter((el) => el.tagName !== 'BUTTON').forEach((item) => {
                    formDataObj[item.name] = item.value;
                });

                this.loading.isActive = true;
                this.$store.dispatch("smsGateway/save", { form: formDataObj, search: this.search }).then((res) => {
                    this.loading.isActive = false;
                    alertService.successFlip(res.config.method === "put" ?? 0, this.$t("menu.sms_gateway"));
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
    },
};
</script>
