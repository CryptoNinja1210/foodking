<template>
    <LoadingComponent :props="loading"/>

    <div class="db-card db-tab-div active">
        <div class="db-card-header border-none">
            <h3 class="db-card-title">{{ $t("menu.branches") }}</h3>
            <div class="db-card-filter">
                <TableLimitComponent :method="list" :search="props.search" :page="paginationPage"/>
                <BranchCreateComponent :props="props"/>
            </div>
        </div>

        <div class="db-table-responsive">
            <table class="db-table stripe">
                <thead class="db-table-head">
                <tr class="db-table-head-tr">
                    <th class="db-table-head-th">{{ $t("label.name") }}</th>
                    <th class="db-table-head-th">
                        {{ $t("label.status") }}
                    </th>
                    <th class="db-table-head-th">
                        {{ $t("label.action") }}
                    </th>
                </tr>
                </thead>
                <tbody class="db-table-body" v-if="branches.length > 0">
                <tr class="db-table-body-tr" v-for="branch in branches" :key="branch">
                    <td class="db-table-body-td" v-if="site_default_branch === branch.id">
                        {{ branch.name }}({{ $t('label.default') }})
                    </td>
                    <td class="db-table-body-td" v-else>
                        {{ branch.name }}
                    </td>
                    <td class="db-table-body-td">
                            <span :class="statusClass(branch.status)">
                                {{ enums.statusEnumArray[branch.status] }}
                            </span>
                    </td>
                    <td class="db-table-body-td">
                        <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                            <SmViewComponent :link="'admin.settings.branch.show'" :id="branch.id"/>
                            <SmModalEditComponent @click="edit(branch)"/>
                            <SmDeleteComponent @click="destroy(branch.id)" v-if="site_default_branch !== branch.id"/>
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
import BranchCreateComponent from "./BranchCreateComponent";
import alertService from "../../../../services/alertService";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent";
import PaginationBox from "../../components/pagination/PaginationBox";
import PaginationSMBox from "../../components/pagination/PaginationSMBox";
import appService from "../../../../services/appService";
import statusEnum from "../../../../enums/modules/statusEnum";
import TableLimitComponent from "../../components/TableLimitComponent";
import SmDeleteComponent from "../../components/buttons/SmDeleteComponent";
import SmModalEditComponent from "../../components/buttons/SmModalEditComponent";
import SmViewComponent from "../../components/buttons/SmViewComponent";

export default {
    name: "BranchListComponent",
    components: {
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        BranchCreateComponent,
        LoadingComponent,
        SmDeleteComponent,
        SmModalEditComponent,
        SmViewComponent,
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
            props: {
                form: {
                    name: "",
                    email: "",
                    phone: "",
                    latitude: "",
                    longitude: "",
                    city: "",
                    state: "",
                    zip_code: "",
                    address: "",
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
            site_default_branch: null,
        };
    },
    mounted() {
        this.list();
        this.siteList();
    },
    computed: {
        branches: function () {
            return this.$store.getters["branch/lists"];
        },
        pagination: function () {
            return this.$store.getters["branch/pagination"];
        },
        paginationPage: function () {
            return this.$store.getters["branch/page"];
        },
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        siteList: function () {
            this.loading.isActive = true;
            this.$store.dispatch('site/lists').then(res => {
                this.site_default_branch = res.data.data.site_default_branch;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch("branch/lists", this.props.search).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        edit: function (branch) {
            appService.modalShow();
            this.loading.isActive = true;
            this.$store.dispatch("branch/edit", branch.id);
            this.props.form = {
                name: branch.name,
                email: branch.email,
                phone: branch.phone,
                latitude: branch.latitude,
                longitude: branch.longitude,
                city: branch.city,
                state: branch.state,
                zip_code: branch.zip_code,
                address: branch.address,
                status: branch.status,
            };
            this.loading.isActive = false;
        },
        destroy: function (id) {
            appService
                .destroyConfirmation()
                .then((res) => {
                    try {
                        this.loading.isActive = true;
                        this.$store
                            .dispatch("branch/destroy", {
                                id: id,
                                search: this.props.search,
                            })
                            .then((res) => {
                                this.loading.isActive = false;
                                alertService.successFlip(null, this.$t("menu.branches"));
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
    },
};
</script>
