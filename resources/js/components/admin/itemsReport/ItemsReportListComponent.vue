<template>
    <LoadingComponent :props="loading" />
    <div class="col-12">
        <div class="db-card db-tab-div active">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ $t('menu.items_report') }}</h3>
                <div class="db-card-filter">
                    <TableLimitComponent :method="list" :search="props.search" :page="paginationPage" />
                    <FilterComponent />
                    <div class="dropdown-group">
                        <ExportComponent />
                        <div class="dropdown-list db-card-filter-dropdown-list">
                            <PrintComponent :props="printObj" />
                            <ExcelComponent :method="xls" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-filter-div">
                <form class="p-4 sm:p-5 mb-5" @submit.prevent="search">
                    <div class="row">

                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="item_category_id" class="db-field-title required">{{
                                $t("label.category")
                            }}</label>

                            <vue-select class="db-field-control f-b-custom-select" id="item_category_id"
                                v-model="props.search.item_category_id" :options="itemCategories" label-by="name"
                                value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true" placeholder="--"
                                search-placeholder="--" />
                        </div>

                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="item_type" class="db-field-title after:hidden">{{
                                $t('label.type')
                            }}</label>
                            <vue-select class="db-field-control f-b-custom-select" id="item_type"
                                v-model="props.search.item_type" :options="[
                                    { id: enums.itemTypeEnum.VEG, name: $t('label.veg') },
                                    { id: enums.itemTypeEnum.NON_VEG, name: $t('label.non_veg') }
                                ]" label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"
                                :clearOnClose="true" placeholder="--" search-placeholder="--" />
                        </div>

                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="name" class="db-field-title required">{{
                                $t("label.name")
                            }}</label>

                            <vue-select class="db-field-control f-b-custom-select" id="name" v-model="props.search.name"
                                :options="items" label-by="name" value-by="name" :closeOnSelect="true" :searchable="true"
                                :clearOnClose="true" placeholder="--" search-placeholder="--" />
                        </div>

                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchStartDate" class="db-field-title after:hidden">{{
                                $t('label.date')
                            }}</label>
                            <Datepicker hideInputIcon autoApply :enableTimePicker="false" utc="false"
                                @update:modelValue="handleDate" v-model="props.form.date" range
                                :preset-ranges="presetRanges">
                                <template #yearly="{ label, range, presetDateRange }">
                                    <span @click="presetDateRange(range)">{{ label }}</span>
                                </template>
                            </Datepicker>
                        </div>

                        <div class="col-12">
                            <div class="flex flex-wrap gap-3 mt-4">
                                <button class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-search-line lab-font-size-16"></i>
                                    <span>{{ $t('button.search') }}</span>
                                </button>
                                <button class="db-btn py-2 text-white bg-gray-600" @click="clear">
                                    <i class="lab lab-cross-line-2 lab-font-size-22"></i>
                                    <span>{{ $t('button.clear') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="db-table-responsive">
                <table class="db-table stripe" id="print">
                    <thead class="db-table-head">
                        <tr class="db-table-head-tr">
                            <th class="db-table-head-th">{{ $t('label.name') }}</th>
                            <th class="db-table-head-th">{{ $t('label.category') }}</th>
                            <th class="db-table-head-th">{{ $t('label.type') }}</th>
                            <th class="db-table-head-th">{{ $t('label.quantity') }}</th>
                        </tr>
                    </thead>
                    <tbody class="db-table-body" v-if="itemsReports.length > 0">
                        <tr class="db-table-body-tr" v-for="itemsReport in itemsReports" :key="itemsReport">
                            <td class="db-table-body-td">{{ itemsReport.name }}</td>
                            <td class="db-table-body-td">{{ itemsReport.category_name }}</td>
                            <td class="db-table-body-td">
                                {{ enums.itemTypeEnumArray[itemsReport.item_type] }}
                            </td>
                            <td class="db-table-body-td">{{ itemsReport.order }}</td>
                        </tr>
                    </tbody>

                    <tfoot class="db-table-foot border-t" v-if="itemsReports.length > 0">
                        <td class="db-table-body-td">{{ $t('label.total') }}</td>
                        <td></td>
                        <td></td>
                        <td class="db-table-body-td"> {{ subTotal(itemsReports) }}</td>
                    </tfoot>
                </table>
            </div>

            <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-6">
                <PaginationSMBox :pagination="pagination" :method="list" />
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <PaginationTextComponent :props="{ page: paginationPage }" />
                    <PaginationBox :pagination="pagination" :method="list" />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent";
import PaginationBox from "../components/pagination/PaginationBox";
import PaginationSMBox from "../components/pagination/PaginationSMBox";
import appService from "../../../services/appService";
import itemTypeEnum from "../../../enums/modules/itemTypeEnum";
import paymentTypeEnum from "../../../enums/modules/paymentTypeEnum";
import TableLimitComponent from "../components/TableLimitComponent";
import FilterComponent from "../components/buttons/collapse/FilterComponent";
import ExportComponent from "../components/buttons/export/ExportComponent";
import print from 'vue3-print-nb';
import PrintComponent from "../components/buttons/export/PrintComponent";
import ExcelComponent from "../components/buttons/export/ExcelComponent";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { ref } from 'vue';
import { endOfMonth, endOfYear, startOfMonth, startOfYear, subMonths } from 'date-fns';
import SmIconViewComponent from "../components/buttons/SmIconViewComponent";
import statusEnum from "../../../enums/modules/statusEnum";

export default {
    name: "ItemsReportListComponent",
    components: {
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        LoadingComponent,
        ExportComponent,
        FilterComponent,
        PrintComponent,
        ExcelComponent,
        Datepicker,
        SmIconViewComponent
    },
    setup() {
        const date = ref();

        const presetRanges = ref([
            { label: 'Today', range: [new Date(), new Date()] },
            { label: 'This month', range: [startOfMonth(new Date()), endOfMonth(new Date())] },
            {
                label: 'Last month',
                range: [startOfMonth(subMonths(new Date(), 1)), endOfMonth(subMonths(new Date(), 1))],
            },
            { label: 'This year', range: [startOfYear(new Date()), endOfYear(new Date())] },
            {
                label: 'This year (slot)',
                range: [startOfYear(new Date()), endOfYear(new Date())],
                slot: 'yearly',
            },
        ]);

        return {
            date,
            presetRanges,
        }
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            enums: {
                itemTypeEnum: itemTypeEnum,
                paymentTypeEnum: paymentTypeEnum,
                itemTypeEnumArray: {
                    [itemTypeEnum.VEG]: this.$t("label.veg"),
                    [itemTypeEnum.NON_VEG]: this.$t("label.non_veg")
                },
                paymentTypeEnumArray: {
                    [paymentTypeEnum.CASH_ON_DELIVERY]: this.$t("label.cash_on_delivery"),
                    [paymentTypeEnum.E_WALLET]: this.$t("label.e_wallet"),
                    [paymentTypeEnum.PAYPAL]: this.$t("label.paypal")
                },
            },
            printLoading: true,
            printObj: {
                id: "print",
                popTitle: this.$t('menu.items_report')
            },
            props: {
                form: {
                    date: null,
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    name: null,
                    item_category_id: null,
                    item_type: null,
                    from_date: "",
                    to_date: "",
                }
            }
        }
    },
    mounted() {
        this.list();
        this.loading.isActive = true;
        this.props.search.page = 1;
        this.$store.dispatch('item/lists', this.props.search).then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
        this.$store.dispatch('itemCategory/lists', this.props.search).then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },
    computed: {
        itemsReports: function () {
            return this.$store.getters['itemsReport/lists'];
        },
        items: function () {
            return this.$store.getters['item/lists'];
        },
        itemCategories: function () {
            return this.$store.getters["itemCategory/lists"];
        },
        pagination: function () {
            return this.$store.getters['itemsReport/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['itemsReport/page'];
        }
    },
    methods: {
        floatNumber(e) {
            return appService.floatNumber(e);
        },
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        search: function () {
            this.list();
        },
        handleDate: function (e) {
            if (e) {
                this.props.search.from_date = e[0];
                this.props.search.to_date = e[1];
            } else {
                this.props.form.date = null;
                this.props.search.from_date = null;
                this.props.search.to_date = null;

            }

        },
        subTotal(items) {
            return items.reduce((acc, ele) => {
                return acc + parseInt(ele.order);
            }, 0);
        },
        clear: function () {
            this.props.search.paginate = 1;
            this.props.search.page = 1;
            this.props.search.name = null;
            this.props.search.item_category_id = null;
            this.props.search.item_type = null;
            this.props.search.from_date = "";
            this.props.search.to_date = "";
            this.list();
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch('itemsReport/lists', this.props.search).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },

        xls: function () {
            this.loading.isActive = true;
            this.$store.dispatch('itemsReport/export', this.props.search).then(res => {
                this.loading.isActive = false;
                const blob = new Blob([res.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = this.$t("menu.items_report");
                link.click();
                URL.revokeObjectURL(link.href);
            }).catch((err) => {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            });
        }
    }
}
</script>
<style scoped>
@media print {
    .hidden-print {
        display: none !important;
    }
}
</style>
