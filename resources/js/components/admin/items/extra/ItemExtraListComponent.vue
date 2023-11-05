<template>
    <ItemExtraCreateComponent :props="extraProps" />
    <br><br>
    <div class="db-card" v-if="extras.length > 0">
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
                <tbody class="db-table-body" v-if="extras.length > 0">
                    <tr class="db-table-body-tr" v-for="extra in extras" :key="extra">
                        <td class="db-table-body-td">
                            {{ extra.name }}
                        </td>
                        <td class="db-table-body-td">
                            {{ extra.flat_price }}
                        </td>
                        <td class="db-table-body-td">
                            <span :class="statusClass(extra.status)">
                                {{ enums.statusEnumArray[extra.status] }}
                            </span>
                        </td>
                        <td class="db-table-body-td">
                            <SmIconModalEditComponent @click="edit(extra)" />
                            <SmIconDeleteComponent @click="destroy(extra.id)" />
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
import ItemExtraCreateComponent from "./ItemExtraCreateComponent";

export default {
    name: "ItemVariationListComponent",
    components: {
        ItemExtraCreateComponent, SmSidebarModalCreateComponent, SmIconModalEditComponent, SmIconDeleteComponent
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
            extraProps: {
                id: 0,
                form: {
                    name: "",
                    price: null,
                    status: statusEnum.ACTIVE
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    order_type: 'desc',
                }
            }
        }
    },
    mounted() {
        this.extraProps.id = this.item;
        this.extraProps.search.id = this.item;
        this.list();
    },
    computed: {
        extras: function () {
            return this.$store.getters['itemExtra/lists'];
        },
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        list: function () {
            this.loading.isActive = true;
            this.$store.dispatch("itemExtra/lists", this.extraProps.search).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        edit: function (itemExtra) {
            appService.modalShow('#extraModal');
            this.loading.isActive = true;
            this.$store.dispatch('itemExtra/edit', itemExtra.id);
            this.loading.isActive = false;
            this.extraProps.form = {
                name: itemExtra.name,
                price: itemExtra.flat_price,
                status: itemExtra.status,
            };
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('itemExtra/destroy', { item: this.item, id: id, search: this.extraProps.search }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, this.$t('label.extra'));
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
