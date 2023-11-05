<template>
    <LoadingComponent :props="loading" />
    <div class="db-card">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ $t('label.orders') }}</h3>
        </div>
        <div class="db-card-body">
            <div class="row">
                <div class="col-12 md:col-6 lg:col-6 xl:col-4" v-if="deliveredOrders.length > 0"
                    v-for="order in deliveredOrders" :key="order">
                    <div class="w-full rounded-lg py-2 px-3 flex items-center gap-5 border border-[#EFF0F6]">
                        <i class="lab lab-reserve lab-font-size-24 lab-font-color-2"></i>
                        <div class="w-full">
                            <div class="flex items-center gap-4 mb-1">
                                <p class="text-sm leading-6 font-rubik">{{ $t('label.order_id') }}: <span
                                        class="text-heading">#{{
                                            order.order_serial_no
                                        }}</span>
                                </p>
                                <span
                                    class="py-0.5 px-2 rounded-full text-[10px] font-rubik leading-4 capitalize text-heading bg-[#BDEFFF]"
                                    :class="orderStatusClass(order.status)">
                                    {{ order.status_name }}</span>
                            </div>
                            <p class="text-xs font-light font-rubik mb-0.5">{{ order.order_items }} {{
                                $t("label.items")
                            }}</p>
                            <p class="text-xs font-light font-rubik mb-1">{{ order.order_datetime }}</p>
                            <div class="flex items-center justify-between">
                                <p class="text-sm leading-6 font-rubik capitalize text-heading">{{
                                    $t("label.total")
                                }}: <span class="font-medium">{{ order.total_currency_price }}</span></p>
                                <router-link
                                    :to="{ name: 'admin.delivery-boys.delivered-order.details', params: { id: props, orderId: order.id } }"
                                    class="text-[10px] leading-4 font-medium font-rubik flex items-center gap-1.5 text-primary">
                                    {{ $t("label.see_order_details") }}
                                    <i class="lab lab-arrow-right lab-font-size-13"></i>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-6">
            <PaginationSMBox :pagination="deliveredOrderPagination" :method="deliveredOrderLists" />
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <PaginationTextComponent :props="{ page: deliveredOrderPage }" />
                <PaginationBox :pagination="deliveredOrderPagination" :method="deliveredOrderLists" />
            </div>
        </div>
    </div>
</template>

<script>
import alertService from "../../../../services/alertService";
import LoadingComponent from "../../components/LoadingComponent";
import TableLimitComponent from "../../components/TableLimitComponent";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent";
import PaginationBox from "../../components/pagination/PaginationBox";
import PaginationSMBox from "../../components/pagination/PaginationSMBox";
import appService from "../../../../services/appService";

export default {
    name: "DeliveredOrderList",
    components: {
        LoadingComponent,
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent
    },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            search: {
                paginate: 1,
                page: 1,
                per_page: 9,
                order_column: 'id',
            }
        }
    },
    mounted() {
        this.deliveredOrderLists();
    },
    computed: {
        deliveredOrders: function () {
            return this.$store.getters['deliveryBoyOrder/deliveredOrders'];
        },
        deliveredOrderPagination: function () {
            return this.$store.getters["deliveryBoyOrder/deliveredOrderPagination"];
        },
        deliveredOrderPage: function () {
            return this.$store.getters["deliveryBoyOrder/deliveredOrderPage"];
        },
    },
    methods: {
        orderStatusClass: function (status) {
            return appService.orderStatusClass(status);
        },
        deliveredOrderLists: function (page = 1) {
            this.loading.isActive = true;
            this.search.page = page;
            this.$store.dispatch('deliveryBoyOrder/deliveredOrders', {
                id: this.props,
                search: this.search
            }).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
    }

}
</script>
