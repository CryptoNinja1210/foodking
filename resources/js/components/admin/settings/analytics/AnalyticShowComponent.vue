<template>
    <LoadingComponent :props="loading" />

    <div class="db-card db-tab-div active">
        <div class="db-card-header border-none">
            <h3 class="db-card-title">{{ $t('menu.analytic_section') }} <span class="text-primary normal-case">({{
                analytic.name }})</span> </h3>
            <div class="db-card-filter">
                <TableLimitComponent :method="list" :search="props.search" :page="paginationPage" />
                <AnalyticSectionCreateComponent :props="props" />
            </div>
        </div>
        <div class="db-table-responsive">
            <table class="db-table stripe">
                <thead class="db-table-head">
                    <tr class="db-table-head-tr">
                        <th class="db-table-head-th">{{ $t('label.name') }}</th>
                        <th class="db-table-head-th">{{ $t('label.section') }}</th>
                        <th class="db-table-head-th">{{ $t('label.action') }}</th>
                    </tr>
                </thead>
                <tbody class="db-table-body" v-if="analyticSections.length > 0">
                    <tr class="db-table-body-tr" v-for="analyticSection in analyticSections" :key="analyticSection">
                        <td class="db-table-body-td">{{ analyticSection.name }}</td>
                        <td class="db-table-body-td">
                            {{ enums.analyticSectionEnumArray[analyticSection.section] }}
                        </td>
                        <td class="db-table-body-td">
                            <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                                <SmModalEditComponent @click="edit(analyticSection)" />
                                <SmDeleteComponent @click="destroy(analyticSection.id)" />
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
import LoadingComponent from "../../components/LoadingComponent";
import AnalyticSectionCreateComponent from "./analyticSection/AnalyticSectionCreateComponent";
import alertService from "../../../../services/alertService";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent";
import PaginationBox from "../../components/pagination/PaginationBox";
import PaginationSMBox from "../../components/pagination/PaginationSMBox";
import appService from "../../../../services/appService";
import analyticSectionEnum from "../../../../enums/modules/analyticSectionEnum";
import TableLimitComponent from "../../components/TableLimitComponent";
import SmDeleteComponent from "../../components/buttons/SmDeleteComponent";
import SmModalEditComponent from "../../components/buttons/SmModalEditComponent";
import SmViewComponent from "../../components/buttons/SmViewComponent";

export default {
    name: "AnalyticListComponent",
    components: {
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        AnalyticSectionCreateComponent,
        LoadingComponent,
        SmDeleteComponent,
        SmModalEditComponent,
        SmViewComponent
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            enums: {
                analyticSectionEnum: analyticSectionEnum,
                analyticSectionEnumArray: {
                    [analyticSectionEnum.HEADER]: this.$t("label.header"),
                    [analyticSectionEnum.BODY]: this.$t("label.body"),
                    [analyticSectionEnum.FOOTER]: this.$t("label.footer")
                }
            },
            props: {
                form: {
                    name: "",
                    data: '',
                    section: analyticSectionEnum.HEADER,
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    order_type: 'desc',
                },
                analyticId: 0
            }
        }
    },
    computed: {
        analytic: function () {
            return this.$store.getters['analytic/show'];
        },
        analyticSections: function () {
            return this.$store.getters['analyticSection/lists'];
        },
        pagination: function () {
            return this.$store.getters['analyticSection/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['analyticSection/page'];
        }
    },
    mounted() {
        this.props.analyticId = this.$route.params.id;
        this.list();
        this.show();
    },
    methods: {
        show: function () {
            this.loading.isActive = true;
            this.$store.dispatch('analytic/show', this.props.analyticId).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch('analyticSection/lists', this.props).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        edit: function (analyticSection) {
            appService.modalShow();
            this.loading.isActive = true;
            this.$store.dispatch('analyticSection/edit', analyticSection.id);
            this.props.form = {
                name: analyticSection.name,
                data: analyticSection.data,
                section: analyticSection.section,
            };
            this.loading.isActive = false;
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('analyticSection/destroy', { analyticId: this.props.analyticId, id: id, search: this.props.search }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, this.$t('menu.analytic_section'));
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
