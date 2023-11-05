<template>
    <LoadingComponent :props="loading" />
    <div class="col-12">
        <div class="db-card db-tab-div active">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ $t("menu.coupons") }}</h3>
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
                    <CouponCreateComponent :props="props" v-if="permissionChecker('coupons_create')" />
                </div>
            </div>
            <div class="table-filter-div">
                <form class="p-4 sm:p-5 mb-5" @submit.prevent="search">
                    <div class="row">
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchName" class="db-field-title after:hidden">{{ $t('label.name') }}</label>
                            <input id="searchName" v-model="props.search.name" type="text" class="db-field-control">
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchCode" class="db-field-title after:hidden">{{ $t('label.code') }}</label>
                            <input id="searchCode" v-model="props.search.code" type="text" class="db-field-control">
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchDiscount" class="db-field-title after:hidden">{{
                                $t('label.discount')
                            }}</label>
                            <input id="searchDiscount" v-model="props.search.discount" v-on:keypress="floatNumber($event)"
                                type="text" class="db-field-control">
                        </div>

                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="discount_type" class="db-field-title after:hidden">{{
                                $t('label.discount_type')
                            }}</label>
                            <vue-select class="db-field-control f-b-custom-select" id="discount_type"
                                v-model="props.search.discount_type" :options="[
                                    { id: enums.taxTypeEnum.FIXED, name: $t('label.fixed') },
                                    { id: enums.taxTypeEnum.PERCENTAGE, name: $t('label.percentage') }
                                ]" label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"
                                :clearOnClose="true" placeholder="--" search-placeholder="--" />
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchStartDate" class="db-field-title after:hidden">{{
                                $t('label.start_date')
                            }}</label>
                            <Datepicker hideInputIcon autoApply v-model="props.search.start_date" :enableTimePicker="true"
                                :is24="false" :monthChangeOnScroll="false" utc="false">
                                <template #am-pm-button="{ toggle, value }">
                                    <button @click="toggle">{{ value }}</button>
                                </template>
                            </Datepicker>
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchEndDate" class="db-field-title after:hidden">{{
                                $t('label.end_date')
                            }}</label>
                            <Datepicker hideInputIcon autoApply v-model="props.search.end_date" :enableTimePicker="true"
                                :is24="false" :monthChangeOnScroll="false" utc="false">
                                <template #am-pm-button="{ toggle, value }">
                                    <button @click="toggle">{{ value }}</button>
                                </template>
                            </Datepicker>
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchminimumorder" class="db-field-title after:hidden">{{
                                $t('label.minimum_order')
                            }}</label>
                            <input id="searchminimumorder" v-model="props.search.minimum_order"
                                v-on:keypress="floatNumber($event)" type="text" class="db-field-control">
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchMaximumDiscount" class="db-field-title after:hidden">{{
                                $t('label.maximum_discount')
                            }}</label>
                            <input id="searchMaximumDiscount" v-model="props.search.maximum_discount"
                                v-on:keypress="floatNumber($event)" type="text" class="db-field-control">
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchLimitPerUser" class="db-field-title after:hidden">{{
                                $t('label.limit_per_user')
                            }}</label>
                            <input id="searchLimitPerUser" v-model="props.search.limit_per_user"
                                v-on:keypress="floatNumber($event)" type="text" class="db-field-control">
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
                            <th class="db-table-head-th">{{ $t("label.name") }}</th>
                            <th class="db-table-head-th">{{ $t("label.code") }}</th>
                            <th class="db-table-head-th">{{ $t("label.discount") }}</th>
                            <th class="db-table-head-th">{{ $t("label.discount_type") }}</th>
                            <th class="db-table-head-th">{{ $t("label.start_date") }}</th>
                            <th class="db-table-head-th">{{ $t("label.end_date") }}</th>
                            <th class="db-table-head-th hidden-print"
                                v-if="permissionChecker('coupons_show') || permissionChecker('coupons_edit') || permissionChecker('coupons_delete')">
                                {{ $t("label.action") }}</th>
                        </tr>
                    </thead>
                    <tbody class="db-table-body" v-if="coupons.length > 0">
                        <tr class="db-table-body-tr" v-for="coupon in coupons" :key="coupon">
                            <td class="db-table-body-td">
                                <div v-if="coupon.name.length < 40"> {{ coupon.name }}</div>
                                <div v-else>{{ coupon.name.substring(0, 40) + ".." }}</div>
                            </td>
                            <td class="db-table-body-td">{{ coupon.code }}</td>
                            <td class="db-table-body-td">{{ coupon.flat_discount }}</td>
                            <td class="db-table-body-td">
                                <span :class="taxTypeClass(coupon.discount_type)">{{
                                    enums.taxTypeEnumArray[coupon.discount_type]
                                }}</span>
                            </td>
                            <td class="db-table-body-td">{{ coupon.convert_start_date }}</td>
                            <td class="db-table-body-td">{{ coupon.convert_end_date }}</td>
                            <td class="db-table-body-td hidden-print"
                                v-if="permissionChecker('coupons_show') || permissionChecker('coupons_edit') || permissionChecker('coupons_delete')">
                                <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                                    <SmIconViewComponent :link="'admin.coupon.show'" :id="coupon.id"
                                        v-if="permissionChecker('coupons_show')" />
                                    <SmIconSidebarModalEditComponent @click="edit(coupon)"
                                        v-if="permissionChecker('coupons_edit')" />
                                    <SmIconDeleteComponent @click="destroy(coupon.id)"
                                        v-if="permissionChecker('coupons_delete')" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
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
import CouponCreateComponent from "./CouponCreateComponent";
import alertService from "../../../services/alertService";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent";
import PaginationBox from "../components/pagination/PaginationBox";
import PaginationSMBox from "../components/pagination/PaginationSMBox";
import appService from "../../../services/appService";
import taxTypeEnum from "../../../enums/modules/taxTypeEnum";
import TableLimitComponent from "../components/TableLimitComponent";
import SmIconDeleteComponent from "../components/buttons/SmIconDeleteComponent";
import SmViewComponent from "../components/buttons/SmViewComponent";
import SmIconSidebarModalEditComponent from "../components/buttons/SmIconSidebarModalEditComponent";
import FilterComponent from "../components/buttons/collapse/FilterComponent";
import ExportComponent from "../components/buttons/export/ExportComponent";
import print from 'vue3-print-nb';
import PrintComponent from "../components/buttons/export/PrintComponent";
import ExcelComponent from "../components/buttons/export/ExcelComponent";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import SmIconViewComponent from "../components/buttons/SmIconViewComponent";


export default {
    name: "CouponListComponent",
    components: {
        SmIconSidebarModalEditComponent,
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        CouponCreateComponent,
        LoadingComponent,
        SmIconDeleteComponent,
        SmViewComponent,
        ExportComponent,
        FilterComponent,
        PrintComponent,
        ExcelComponent,
        Datepicker,
        SmIconViewComponent
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
            },

            printLoading: true,
            printObj: {
                id: "print",
                popTitle: this.$t('menu.coupons')
            },
            props: {
                form: {
                    name: "",
                    description: "",
                    code: "",
                    discount: "",
                    discount_type: taxTypeEnum.FIXED,
                    start_date: "",
                    end_date: "",
                    minimum_order: "",
                    maximum_discount: "",
                    limit_per_user: "",
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: "id",
                    order_type: "desc",
                    name: "",
                    code: "",
                    discount: "",
                    discount_type: null,
                    start_date: "",
                    end_date: "",
                    minimum_order: "",
                    maximum_discount: "",
                    limit_per_user: "",
                },
            },
        };
    },
    mounted() {
        this.list();
    },
    computed: {
        coupons: function () {
            return this.$store.getters["coupon/lists"];
        },
        pagination: function () {
            return this.$store.getters["coupon/pagination"];
        },
        paginationPage: function () {
            return this.$store.getters["coupon/page"];
        },
    },
    methods: {
        permissionChecker(e) {
            return appService.permissionChecker(e);
        },
        floatNumber(e) {
            return appService.floatNumber(e);
        },
        taxTypeClass: function (status) {
            return appService.taxTypeClass(status);
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        search: function () {
            this.list();
        },
        clear: function () {
            this.props.search.paginate = 1;
            this.props.search.page = 1;
            this.props.search.name = "";
            this.props.search.code = "";
            this.props.search.discount = "";
            this.props.search.discount_type = null;
            this.props.search.start_date = "";
            this.props.search.end_date = "";
            this.props.search.minimum_order = "";
            this.props.search.maximum_discount = "";
            this.props.search.limit_per_user = "";
            this.list();
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch("coupon/lists", this.props.search).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        edit: function (coupon) {
            appService.sideDrawerShow();
            this.loading.isActive = true;
            this.$store.dispatch("coupon/edit", coupon.id).then((res) => {
                this.loading.isActive = false;
                this.props.errors = {};
                this.props.form = {
                    name: coupon.name,
                    description: coupon.description,
                    code: coupon.code,
                    discount: coupon.flat_discount,
                    discount_type: coupon.discount_type,
                    start_date: coupon.start_date,
                    end_date: coupon.end_date,
                    minimum_order: coupon.minimum_order_flat_amount,
                    maximum_discount: coupon.maximum_flat_discount,
                    limit_per_user: coupon.limit_per_user,
                };
            }).catch((err) => {
                alertService.error(err.response.data.message);
            });
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch("coupon/destroy", {
                        id: id,
                        search: this.props.search,
                    }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(
                            null,
                            this.$t("menu.coupons")
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

        xls: function () {
            this.loading.isActive = true;
            this.$store.dispatch('coupon/export', this.props.search).then(res => {
                this.loading.isActive = false;
                const blob = new Blob([res.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = this.$t("menu.coupons");
                link.click();
                URL.revokeObjectURL(link.href);
            }).catch((err) => {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            });
        }
    },
};

</script>
<style scoped>
@media print {
    .hidden-print {
        display: none !important;
    }
}
</style>
