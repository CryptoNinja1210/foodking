<template>
    <LoadingComponent :props="loading"/>

    <div class="db-card db-tab-div active">
        <div class="db-card-header border-none">
            <h3 class="db-card-title">{{ $t("menu.currencies") }}</h3>
            <div class="db-card-filter">
                <TableLimitComponent :method="list" :search="props.search" :page="paginationPage"/>
                <CurrencyCreateComponent :props="props"/>
            </div>
        </div>

        <div class="db-table-responsive">
            <table class="db-table stripe">
                <thead class="db-table-head">
                <tr class="db-table-head-tr">
                    <th class="db-table-head-th">{{ $t("label.name") }}</th>
                    <th class="db-table-head-th">
                        {{ $t("label.symbol") }}
                    </th>
                    <th class="db-table-head-th">
                        {{ $t("label.code") }}
                    </th>
                    <th class="db-table-head-th">
                        {{ $t("label.is_cryptocurrency") }}
                    </th>
                    <th class="db-table-head-th">
                        {{ $t("label.exchange_rate") }}
                    </th>
                    <th class="db-table-head-th">
                        {{ $t("label.action") }}
                    </th>
                </tr>
                </thead>
                <tbody class="db-table-body" v-if="currencies.length > 0">
                <tr class="db-table-body-tr" v-for="currency in currencies" :key="currency">
                    <td class="db-table-body-td" v-if="site_default_currency === currency.id">
                        {{ currency.name }}({{ $t('label.default') }})
                    </td>
                    <td class="db-table-body-td" v-else>
                        {{ currency.name }}
                    </td>
                    <td class="db-table-body-td">
                        {{ currency.symbol }}
                    </td>
                    <td class="db-table-body-td">
                        {{ currency.code }}
                    </td>
                    <td class="db-table-body-td">
                            <span :class="askClass(currency.is_cryptocurrency)">
                                {{ enums.askEnumArray[currency.is_cryptocurrency] }}
                            </span>
                    </td>
                    <td class="db-table-body-td">
                        {{ currency.exchange_rate }}
                    </td>

                    <td class="db-table-body-td">
                        <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                            <SmModalEditComponent @click="edit(currency)"/>
                            <SmDeleteComponent @click="destroy(currency.id)"
                                               v-if="site_default_currency != currency.id"/>
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
import CurrencyCreateComponent from "./CurrencyCreateComponent";
import alertService from "../../../../services/alertService";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent";
import PaginationBox from "../../components/pagination/PaginationBox";
import PaginationSMBox from "../../components/pagination/PaginationSMBox";
import appService from "../../../../services/appService";
import TableLimitComponent from "../../components/TableLimitComponent";
import SmDeleteComponent from "../../components/buttons/SmDeleteComponent";
import SmModalEditComponent from "../../components/buttons/SmModalEditComponent";
import askEnum from "../../../../enums/modules/askEnum";

export default {
    name: "CurrencyListComponent",
    components: {
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        CurrencyCreateComponent,
        LoadingComponent,
        SmDeleteComponent,
        SmModalEditComponent,
        askEnum,
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                askEnum: askEnum,
                askEnumArray: {
                    [askEnum.YES]: this.$t("label.yes"),
                    [askEnum.NO]: this.$t("label.no"),
                },
            },
            props: {
                form: {
                    name: "",
                    symbol: "",
                    code: "",
                    is_cryptocurrency: askEnum.NO,
                    exchange_rate: "",
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: "id",
                    order_type: "desc",
                },
            },
            site_default_currency: null,
        };
    },
    mounted() {
        this.list();
        this.siteList();
    },
    computed: {
        currencies: function () {
            return this.$store.getters["currency/lists"];
        },
        pagination: function () {
            return this.$store.getters["currency/pagination"];
        },
        paginationPage: function () {
            return this.$store.getters["currency/page"];
        },
    },
    methods: {
        askClass: function (ask) {
            return appService.askClass(ask);
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        siteList: function () {
            this.loading.isActive = true;
            this.$store.dispatch('site/lists').then(res => {
                this.site_default_currency = res.data.data.site_default_currency;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });

        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch("currency/lists", this.props.search).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        edit: function (tax) {
            appService.modalShow();
            this.loading.isActive = true;
            this.$store.dispatch("currency/edit", tax.id);
            this.props.form = {
                name: tax.name,
                symbol: tax.symbol,
                code: tax.code,
                is_cryptocurrency: tax.is_cryptocurrency,
                exchange_rate: tax.exchange_rate,
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
                            .dispatch("currency/destroy", {
                                id: id,
                                search: this.props.search,
                            })
                            .then((res) => {
                                this.loading.isActive = false;
                                alertService.successFlip(
                                    null,
                                    this.$t("menu.currencies")
                                );
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
