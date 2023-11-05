<template>
    <LoadingComponent :props="loading" />
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ $t("menu.employees") }}</h3>
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
                    <EmployeeCreateComponent :props="props" v-if="permissionChecker('employees_create')" />
                </div>
            </div>

            <div class="table-filter-div">
                <form class="p-4 sm:p-5 mb-5" @submit.prevent="search">
                    <div class="row">
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchName" class="db-field-title after:hidden">{{
                                $t("label.name")
                            }}</label>
                            <input id="searchName" v-model="props.search.name" type="text" class="db-field-control" />
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchEmail" class="db-field-title after:hidden">{{
                                $t("label.email")
                            }}</label>
                            <input id="searchEmail" v-model="props.search.email" type="text" class="db-field-control" />
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchPhone" class="db-field-title after:hidden">{{
                                $t("label.phone")
                            }}</label>
                            <input id="searchPhone" v-model="props.search.phone" v-on:keypress="phoneNumber($event)"
                                type="text" class="db-field-control" />
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
                                :clearOnClose="true" placeholder="--" search-placeholder="--" />
                        </div>

                        <div class="col-12 sm:col-6 md:col-4 xl:col-3" v-if="branches.length > 1 && authBranch === 0">
                            <label for="searchBranch" class="db-field-title after:hidden">
                                {{ $t("label.branch") }}
                            </label>
                            <vue-select class="db-field-control f-b-custom-select" id="searchBranch"
                                v-model="props.search.branch_id" :options="[
                                    { id: defaultAccess.branch_id, name: $t('label.current_branch') },
                                    { id: 0, name: $t('label.all_branch') },
                                ]" label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"
                                :clearOnClose="true" placeholder="--" search-placeholder="--" />
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="role_id" class="db-field-title required">{{
                                $t("label.role")
                            }}</label>

                            <vue-select class="db-field-control f-b-custom-select" id="role_id"
                                v-model="props.search.role_id" :options="roles" label-by="name" value-by="id"
                                :closeOnSelect="true" :searchable="true" :clearOnClose="true" placeholder="--"
                                search-placeholder="--" />
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
                            <th class="db-table-head-th">{{ $t("label.name") }}</th>
                            <th class="db-table-head-th">{{ $t("label.email") }}</th>
                            <th class="db-table-head-th">{{ $t("label.phone") }}</th>
                            <th class="db-table-head-th">{{ $t("label.role") }}</th>
                            <th class="db-table-head-th">{{ $t("label.status") }}</th>
                            <th class="db-table-head-th hidden-print"
                                v-if="permissionChecker('employees_show') || permissionChecker('employees_edit') || permissionChecker('employees_delete')">
                                {{ $t("label.action") }}</th>
                        </tr>
                    </thead>
                    <tbody class="db-table-body" v-if="employees.length > 0">
                        <tr class="db-table-body-tr" v-for="employee in employees" :key="employee">
                            <td class="db-table-body-td">{{ textShortener(employee.name, 20) }}</td>
                            <td class="db-table-body-td">{{ employee.email }}</td>
                            <td class="db-table-body-td">{{ employee.country_code + '' + employee.phone }}</td>
                            <td class="db-table-body-td">{{ employee.role }}</td>
                            <td class="db-table-body-td">
                                <span :class="statusClass(employee.status)">
                                    {{ enums.statusEnumArray[employee.status] }}
                                </span>
                            </td>
                            <td class="db-table-body-td hidden-print"
                                v-if="permissionChecker('employees_show') || permissionChecker('employees_edit') || permissionChecker('employees_delete')">
                                <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                                    <SmIconViewComponent :link="'admin.employees.show'" :id="employee.id"
                                        v-if="permissionChecker('employees_show')" />
                                    <SmIconSidebarModalEditComponent @click="edit(employee)"
                                        v-if="permissionChecker('employees_edit')" />
                                    <SmIconDeleteComponent @click="destroy(employee.id)"
                                        v-if="permissionChecker('employees_delete')" />
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
import EmployeeCreateComponent from "./EmployeeCreateComponent";
import alertService from "../../../services/alertService";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent";
import PaginationBox from "../components/pagination/PaginationBox";
import PaginationSMBox from "../components/pagination/PaginationSMBox";
import appService from "../../../services/appService";
import statusEnum from "../../../enums/modules/statusEnum";
import TableLimitComponent from "../components/TableLimitComponent";
import SmIconViewComponent from "../components/buttons/SmIconViewComponent";
import SmIconSidebarModalEditComponent from "../components/buttons/SmIconSidebarModalEditComponent";
import SmIconDeleteComponent from "../components/buttons/SmIconDeleteComponent";
import FilterComponent from "../components/buttons/collapse/FilterComponent";
import ExportComponent from "../components/buttons/export/ExportComponent";
import print from "vue3-print-nb";
import PrintComponent from "../components/buttons/export/PrintComponent";
import ExcelComponent from "../components/buttons/export/ExcelComponent";

export default {
    name: "EmployeeListComponent",
    components: {
        ExportComponent,
        FilterComponent,
        SmIconSidebarModalEditComponent,
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        EmployeeCreateComponent,
        LoadingComponent,
        SmIconDeleteComponent,
        SmIconViewComponent,
        PrintComponent,
        ExcelComponent,
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
            printLoading: true,
            printObj: {
                id: "print",
                popTitle: this.$t("menu.employees"),
            },
            props: {
                form: {
                    name: "",
                    email: "",
                    phone: "",
                    password: "",
                    password_confirmation: "",
                    country_code: "",
                    branch_id: null,
                    role_id: null,
                    status: statusEnum.ACTIVE,
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: "id",
                    order_type: "desc",
                    name: "",
                    email: "",
                    phone: "",
                    branch_id: null,
                    role_id: null,
                    status: null,
                },
            },
            country_code: "",
        };
    },
    computed: {
        defaultAccess: function () {
            return this.$store.getters["defaultAccess/show"];
        },
        branches: function () {
            return this.$store.getters["branch/lists"];
        },
        authBranch: function () {
            return this.$store.getters.authBranchId;
        },
        roles: function () {
            return this.$store.getters["role/lists"];
        },
        employees: function () {
            return this.$store.getters["employee/lists"];
        },
        pagination: function () {
            return this.$store.getters["employee/pagination"];
        },
        paginationPage: function () {
            return this.$store.getters["employee/page"];
        },
        countryCode: function () {
            return this.$store.getters['countryCode/show'];
        }
    },

    mounted() {
        this.list();
        this.$store.dispatch("defaultAccess/show");
        this.$store.dispatch("branch/lists", {
            order_column: "id",
            order_type: "asc",
            status: statusEnum.ACTIVE,
        });
        this.$store.dispatch("role/lists", {
            order_column: "id",
            order_type: "asc",
            excepts: "1|2|3|4|5",
        });
        this.$store.dispatch('company/lists').then(companyRes => {
            this.loading.isActive = true;
            this.$store.dispatch('countryCode/show', companyRes.data.data.company_country_code).then(res => {
                this.country_code = res.data.data.calling_code;
                this.loading.isActive = false;

            }).catch((err) => {
                this.loading.isActive = false;

            });
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },

    methods: {
        permissionChecker(e) {
            return appService.permissionChecker(e);
        },
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        phoneNumber(e) {
            return appService.phoneNumber(e);
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
            this.props.search.email = "";
            this.props.search.phone = "";
            this.props.search.branch_id = null;
            this.props.search.role_id = null;
            this.props.search.status = null;
            this.list();
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store
                .dispatch("employee/lists", this.props.search)
                .then((res) => {
                    this.loading.isActive = false;
                })
                .catch((err) => {
                    this.loading.isActive = false;
                });
        },
        edit: function (employee) {
            appService.sideDrawerShow();
            this.loading.isActive = true;
            this.$store.dispatch("employee/edit", employee.id);
            this.loading.isActive = false;
            this.props.errors = {};
            this.props.form = {
                name: employee.name,
                email: employee.email,
                phone: employee.phone,
                password: employee.password,
                branch_id: employee.branch_id === 0 ? 0 : employee.branch_id,
                role_id: employee.role_id,
                status: employee.status,
                country_code: this.country_code,
            };
        },
        destroy: function (id) {
            appService
                .destroyConfirmation()
                .then((res) => {
                    try {
                        this.loading.isActive = true;
                        this.$store
                            .dispatch("employee/destroy", {
                                id: id,
                                search: this.props.search,
                            })
                            .then((res) => {
                                this.loading.isActive = false;
                                alertService.successFlip(null, this.$t("menu.employees"));
                            })
                            .catch((err) => {
                                this.loading.isActive = false;
                                alertService.error(err.response.data.message);
                            });
                    } catch (err) {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.message);
                    }
                })
                .catch((err) => {
                    this.loading.isActive = false;
                });
        },
        xls: function () {
            this.loading.isActive = true;
            this.$store
                .dispatch("employee/export", this.props.search)
                .then((res) => {
                    this.loading.isActive = false;
                    const blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                    });
                    const link = document.createElement("a");
                    link.href = URL.createObjectURL(blob);
                    link.download = this.$t("menu.employees");
                    link.click();
                    URL.revokeObjectURL(link.href);
                })
                .catch((err) => {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                });
        },
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
