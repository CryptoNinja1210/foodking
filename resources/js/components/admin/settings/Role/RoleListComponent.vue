<template>
    <LoadingComponent :props="loading" />
    <div id="role" class="db-card db-tab-div active">
        <div class="db-card-header">
            <h3 class="db-card-title"> {{ $t('menu.role') }} &amp; {{ $t('label.permissions') }}</h3>
            <div class="db-card-filter">
                <TableLimitComponent :method="list" :search="props.search" :page="paginationPage" />
                <RoleCreateComponent :props="props" />
            </div>
        </div>
        <ul v-if="roles.length > 0">
            <li v-for="role in roles" :key="role.role"
                class="flex flex-col items-center justify-between gap-4 sm:flex-row sm:justify-between py-3 px-4 border-b last:border-none border-solid border-slate-200">
                <span class="font-medium capitalize text-center sm:text-left text-sm text-slate-500">
                    {{ role.name }}
                    <span class="block font-normal whitespace-nowrap">({{ role.users_count }}) {{ $t('label.members')
                    }}</span>
                </span>
                <div class="flex flex-wrap justify-center items-center sm:items-start sm:justify-end gap-1.5">
                    <router-link class="db-btn-outline sm primary modal-btn m-0.5"
                        :to="{ name: 'admin.settings.role.show', params: { id: role.id } }">
                        <i class="lab lab-key"></i>
                        <span>{{ $t("button.permissions") }}</span>
                    </router-link>
                    <SmModalEditComponent @click="edit(role)" />
                    <SmDeleteComponent @click="destroy(role.id)" v-if="!enums.roleEnumArray.includes(role.id)" />
                </div>
            </li>
        </ul>
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
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";
import RoleCreateComponent from "./RoleCreateComponent";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent";
import PaginationBox from "../../components/pagination/PaginationBox";
import PaginationSMBox from "../../components/pagination/PaginationSMBox";
import appService from "../../../../services/appService";
import TableLimitComponent from "../../components/TableLimitComponent";
import SmDeleteComponent from "../../components/buttons/SmDeleteComponent";
import SmModalEditComponent from "../../components/buttons/SmModalEditComponent";
import roleEnum from "../../../../enums/modules/roleEnum";

export default {
    name: "RoleListComponent",
    components: {
        TableLimitComponent,
        PaginationSMBox,
        RoleCreateComponent,
        PaginationBox,
        PaginationTextComponent,
        LoadingComponent,
        SmDeleteComponent,
        SmModalEditComponent,
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            props: {
                form: {
                    name: "",
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    order_type: 'asc',
                }
            },
            enums: {
                roleEnumArray: [
                    roleEnum.ADMIN,
                    roleEnum.CUSTOMER,
                    roleEnum.DELIVERY_BOY,
                    roleEnum.WAITER,
                    roleEnum.CHEF
                ],
            },
        }
    },
    computed: {
        roles: function () {
            return this.$store.getters['role/lists'];
        },
        pagination: function () {
            return this.$store.getters['role/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['role/page'];
        }
    },
    mounted() {
        this.list();
    },
    methods: {
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch('role/lists', this.props.search).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        edit: function (role) {
            appService.modalShow();
            this.loading.isActive = true;
            this.props.form = {
                name: role.name,
            };
            this.$store.dispatch('role/edit', role.id);
            this.loading.isActive = false;
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('role/destroy', {
                        id: id,
                        search: this.props.search
                    }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, this.$t('menu.role'));
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
        }
    }
}
</script>
