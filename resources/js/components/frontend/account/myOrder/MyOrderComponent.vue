<template>
    <LoadingComponent :props="loading" />
    <section class="pt-8 pb-16">
        <div class="container max-w-3xl">
            <router-link :to="{ name: 'frontend.home' }" class="mb-3 inline-flex items-center gap-2 text-primary">
                <i class="lab lab-undo lab-font-size-16"></i>
                <span class="text-xs font-medium leading-6">{{ $t('label.back_to_home') }}</span>
            </router-link>
            <div class="flex items-start flex-col md:flex-row gap-6">
                <div class="w-full">
                    <h3 class="capitalize font-medium text-[26px] leading-[40px] mb-4 pl-5 md:pl-0 text-[#008BBA]">
                        {{ $t('label.active_orders') }}
                    </h3>
                    <ul class="w-full p-4 rounded-2xl shadow-xs flex flex-col gap-4 bg-white"
                        v-if="activeOrders.length > 0">
                        <li class="w-full rounded-2xl bg-white" v-for="activeOrder in activeOrders" :key="activeOrder">
                            <div class="w-full rounded-lg py-2 px-3 flex items-center gap-5 border border-[#EFF0F6]">
                                <i class="lab lab-reserve lab-font-size-32 lab-color-blue"></i>
                                <div class="w-full">
                                    <div class="flex flex-wrap items-center gap-y-1 gap-x-3">
                                        <p class="text-sm leading-6 font-rubik">{{ $t("label.order_id") }}:
                                            <span class="text-heading">#
                                                {{ activeOrder.order_serial_no }}
                                            </span>
                                        </p>
                                        <span :class="orderStatusClass(activeOrder.status)">
                                            {{ enums.orderStatusEnumArray[activeOrder.status] }}
                                        </span>
                                    </div>
                                    <p class="text-xs font-light font-rubik mb-1">{{
                                        activeOrder.order_datetime
                                    }}
                                    </p>
                                    <p class="text-sm font-normal font-rubik capitalize mb-2 text-[#00749B]">
                                        {{ enums.orderTypeEnumArray[activeOrder.order_type] }}
                                    </p>
                                    <div class="flex flex-wrap gap-3 items-center justify-between">
                                        <p class="text-sm leading-6 font-rubik capitalize text-heading">{{
                                            $t("label.total")
                                        }}: <span class="font-medium">{{ activeOrder.total_currency_price }}</span>
                                        </p>
                                        <router-link
                                            :to="{ name: 'frontend.myOrder.details', params: { id: activeOrder.id } }"
                                            class="text-[10px] leading-4 font-medium font-rubik flex items-center gap-1.5 text-primary">
                                            {{ $t("label.see_details") }}
                                            <i class="lab lab-arrow-right lab-font-size-13"></i>
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="w-full">
                    <h3 class="capitalize font-medium text-[26px] leading-[40px] mb-4 pl-5 md:pl-0 text-[#008BBA]">
                        {{ $t('label.previous_orders') }}
                    </h3>
                    <ul class="w-full p-4 rounded-2xl shadow-xs flex flex-col gap-4 bg-white"
                        v-if="previousOrders.length > 0">
                        <li class="w-full rounded-lg py-2 px-3 flex items-center gap-5 border border-[#EFF0F6]"
                            v-for="previousOrder in previousOrders" :key="previousOrder">
                            <i class="lab lab-reserve lab-font-size-32 lab-color-blue"></i>
                            <div class="w-full">
                                <div class="flex flex-wrap items-center gap-y-1 gap-x-3">
                                    <p class="text-sm leading-6 font-rubik">{{ $t("label.order_id") }}: <span
                                            class="text-heading">#{{
                                                previousOrder.order_serial_no
                                            }}</span></p>
                                    <span :class="orderStatusClass(previousOrder.status)">
                                        {{ enums.orderStatusEnumArray[previousOrder.status] }}
                                    </span>
                                </div>
                                <p class="text-xs font-light font-rubik mb-1">{{
                                    previousOrder.order_datetime
                                }}</p>
                                <p class="text-sm font-normal font-rubik capitalize mb-2 text-[#00749B]">
                                    {{ enums.orderTypeEnumArray[previousOrder.order_type] }}
                                </p>
                                <div class="flex flex-wrap gap-3 items-center justify-between">
                                    <p class="text-sm leading-6 font-rubik capitalize text-heading">{{
                                        $t("label.total")
                                    }}:
                                        <span class="font-medium">{{ previousOrder.total_currency_price }}</span>
                                    </p>
                                    <router-link
                                        :to="{ name: 'frontend.myOrder.details', params: { id: previousOrder.id } }"
                                        class="text-[10px] leading-4 font-medium font-rubik flex items-center gap-1.5
                                                                                                                                                                                                                        text-primary">
                                        {{ $t("label.see_details") }}
                                        <i class="lab lab-arrow-right lab-font-size-13"></i>
                                    </router-link>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="flex items-center justify-between border-gray-200 bg-white px-4 py-6">
                        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                            <PaginationBox :pagination="pagination" :method="previousOrderList" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div v-if="Object.keys(order).length > 0" ref="confirmOrder" id="confirm-order" class="modal confirm-order ff-modal">
        <div class="modal-dialog max-w-[360px] relative">
            <button class="modal-close fa-regular fa-circle-xmark absolute top-5 right-5"
                @click.prevent="closeModal"></button>
            <div class="modal-body">
                <h3 class="capitalize text-base font-medium text-center mt-2 mb-3">
                    {{ $t('message.order_thank_you') }}
                </h3>
                <img class="w-[120px] mx-auto mb-3" :src="setting.image_confirm" alt="gif">
                <h3 class="capitalize text-lg font-medium text-center mb-3 text-primary">
                    {{ $t('label.order_confirmed') }}
                </h3>
                <p class="text-sm leading-6 mb-4">
                    {{ $t('message.order_confirm') }}
                    <b v-if="order.order_type === enums.orderTypeEnum.TAKEAWAY" class="font-medium">{{
                        $t('label.pick_and_apy') }}.
                    </b>
                    <b v-if="order.order_type === enums.orderTypeEnum.DELIVERY" class="font-medium">
                        {{ $t('label.cash_on_delivery') }}.
                    </b>
                    <strong class="font-normal" v-if="setting.site_online_payment_gateway === enums.activityEnum.ENABLE && order.transaction === null && order.payment_status === enums.paymentStatusEnum.UNPAID">
                        {{ $t('message.choosing_payment_options') }}
                    </strong>
                </p>
                <div class="flex items-end justify-between mb-4">
                    <div>
                        <h3 class="font-medium mb-1">{{ order.branch.name }}</h3>
                        <h5 class="capitalize text-[10px] font-normal leading-4">
                            {{ enums.orderTypeEnumArray[order.order_type] }}
                        </h5>
                    </div>
                    <div class="flex gap-4">
                        <router-link @click.prevent="closeModal"
                            class="w-8 h-8 rounded-full flex items-center justify-center bg-[#FFEDF4]"
                            :to="{ name: 'frontend.chat', query: { id: order.branch.id } }">
                            <i class="lab lab-messages-2 font-fill-primary lab-font-size-16"></i>
                        </router-link>
                        <a @click="closeModal" :href="'tel:' + order.branch.phone"
                            class="w-8 h-8 rounded-full flex items-center justify-center bg-[#FFEDF4]">
                            <i class="lab lab-call-calling font-fill-primary lab-font-size-16"></i>
                        </a>
                    </div>
                </div>

                <div class="flex gap-6" v-if="setting.site_online_payment_gateway === enums.activityEnum.ENABLE && order.transaction === null && order.payment_status === enums.paymentStatusEnum.UNPAID">
                    <router-link @click.prevent="closeModal"
                        class="w-full rounded-3xl text-center font-medium leading-6 py-3 border border-primary text-primary bg-white"
                        :to="{ name: 'frontend.myOrder.details', params: { id: order.id } }">
                        {{ $t('button.go_to_order') }}
                    </router-link>
                    <a :href="'/payment/' + order.id + '/pay'"
                        class="w-full rounded-3xl text-center font-medium leading-6 py-3 text-white bg-primary">
                        {{ $t('button.pay_now') }}
                    </a>
                </div>

                <router-link v-else @click.prevent="closeModal"
                             class="w-full rounded-3xl text-center font-medium leading-6 py-3 text-white bg-primary"
                             :to="{ name: 'frontend.myOrder.details', params: { id: order.id } }">
                    {{ $t('button.go_to_order') }}
                </router-link>

            </div>
        </div>
    </div>
</template>

<script>


import orderStatusEnum from "../../../../enums/modules/orderStatusEnum";
import appService from "../../../../services/appService";
import LoadingComponent from "../../components/LoadingComponent";
import orderTypeEnum from "../../../../enums/modules/orderTypeEnum";
import PaginationSMBox from "../../../admin/components/pagination/PaginationSMBox"
import PaginationBox from "../../../admin/components/pagination/PaginationBox"
import PaginationTextComponent from "../../../admin/components/pagination/PaginationTextComponent"
import activityEnum from "../../../../enums/modules/activityEnum";
import paymentStatusEnum from "../../../../enums/modules/paymentStatusEnum";


export default {
    name: "MyOrderComponent",
    components: {
        LoadingComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                activityEnum: activityEnum,
                paymentStatusEnum: paymentStatusEnum,
                orderStatusEnum: orderStatusEnum,
                orderTypeEnum: orderTypeEnum,
                orderStatusEnumArray: {
                    [orderStatusEnum.PENDING]: this.$t("label.pending"),
                    [orderStatusEnum.ACCEPT]: this.$t("label.accept"),
                    [orderStatusEnum.PROCESSING]: this.$t("label.processing"),
                    [orderStatusEnum.OUT_FOR_DELIVERY]: this.$t("label.out_for_delivery"),
                    [orderStatusEnum.DELIVERED]: this.$t("label.delivered"),
                    [orderStatusEnum.CANCELED]: this.$t("label.canceled"),
                    [orderStatusEnum.REJECTED]: this.$t("label.rejected"),
                    [orderStatusEnum.RETURNED]: this.$t("label.returned"),
                },
                orderTypeEnumArray: {
                    [orderTypeEnum.DELIVERY]: this.$t("label.delivery"),
                    [orderTypeEnum.TAKEAWAY]: this.$t("label.takeaway")
                },
            },
            active: {
                excepts: orderStatusEnum.DELIVERED + "|" + orderStatusEnum.CANCELED + "|" + orderStatusEnum.REJECTED + "|" + orderStatusEnum.RETURNED,
            },
            previous: {
                paginate: 1,
                page: 1,
                per_page: 5,
                excepts: orderStatusEnum.PENDING + "|" + orderStatusEnum.ACCEPT + "|" + orderStatusEnum.PROCESSING + "|" + orderStatusEnum.OUT_FOR_DELIVERY,
            }
        };
    },
    mounted() {
        try {
            this.loading.isActive = true;
            this.$store.dispatch('frontendOrder/activeOrder', {
                excepts: this.active.excepts,
            }).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });

            this.previousOrderList();

            if (Object.keys(this.$route.query).length > 0) {
                this.loading.isActive = true;
                this.$store.dispatch('frontendOrder/show', this.$route.query.id).then(res => {
                    const modalTarget = this.$refs.confirmOrder;
                    modalTarget?.classList?.add("active");
                    document.body.style.overflowY = "hidden";
                    this.loading.isActive = false;
                }).catch((err) => {
                    this.loading.isActive = false;
                });
            }
        } catch (err) {
            this.loading.isActive = false;
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        activeOrders: function () {
            return this.$store.getters['frontendOrder/activeOrder'];
        },
        previousOrders: function () {
            return this.$store.getters['frontendOrder/previousOrder'];
        },
        order: function () {
            return this.$store.getters['frontendOrder/show'];
        },
        pagination: function () {
            return this.$store.getters["frontendOrder/pagination"];
        },
        paginationPage: function () {
            return this.$store.getters["frontendOrder/page"];
        },
    },
    methods: {
        orderStatusClass: function (status) {
            return appService.orderStatusClass(status);
        },
        closeModal: function () {
            const modalTarget = this.$refs.confirmOrder;
            modalTarget?.classList?.remove("active");
            document.body.style.overflowY = "auto";
            this.loading.isActive = false;
        },
        previousOrderList: function (page = 1) {
            this.loading.isActive = true;
            this.previous.page = page;
            this.$store.dispatch('frontendOrder/previousOrder', this.previous).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        }
    }
}
</script>
