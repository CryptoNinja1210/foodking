<template>
    <OfferItemCreateComponent :props="offerProps" />
    <br><br>
    <div class="db-card" v-if="offerItems.length > 0">
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
                <tbody class="db-table-body" v-if="offerItems.length > 0">
                    <tr class="db-table-body-tr" v-for="offerItem in offerItems" :key="offerItem">
                        <td class="db-table-body-td">
                            {{ offerItem.offer_item_name }}
                        </td>
                        <td class="db-table-body-td">
                            {{ offerItem.offer_item_flat_price }}
                        </td>
                        <td class="db-table-body-td">
                            <span :class="statusClass(offerItem.offer_item_status)">
                                {{ enums.statusEnumArray[offerItem.offer_item_status] }}
                            </span>
                        </td>
                        <td class="db-table-body-td">
                            <SmIconDeleteComponent @click="destroy(offerItem.id)" />
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
import OfferItemCreateComponent from "./OfferItemCreateComponent";

export default {
    name: "OfferItemListComponent",
    components: {
        OfferItemCreateComponent, SmSidebarModalCreateComponent, SmIconModalEditComponent, SmIconDeleteComponent
    },
    props: {
        offer: { type: Number },
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
            offerProps: {
                id: this.offer,
                form: {
                    item_id: null,
                },
                search: {
                    id: this.offer,
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'desc',
                }
            },
        }
    },
    mounted() {
        this.list();
    },
    computed: {
        offerItems: function () {
            return this.$store.getters['offerItem/lists'];
        }
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        list: function () {
            this.loading.isActive = true;
            this.$store.dispatch("offerItem/lists", this.offerProps.search).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('offerItem/destroy', { offer: this.offer, id: id, search: this.offerProps.search }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, this.$t('label.item'));
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
