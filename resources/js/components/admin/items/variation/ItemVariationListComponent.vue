<template>
    <ItemVariationCreateComponent :props="variationProps" />
    <br><br>
    <div class="db-card mb-5" v-if="variations.length > 0" v-for="variation in variations" :key="variation">
        <div class="db-card-header border-none">
            <h3 class="db-card-title">{{ variation.name }}</h3>
        </div>
        <div class="db-table-responsive">
            <table class="db-table stripe">
                <thead class="db-table-head">
                    <tr class="db-table-head-tr">
                        <th class="db-table-head-th">{{ $t("label.name") }}</th>
                        <th class="db-table-head-th">{{ $t("label.additional_price") }}</th>
                        <th class="db-table-head-th">{{ $t("label.status") }}</th>
                        <th class="db-table-head-th">{{ $t("label.action") }}</th>
                    </tr>
                </thead>
                <tbody class="db-table-body" v-if="variation.children">
                    <tr class="db-table-body-tr" v-for="child in variation.children" :key="child">
                        <td class="db-table-body-td">
                            {{ child.name }}
                        </td>
                        <td class="db-table-body-td">
                            {{ child.flat_price }}
                        </td>
                        <td class="db-table-body-td">
                            <span :class="statusClass(child.status)">
                                {{ enums.statusEnumArray[child.status] }}
                            </span>
                        </td>
                        <td class="db-table-body-td">
                            <SmIconModalEditComponent @click="edit(child)" />
                            <SmIconDeleteComponent @click="destroy(child.id)" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import SmSidebarModalCreateComponent from "../../components/buttons/SmSidebarModalCreateComponent";
import alertService from "../../../../services/alertService";
import statusEnum from "../../../../enums/modules/statusEnum";
import appService from "../../../../services/appService";
import SmIconDeleteComponent from "../../components/buttons/SmIconDeleteComponent";
import SmIconModalEditComponent from "../../components/buttons/SmIconModalEditComponent";
import ItemVariationCreateComponent from "./ItemVariationCreateComponent";

export default {
    name: "ItemVariationListComponent",
    components: {
        ItemVariationCreateComponent, SmSidebarModalCreateComponent, SmIconModalEditComponent, SmIconDeleteComponent
    },
    props: {
        item: { type: Number },
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
            variationProps: {
                id: 0,
                form: {
                    name: "",
                    price: null,
                    item_attribute_id: null,
                    caution: "",
                    status: statusEnum.ACTIVE
                },
                search: {
                    id: 0,
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'asc',
                }
            }
        }
    },
    mounted() {
        this.variationProps.id = this.item;
        this.variationProps.search.id = this.item;
        this.list();
    },
    computed: {
        variations: function () {
            return this.$store.getters['itemVariation/listGroupByAttributes'];
        }
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        list: function () {
            this.loading.isActive = true;
            this.$store.dispatch("itemVariation/listGroupByAttributes", this.variationProps.search).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        edit: function (itemVariation) {
            appService.modalShow();
            this.loading.isActive = true;
            this.$store.dispatch('itemVariation/edit', itemVariation.id);
            this.loading.isActive = false;
            this.variationProps.form = {
                name: itemVariation.name,
                price: itemVariation.flat_price,
                item_attribute_id: itemVariation.item_attribute_id,
                caution: itemVariation.caution,
                status: itemVariation.status
            };
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('itemVariation/destroy', { item: this.item, id: id, search: this.variationProps.search }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, this.$t('label.variation'));
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.message);
                    })
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }).catch((err) => {
                this.loading.isActive = false;
            });
        }
    }
}
</script>
