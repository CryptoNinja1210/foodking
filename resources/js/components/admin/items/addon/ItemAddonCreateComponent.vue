<template>
    <LoadingComponent :props="loading" />

    <button type="button" @click="add" data-modal="#addonModal" class="db-btn h-[37px] text-white bg-primary">
        <i class="lab lab-add-circle-line"></i>
        <span>{{ addButton.title }}</span>
    </button>

    <div id="addonModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("menu.addons") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="addon_item_id" class="db-field-title required">
                                {{ $t("label.addon_item") }}
                            </label>
                            <vue-select class="db-field-control f-b-custom-select" id="site_default_branch"
                                @update:modelValue="variation" v-bind:class="errors.addon_item_id ? 'invalid' : ''"
                                v-model="props.form.addon_item_id" :options="items" label-by="name" value-by="id"
                                :closeOnSelect="true" :searchable="true" :clearOnClose="true" placeholder="--"
                                search-placeholder="--" />
                            <small class="db-field-alert" v-if="errors.addon_item_id">
                                {{ errors.addon_item_id[0] }}
                            </small>
                        </div>
                        <div class="form-col-12 sm:form-col-6" v-if="variations.length > 0" v-for="variation in variations"
                            :key="variation">
                            <label :for="'addon_item_id_' + variation.item_attribute_id" class="db-field-title">
                                {{ variation.name }}
                            </label>

                            <select class="db-field-control f-b-custom-select"
                                :id="'addon_item_id_' + variation.item_attribute_id"
                                v-model="props.form.addon_item_variation[variation.item_attribute_id]">
                                <option value="0">--</option>
                                <option v-for="child in variation.children" :value="child.id">{{ child.name }}
                                </option>

                            </select>
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
    name: "ItemAddonCreateComponent",
    components: { LoadingComponent },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            addButton: {
                title: this.$t("button.add_addon")
            },
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
            errors: {},
            variations: [],
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
            status: statusEnum.ACTIVE,
            except: this.props.id
        });
        this.loading.isActive = false;
    },
    methods: {
        add: function () {
            appService.modalShow('#addonModal');
        },
        numberOnly: function (e) {
            return appService.floatNumber(e);
        },
        reset: function () {
            appService.modalHide('#addonModal');
            this.$store.dispatch("itemAddon/reset").then().catch();
            this.errors = {};
            this.variations = [];
            this.$props.props.form = {
                addon_item_id: null,
                addon_item_variation: {},
            };
        },
        variation: function (id) {
            this.loading.isActive = true;
            this.$props.props.form = {
                addon_item_id: null,
                addon_item_variation: {},
            };
            this.errors = {};
            this.$store.dispatch("itemVariation/listGroupByAttributes", {
                id: id,
                paginate: 0,
                order_column: 'id',
                order_type: 'asc',
                vuex: false
            }).then((res) => {
                this.variations = res.data.data;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            });
        },
        save: function () {
            try {
                const tempId = this.$store.getters["itemAddon/temp"].temp_id;
                this.loading.isActive = true;
                if (this.props.form.addon_item_variation) {

                    this.props.form.addon_item_variation = JSON.stringify(this.props.form.addon_item_variation);
                }
                this.$store.dispatch("itemAddon/save", this.props).then((res) => {
                    appService.modalHide();
                    this.loading.isActive = false;
                    alertService.successFlip(
                        tempId === null ? 0 : 1,
                        this.$t("label.addon")
                    );
                    this.props.form = {
                        addon_item_id: null,
                        addon_item_variation: {},
                    };
                    this.variations = [],
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
