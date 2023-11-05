<template>
    <LoadingComponent :props="loading"/>
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ $t('menu.items') }}</h3>
                <div class="db-card-filter">
                    <TableLimitComponent :method="list" :search="props.search" :page="paginationPage"/>
                    <FilterComponent/>
                    <div class="dropdown-group">
                        <ExportComponent/>
                        <div class="dropdown-list db-card-filter-dropdown-list">
                            <PrintComponent :props="printObj"/>
                            <ExcelComponent :method="xls"/>
                        </div>
                    </div>
                    <ItemCreateComponent :props="props" v-if="permissionChecker('items_create')"/>
                </div>
            </div>

            <div class="table-filter-div">
                <form class="p-4 sm:p-5 mb-5" @submit.prevent="search">
                    <div class="row">
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="name" class="db-field-title after:hidden">{{
                                    $t("label.name")
                                }}</label>
                            <input id="name" v-model="props.search.name" type="text" class="db-field-control"/>
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="price" class="db-field-title after:hidden">{{
                                    $t("label.price")
                                }}</label>
                            <input id="price" v-on:keypress="numberOnly($event)" v-model="props.search.price"
                                   type="text"
                                   class="db-field-control"/>
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="item_category_id" class="db-field-title">{{
                                    $t("label.category")
                                }}</label>

                            <vue-select class="db-field-control f-b-custom-select" id="item_category_id"
                                        v-model="props.search.item_category_id" :options="itemCategories"
                                        label-by="name"
                                        value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                                        placeholder="--"
                                        search-placeholder="--"/>
                        </div>

                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="tax_id" class="db-field-title">{{
                                    $t("label.tax")
                                }}</label>

                            <vue-select class="db-field-control f-b-custom-select" id="tax_id"
                                        v-model="props.search.tax_id"
                                        :options="taxes" label-by="name" value-by="id" :closeOnSelect="true"
                                        :searchable="true"
                                        :clearOnClose="true" placeholder="--" search-placeholder="--"/>
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchItemType" class="db-field-title after:hidden">{{
                                    $t("label.item_type")
                                }}</label>
                            <vue-select class="db-field-control f-b-custom-select" id="searchItemType"
                                        v-model="props.search.item_type" :options="[
                                    { id: enums.itemTypeEnum.VEG, name: $t('label.veg') },
                                    { id: enums.itemTypeEnum.NON_VEG, name: $t('label.non_veg') },
                                ]" label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"
                                        :clearOnClose="true" placeholder="--" search-placeholder="--"/>
                        </div>

                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchIsFeatured" class="db-field-title after:hidden">{{
                                    $t("label.is_featured")
                                }}</label>
                            <vue-select class="db-field-control f-b-custom-select" id="searchIsFeatured"
                                        v-model="props.search.is_featured" :options="[
                                    { id: enums.askEnum.YES, name: $t('label.yes') },
                                    { id: enums.askEnum.NO, name: $t('label.no') },
                                ]" label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"
                                        :clearOnClose="true" placeholder="--" search-placeholder="--"/>
                        </div>


                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchStatus" class="db-field-title after:hidden">{{
                                    $t("label.status")
                                }}</label>
                            <vue-select class="db-field-control f-b-custom-select" id="searchStatus"
                                        v-model="props.search.status" :options="[
                                    { id: enums.statusEnum.ACTIVE, name: $t('label.active') },
                                    { id: enums.statusEnum.INACTIVE, name: $t('label.inactive') },
                                ]" label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"
                                        :clearOnClose="true" placeholder="--" search-placeholder="--"/>
                        </div>

                        <div class="col-12">
                            <div class="flex flex-wrap gap-3 mt-4">
                                <button class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-search-line lab-font-size-16"></i>
                                    <span>{{ $t("button.search") }}</span>
                                </button>
                                <button class="db-btn py-2 text-white bg-gray-600" @click="clear">
                                    <i class="lab lab-cross-line-2 lab-font-size-22"></i>
                                    <span>{{ $t("button.clear") }}</span>
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
                        <th class="db-table-head-th">
                            {{ $t('label.name') }}
                        </th>
                        <th class="db-table-head-th">
                            {{ $t('label.category') }}
                        </th>
                        <th class="db-table-head-th">
                            {{ $t('label.price') }}
                        </th>
                        <th class="db-table-head-th">
                            {{ $t('label.status') }}
                        </th>
                        <th class="db-table-head-th hidden-print"
                            v-if="permissionChecker('items_show') || permissionChecker('items_edit') || permissionChecker('items_delete')">
                            {{ $t('label.action') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody class="db-table-body" v-if="items.length > 0">
                    <tr class="db-table-body-tr" v-for="item in items" :key="item">
                        <td class="db-table-body-td">
                            {{ textShortener(item.name, 40) }}
                        </td>
                        <td class="db-table-body-td">{{ item.category_name }}</td>
                        <td class="db-table-body-td">{{ item.flat_price }}</td>
                        <td class="db-table-body-td">
                                <span :class="statusClass(item.status)">
                                    {{ enums.statusEnumArray[item.status] }}
                                </span>
                        </td>
                        <td class="db-table-body-td hidden-print"
                            v-if="permissionChecker('items_show') || permissionChecker('items_edit') || permissionChecker('items_delete')">
                            <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                                <SmIconViewComponent :link="'admin.item.show'" :id="item.id"
                                                     v-if="permissionChecker('items_show')"/>
                                <SmIconSidebarModalEditComponent @click="edit(item)"
                                                                 v-if="permissionChecker('items_edit')"/>
                                <SmIconDeleteComponent @click="destroy(item.id)"
                                                       v-if="permissionChecker('items_delete')"/>
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
    </div>
</template>
<script>
import LoadingComponent from "../components/LoadingComponent";
import ItemCreateComponent from "./ItemCreateComponent";
import alertService from "../../../services/alertService";
import statusEnum from "../../../enums/modules/statusEnum";
import askEnum from "../../../enums/modules/askEnum";
import itemTypeEnum from "../../../enums/modules/itemTypeEnum";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent";
import PaginationBox from "../components/pagination/PaginationBox";
import PaginationSMBox from "../components/pagination/PaginationSMBox";
import appService from "../../../services/appService";
import TableLimitComponent from "../components/TableLimitComponent";
import SmIconSidebarModalEditComponent from "../components/buttons/SmIconSidebarModalEditComponent";
import SmIconDeleteComponent from "../components/buttons/SmIconDeleteComponent";
import SmIconViewComponent from "../components/buttons/SmIconViewComponent";
import FilterComponent from "../components/buttons/collapse/FilterComponent";
import ExportComponent from "../components/buttons/export/ExportComponent";
import PrintComponent from "../components/buttons/export/PrintComponent";
import ExcelComponent from "../components/buttons/export/ExcelComponent";

export default {
    name: "ItemListComponent",
    components: {
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        ItemCreateComponent,
        LoadingComponent,
        SmIconSidebarModalEditComponent,
        SmIconDeleteComponent,
        SmIconViewComponent,
        FilterComponent,
        ExportComponent,
        PrintComponent,
        ExcelComponent
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            enums: {
                statusEnum: statusEnum,
                itemTypeEnum: itemTypeEnum,
                askEnum: askEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive")
                }
            },
            printLoading: true,
            printObj: {
                id: "print",
                popTitle: this.$t("menu.items"),
            },
            taxProps: {
                search: {
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'asc'
                }
            },
            categoryProps: {
                search: {
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'asc'
                }
            },
            props: {
                form: {
                    name: "",
                    price: "",
                    description: "",
                    caution: "",
                    is_featured: askEnum.YES,
                    item_type: itemTypeEnum.VEG,
                    item_category_id: null,
                    tax_id: null,
                    status: statusEnum.ACTIVE,
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    order_type: 'desc',
                    name: "",
                    price: "",
                    item_category_id: null,
                    status: null,
                    tax_id: null,
                    item_type: null,
                    is_featured: null
                }
            }
        }
    },
    mounted() {
        this.list();
        this.loading.isActive = true;
        this.props.search.page = 1;
        this.$store.dispatch('itemCategory/lists', this.categoryProps.search).then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
        this.$store.dispatch('tax/lists', this.taxProps.search).then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },
    computed: {
        items: function () {
            return this.$store.getters['item/lists'];
        },
        pagination: function () {
            return this.$store.getters['item/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['item/page'];
        },
        itemCategories: function () {
            return this.$store.getters["itemCategory/lists"];
        },
        taxes: function () {
            return this.$store.getters['tax/lists'];
        }
    },
    methods: {
        permissionChecker(e) {
            return appService.permissionChecker(e);
        },
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        numberOnly: function (e) {
            return appService.floatNumber(e);
        },
        search: function () {
            this.list();
        },
        clear: function () {
            this.props.search.paginate = 1;
            this.props.search.page = 1;
            this.props.search.name = "";
            this.props.search.price = "";
            this.props.search.item_category_id = null;
            this.props.search.status = null;
            this.props.search.tax_id = null;
            this.props.search.item_type = null;
            this.props.search.is_featured = null;
            this.list();
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch('item/lists', this.props.search).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        edit: function (item) {
            appService.sideDrawerShow();
            this.loading.isActive = true;
            this.$store.dispatch('item/edit', item.id);
            this.loading.isActive = false;
            this.props.errors = {};
            this.props.form = {
                name: item.name,
                price: item.flat_price,
                description: item.description,
                caution: item.caution,
                is_featured: item.is_featured,
                item_type: item.item_type,
                tax_id: item.tax_id,
                item_category_id: item.item_category_id,
                status: item.status,
            };
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('item/destroy', {id: id, search: this.props.search}).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, this.$t('menu.items'));
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
            })
        },
        xls: function () {
            this.loading.isActive = true;
            this.$store.dispatch("item/export", this.props.search).then((res) => {
                this.loading.isActive = false;
                const blob = new Blob([res.data], {
                    type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                });
                const link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.download = this.$t("menu.items");
                link.click();
                URL.revokeObjectURL(link.href);
            }).catch((err) => {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            });
        },
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
