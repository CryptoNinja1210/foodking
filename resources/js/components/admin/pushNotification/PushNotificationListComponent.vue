<template>
    <LoadingComponent :props="loading" />

    <div class="db-card db-tab-div active">
        <div class="db-card-header border-none">
            <h3 class="db-card-title">{{ $t('menu.push_notifications') }}</h3>
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
                <PushNotificationCreateComponent :props="props" v-if="permissionChecker('push-notifications_create')" />
            </div>
        </div>

        <div class="table-filter-div">
            <form class="p-4 sm:p-5 mb-5" @submit.prevent="search">
                <div class="row">
                    <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                        <label for="searchTitle" class="db-field-title after:hidden">{{ $t('label.title') }}</label>
                        <input id="searchTitle" v-model="props.search.title" type="text" class="db-field-control">
                    </div>
                    <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                        <label for="searchRole" class="db-field-title after:hidden">{{ $t('label.role') }}</label>
                        <vue-select @search:change="selectUser($event)" class="db-field-control f-b-custom-select"
                            id="role_id" v-model="props.search.role_id" :options="roles" label-by="name" value-by="id"
                            :closeOnSelect="true" :searchable="true" :clearOnClose="true" placeholder="--"
                            search-placeholder="--" />
                    </div>
                    <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                        <label for="searchUser" class="db-field-title after:hidden">{{ $t('label.user') }}</label>
                        <vue-select class="db-field-control f-b-custom-select" id="user_id" v-model="props.search.user_id"
                            :options="users" label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"
                            :clearOnClose="true" placeholder="--" search-placeholder="--" />
                    </div>

                    <div class="col-12">
                        <div class="flex flex-wrap gap-3 mt-4">
                            <button class="db-btn py-2 text-white bg-primary">
                                <i class="lab lab-search-line lab-font-size-16"></i>
                                <span>{{ $t('button.search') }}</span>
                            </button>
                            <button class="db-btn py-2 text-white bg-gray-600" @click.prevent="clear">
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
                        <th class="db-table-head-th">{{ $t('label.title') }}</th>
                        <th class="db-table-head-th">{{ $t('label.role') }}</th>
                        <th class="db-table-head-th">{{ $t('label.user') }}</th>
                        <th class="db-table-head-th hidden-print"
                            v-if="permissionChecker('push-notifications_show') || permissionChecker('push-notifications_delete')">
                            {{ $t('label.action') }}</th>
                    </tr>
                </thead>
                <tbody class="db-table-body" v-if="pushNotifications.length > 0">
                    <tr class="db-table-body-tr" v-for="pushNotification in pushNotifications" :key="pushNotification">
                        <td class="db-table-body-td">
                            <div>{{ textShortener(pushNotification.title) }}</div>
                        </td>
                        <td class="db-table-body-td">
                            <div>{{ pushNotification.role }}</div>
                        </td>
                        <td class="db-table-body-td">
                            <div>{{ pushNotification.customer }}</div>
                        </td>
                        <td class="db-table-body-td hidden-print"
                            v-if="permissionChecker('push-notifications_show') || permissionChecker('push-notifications_delete')">
                            <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                                <SmViewComponent :link="'admin.push-notification.show'" :id="pushNotification.id"
                                    v-if="permissionChecker('push-notifications_show')" />
                                <SmDeleteComponent @click="destroy(pushNotification.id)"
                                    v-if="permissionChecker('push-notifications_delete')" />
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
</template>
<script>
import LoadingComponent from "../components/LoadingComponent";
import PushNotificationCreateComponent from "./PushNotificationCreateComponent";
import alertService from "../../../services/alertService";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent";
import PaginationBox from "../components/pagination/PaginationBox";
import PaginationSMBox from "../components/pagination/PaginationSMBox";
import appService from "../../../services/appService";
import statusEnum from "../../../enums/modules/statusEnum";
import TableLimitComponent from "../components/TableLimitComponent";
import SmDeleteComponent from "../components/buttons/SmDeleteComponent";
import SmModalEditComponent from "../components/buttons/SmModalEditComponent";
import SmViewComponent from "../components/buttons/SmViewComponent";
import FilterComponent from "../components/buttons/collapse/FilterComponent";
import ExportComponent from "../components/buttons/export/ExportComponent";
import PrintComponent from "../components/buttons/export/PrintComponent";
import ExcelComponent from "../components/buttons/export/ExcelComponent";

export default {
    name: "PushNotificationListComponent",
    components: {
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        PushNotificationCreateComponent,
        LoadingComponent,
        SmDeleteComponent,
        SmModalEditComponent,
        SmViewComponent,
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
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive")
                },
            },
            printLoading: true,
            printObj: {
                id: "print",
                popTitle: this.$t('menu.push_notifications')
            },
            props: {
                form: {
                    title: "",
                    description: "",
                    role_id: null,
                    user_id: null,
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    order_type: 'desc',
                    branch_id: null,
                    title: "",
                    role_id: null,
                    user_id: null
                }
            }
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("defaultAccess/show").then(res => {
            this.props.search.branch_id = res.data.data.branch_id;
            this.loading.isActive = false;
            this.list();
        }).catch((error) => {
            this.loading.isActive = false;
        });
        this.$store.dispatch('role/lists', {
            order_column: 'id',
            order_type: 'asc',
        });
        this.$store.dispatch('user/lists', {
            order_column: 'id',
            order_type: 'asc',
            status: statusEnum.ACTIVE
        });
    },
    computed: {
        pushNotifications: function () {
            return this.$store.getters['pushNotification/lists'];
        },
        pagination: function () {
            return this.$store.getters['pushNotification/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['pushNotification/page'];
        },
        roles: function () {
            return this.$store.getters['role/lists'];
        },
        users: function () {
            return this.$store.getters['user/lists'];
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
        search: function () {
            this.list();
        },
        selectUser: function (e) {
            this.props.search.user_id = null;
            this.$store.dispatch('user/lists', {
                order_column: 'id',
                order_type: 'asc',
                status: statusEnum.ACTIVE,
                role_id: this.props.search.role_id,
                branch_id: null,
            });
        },
        clear: function () {
            this.props.search.paginate = 1;
            this.props.search.page = 1;
            this.props.search.per_page = 10;
            this.props.search.order_column = 'id';
            this.props.search.order_type = 'desc';
            this.props.search.title = "";
            this.props.search.role_id = null;
            this.props.search.user_id = null;
            this.list();
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch('pushNotification/lists', this.props.search).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('pushNotification/destroy', {
                        id: id,
                        search: this.props.search
                    }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, this.$t('label.push_notification'));
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
            this.$store.dispatch('pushNotification/export', this.props.search).then(res => {
                this.loading.isActive = false;
                const blob = new Blob([res.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = this.$t("menu.push_notifications");
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
