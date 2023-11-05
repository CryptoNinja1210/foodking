<template>
    <LoadingComponent :props="loading" />

    <button type="button" @click="add" data-modal="#addonModal" class="db-btn h-[37px] text-white bg-primary">
        <i class="lab lab-add-circle-line"></i>
        <span>{{ addButton.title }}</span>
    </button>

    <div id="addonModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("menu.items") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <div class="form-row" v-if="message">
                    <div class="form-col-12 db-field-alert">
                        {{ message }}
                    </div>
                </div>
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-12">
                            <label for="item_id" class="db-field-title required">
                                {{ $t("label.item") }}
                            </label>
                            <vue-select class="db-field-control f-b-custom-select" id="site_default_branch"
                                v-bind:class="errors.item_id ? 'invalid' : ''" v-model="props.form.item_id" :options="items"
                                label-by="name" value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                                placeholder="--" search-placeholder="--" />
                            <small class="db-field-alert" v-if="errors.item_id">
                                {{ errors.item_id[0] }}
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
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";
import statusEnum from "../../../../enums/modules/statusEnum";

export default {
    name: "OfferItemCreateComponent",
    components: { LoadingComponent },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            addButton: {
                title: this.$t("button.add_item")
            },
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
            errors: {},
            message: null,

        };
    },
    computed: {
        items: function () {
            return this.$store.getters['item/lists'];
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch('item/lists', {
            paginate: 0,
            order_column: 'id',
            order_type: 'asc',
            status: statusEnum.ACTIVE
        });
        this.loading.isActive = false;
    },
    methods: {
        add: function () {
            appService.modalShow();
        },
        numberOnly: function (e) {
            return appService.floatNumber(e);
        },
        reset: function () {
            appService.modalHide();
            this.$store.dispatch("offerItem/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                item_id: null,
            };
            this.message = null;
        },
        save: function () {
            try {
                const tempId = this.$store.getters["offerItem/temp"].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch("offerItem/save", this.props).then((res) => {
                    appService.modalHide();
                    this.loading.isActive = false;
                    alertService.successFlip(
                        tempId === null ? 0 : 1,
                        this.$t("label.item")
                    );
                    this.props.form = {
                        item_id: null,
                    };
                    this.errors = {};
                    this.message = null;
                }).catch((err) => {
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
    }
};
</script>
