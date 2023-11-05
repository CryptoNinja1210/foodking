<template>
    <LoadingComponent :props="loading" />

    <button type="button" @click="add" data-modal="#extraModal" class="db-btn h-[37px] text-white bg-primary">
        <i class="lab lab-add-circle-line"></i>
        <span>{{ addButton.title }}</span>
    </button>

    <div id="extraModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("menu.extras") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="name" class="db-field-title required">{{ $t("label.name") }}</label>
                            <input v-model="props.form.name" v-bind:class="errors.name ? 'invalid' : ''" type="text"
                                id="name" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.name">{{ errors.name[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="price" class="db-field-title required">{{
                                $t("label.additional_price")
                            }}</label>
                            <input v-on:keypress="numberOnly($event)" v-model="props.form.price"
                                v-bind:class="errors.price ? 'invalid' : ''" type="text" id="price"
                                class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.price">{{ errors.price[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="extraActive">{{ $t("label.status") }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.ACTIVE" v-model="props.form.status" id="extraActive"
                                            type="radio" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="extraActive" class="db-field-label">{{ $t("label.active") }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.INACTIVE" v-model="props.form.status" type="radio"
                                            id="extraInactive" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="extraInactive" class="db-field-label">{{ $t("label.inactive") }}</label>
                                </div>
                            </div>
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
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";
import statusEnum from "../../../../enums/modules/statusEnum";

export default {
    name: "ItemExtraCreateComponent",
    components: { LoadingComponent },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            addButton: {
                title: this.$t("button.add_extra")
            },
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
            errors: {},
        };
    },
    computed: {},
    mounted() { },
    methods: {
        add: function () {
            appService.modalShow('#extraModal');
        },
        numberOnly: function (e) {
            return appService.floatNumber(e);
        },
        reset: function () {
            appService.modalHide('#extraModal');
            this.$store.dispatch("itemExtra/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                name: "",
                price: null,
                status: statusEnum.ACTIVE,
            };
        },
        save: function () {
            try {
                const tempId = this.$store.getters["itemExtra/temp"].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch("itemExtra/save", this.props).then((res) => {
                    appService.modalHide();
                    this.loading.isActive = false;
                    alertService.successFlip(
                        tempId === null ? 0 : 1,
                        this.$t("label.extra")
                    );
                    this.props.form = {
                        name: "",
                        price: null,
                        status: statusEnum.ACTIVE,
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
    }
};
</script>
