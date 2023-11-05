<template>
    <ItemAddonCreateComponent :props="addonProps" />
    <br><br>
    <div class="db-card" v-if="addons.length > 0">
        <div class="db-table-responsive">
            <table class="db-table stripe">
                <thead class="db-table-head">
                    <tr class="db-table-head-tr">
                        <th class="db-table-head-th">{{ $t("label.name") }}</th>
                        <th class="db-table-head-th">{{ $t("label.price") }}</th>
                        <th class="db-table-head-th">{{ $t("label.status") }}</th>
                        <th class="db-table-head-th">{{ $t("label.action") }}</th>
                    </tr>
                </thead>
                <tbody class="db-table-body" v-if="addons.length > 0">
                    <tr class="db-table-body-tr" v-for="addon in addons" :key="addon">
                        <td class="db-table-body-td">
                            {{ addon.addon_item_name }}<br>
                            <span v-if="addon.variation_names.length > 0"
                                v-for="(variationName, index) in addon.variation_names">
                                <span>{{ variationName.attribute_name }} : {{ variationName.name }}
                                    <span v-if="index + 1 < addon.variation_names.length">, </span>
                                </span>

                            </span>
                        </td>
                        <td class="db-table-body-td">
                            {{ addon.total_flat_price }}
                        </td>
                        <td class="db-table-body-td">
                            <span :class="statusClass(addon.addon_item_status)">
                                {{ enums.statusEnumArray[addon.addon_item_status] }}
                            </span>
                        </td>
                        <td class="db-table-body-td">
                            <SmIconDeleteComponent @click="destroy(addon.id)" />
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
import ItemAddonCreateComponent from "./ItemAddonCreateComponent";

export default {
    name: "ItemAddonListComponent",
    components: {
        ItemAddonCreateComponent, SmSidebarModalCreateComponent, SmIconModalEditComponent, SmIconDeleteComponent
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
            addonProps: {
                id: this.item,
                form: {
                    addon_item_id: null,
                    addon_item_variation: {},
                },
                search: {
                    id: this.item,
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'desc',
                }
            },
            variations: [],
        }
    },
    mounted() {
        this.list();
    },
    computed: {
        addons: function () {
            return this.$store.getters['itemAddon/lists'];
        }
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        list: function () {
            this.loading.isActive = true;
            this.$store.dispatch("itemAddon/lists", this.addonProps.search).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('itemAddon/destroy', { item: this.item, id: id, search: this.addonProps.search }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, this.$t('label.addon'));
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
