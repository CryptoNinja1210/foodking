<template>
    <LoadingComponent :props="loading"/>

    <div class="db-card db-tab-div active">
        <div class="db-card-header border-none">
            <h3 class="db-card-title">{{ $t("menu.taxes") }}</h3>
            <div class="db-card-filter">
                <TableLimitComponent :method="list" :search="props.search" :page="paginationPage"/>
                <TaxCreateComponent :props="props"/>
            </div>
        </div>

        <div class="db-table-responsive">
            <table class="db-table stripe">
                <thead class="db-table-head">
                <tr class="db-table-head-tr">
                    <th class="db-table-head-th">{{ $t("label.name") }}</th>
                    <th class="db-table-head-th">{{ $t("label.code") }}</th>
                    <th class="db-table-head-th">
                        {{ $t("label.tax_rate") }}
                    </th>
                    <th class="db-table-head-th">{{ $t('label.status') }}</th>
                    <th class="db-table-head-th">
                        {{ $t("label.action") }}
                    </th>
                </tr>
                </thead>
                <tbody class="db-table-body" v-if="taxes.length > 0">
                <tr class="db-table-body-tr" v-for="tax in taxes" :key="tax">
                    <td class="db-table-body-td">
                        {{ tax.name }}
                    </td>
                    <td class="db-table-body-td">
                        {{ tax.code }}
                    </td>
                    <td class="db-table-body-td">
                        {{ tax.tax_currency_rate }}
                    </td>
                    <td class="db-table-body-td">
                            <span :class="statusClass(tax.status)">
                                {{ enums.statusEnumArray[tax.status] }}
                            </span>
                    </td>
                    <td class="db-table-body-td">
                        <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                            <SmModalEditComponent @click="edit(tax)"/>
                            <SmDeleteComponent @click="destroy(tax.id)"/>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-6">
            <PaginationSMBox :pagination="pagination" :method="list"/>
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <PaginationTextComponent :props="{ page: paginationPage }"/>
                <PaginationBox :pagination="pagination" :method="list"/>
            </div>
        </div>
    </div>
</template>
<script>
import LoadingComponent from "../../components/LoadingComponent";
import TaxCreateComponent from "./TaxCreateComponent";
import alertService from "../../../../services/alertService";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent";
import PaginationBox from "../../components/pagination/PaginationBox";
import PaginationSMBox from "../../components/pagination/PaginationSMBox";
import appService from "../../../../services/appService";
import TableLimitComponent from "../../components/TableLimitComponent";
import SmDeleteComponent from "../../components/buttons/SmDeleteComponent";
import SmModalEditComponent from "../../components/buttons/SmModalEditComponent";
import taxTypeEnum from "../../../../enums/modules/taxTypeEnum";
import statusEnum from "../../../../enums/modules/statusEnum";

export default {
    name: "TaxListComponent",
    components: {
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        TaxCreateComponent,
        LoadingComponent,
        SmDeleteComponent,
        SmModalEditComponent,
        taxTypeEnum,
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                taxTypeEnum: taxTypeEnum,
                taxTypeEnumArray: {
                    [taxTypeEnum.FIXED]: this.$t("label.fixed"),
                    [taxTypeEnum.PERCENTAGE]: this.$t("label.percentage"),
                },
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive")
                }
            },
            props: {
                form: {
                    name: "",
                    code: "",
                    tax_rate: "",
                    status: statusEnum.ACTIVE,
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: "id",
                    order_type: "desc",
                },
            },
        };
    },
    mounted() {
        this.list();
    },
    computed: {
        taxes: function () {
            return this.$store.getters["tax/lists"];
        },
        pagination: function () {
            return this.$store.getters["tax/pagination"];
        },
        paginationPage: function () {
            return this.$store.getters["tax/page"];
        },
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store
                .dispatch("tax/lists", this.props.search)
                .then((res) => {
                    this.loading.isActive = false;
                })
                .catch((err) => {
                    this.loading.isActive = false;
                });
        },
        edit: function (tax) {
            appService.modalShow();
            this.loading.isActive = true;
            this.$store.dispatch("tax/edit", tax.id);
            this.props.form = {
                name: tax.name,
                code: tax.code,
                tax_rate: tax.tax_currency_rate,
                status: tax.status,
            };
            this.loading.isActive = false;
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch("tax/destroy", {
                        id: id,
                        search: this.props.search,
                    }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(
                            null,
                            this.$t("menu.taxes")
                        );
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.message);
                    });
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
    },
};
</script>
