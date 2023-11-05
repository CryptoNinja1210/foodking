<template>
    <section class="col-12 pt-4 pb-4">
        <router-link to="" @click="$router.go(-1)" class="mb-3 inline-flex items-center gap-2 text-primary">
            <i class="lab lab-undo lab-font-size-16"></i>
            <span class="text-xs font-medium leading-6">{{ $t('label.back_to_orders') }}</span>
        </router-link>
        <div class="flex items-start flex-col md:flex-row gap-6">
            <div class="w-full">
                <div class="p-4 mb-6 rounded-2xl shadow-xs bg-white">
                    <h3 class="text-sm leading-6 mb-1 font-medium">{{ $t("label.order_id") }}: <span
                            class="text-[#008BBA]">#{{ order.order_serial_no }}</span></h3>
                    <p class="text-xs font-light mb-3">{{ order.order_datetime }}</p>
                    <div class="flex flex-wrap items-center gap-2 mb-5">
                        <span class="text-sm capitalize">{{ $t("label.order_type") }}:</span>
                        <span class="text-sm capitalize text-heading">
                            {{ enums.orderTypeEnumArray[order.order_type] }}
                        </span>
                    </div>

                    <OrderStatusComponent :props="order" />
                    <div>
                        <h3 class="font-medium mb-2">{{ orderBranch.name }}</h3>
                        <div class="flex items-center justify-between gap-5">
                            <div class="flex items-start justify-start gap-2.5">
                                <i class="lab lab-location leading-none mt-1.5 flex-shrink-0 lab-font-size-14"></i>
                                <span class="text-sm leading-6 text-heading">{{ orderBranch.address }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4" v-if="parseInt(order.status) === parseInt(enums.orderStatusEnum.REJECTED)">
                        <h3 class="capitalize font-medium text-sm leading-6 mb-2">{{ $t("label.reason") }}:</h3>
                        <p class="text-sm text-heading mb-2">{{ order.reason }}</p>
                    </div>
                </div>
                <div class="p-4 mb-6 rounded-2xl shadow-xs bg-white"
                    v-if="orderAddress && order.order_type === enums.orderTypeEnum.DELIVERY">
                    <h3 class="text-sm leading-6 font-medium mb-2">{{ $t("label.delivery_address") }}</h3>
                    <div class="flex items-start justify-start gap-2.5">
                        <i class="lab lab-location leading-none mt-1.5 flex-shrink-0 lab-font-size-14"></i>
                        <span class="text-sm leading-6 text-heading">
                            {{ orderAddress.apartment ? orderAddress.apartment + ', ' : '' }}
                            {{ orderAddress.address }}
                        </span>
                    </div>
                </div>

                <div v-if="parseInt(order.status) !== parseInt(enums.orderStatusEnum.REJECTED) && parseInt(order.status) !== parseInt(enums.orderStatusEnum.CANCELED)"
                    class="p-4 rounded-2xl shadow-xs bg-white">
                    <h3 class="capitalize font-medium text-sm leading-6 mb-2">{{ $t("label.payment_info") }}</h3>
                    <ul class="flex flex-col gap-2">
                        <li class="flex items-center gap-2">
                            <span class="capitalize text-sm leading-6">{{ $t("label.method") }}:</span>
                            <span v-if="order.transaction" class="capitalize text-sm leading-6 text-heading">
                                {{ order.transaction.payment_method }} ({{ order.transaction.transaction_no }})
                            </span>
                            <span v-else class="capitalize text-sm leading-6 text-heading">
                                {{ enums.paymentTypeEnumArray[order.payment_method] }}
                            </span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="capitalize text-sm leading-6">{{ $t("label.status") }}:</span>
                            <span class="capitalize text-sm leading-6"
                                :class="enums.paymentStatusEnum.PAID === order.payment_status ? 'text-green-600' : 'text-[#FB4E4E]'">
                                {{ enums.paymentStatusEnumArray[order.payment_status] }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="w-full rounded-2xl shadow-xs bg-white">
                <div class="p-4 border-b">
                    <h3 class="font-medium text-sm leading-6 capitalize mb-4">{{ $t('label.order_details') }}</h3>
                    <div class="pl-3">
                        <div class="mb-3 pb-3 border-b last:mb-0 last:pb-0 last:border-b-0 border-gray-2"
                            v-if="orderItems.length > 0" v-for="item in orderItems" :key="item">
                            <div class="flex items-center gap-3 relative">
                                <h3
                                    class="absolute top-5 -left-3 text-sm w-[26px] h-[26px] leading-[26px] text-center rounded-full text-white bg-heading">
                                    {{ item.quantity }}</h3>
                                <img class="w-16 h-16 rounded-lg flex-shrink-0" :src="item.item_image" alt="thumbnail">
                                <div class="w-full">
                                    <a href="#"
                                        class="text-sm font-medium capitalize transition text-heading hover:underline">{{
                                            item.item_name
                                        }}</a>
                                    <p v-if="item.item_variations.length > 0" class="capitalize text-xs mb-1.5">
                                        <span v-for="variation in item.item_variations" :key="variation">
                                            <span class="capitalize text-xs w-fit whitespace-nowrap">
                                                {{ variation.variation_name }}:&nbsp;
                                            </span>
                                            <span class="text-xs">
                                                {{ variation.name }}
                                            </span>
                                        </span>
                                    </p>

                                    <h3 class="text-xs font-semibold">{{ item.price }}</h3>
                                </div>
                            </div>
                            <ul class="flex flex-col gap-1.5 mt-2">
                                <li class="flex gap-1" v-if="item.item_extras.length > 0">
                                    <h3 class="capitalize text-xs w-fit whitespace-nowrap">{{
                                        $t('label.extras')
                                    }}:</h3>
                                    <p class="text-xs" v-for="(extra, index) in item.item_extras">
                                        {{ extra.name }}<span v-if="index + 1 < item.item_extras.length">, </span>
                                    </p>
                                </li>
                                <li class="flex gap-1" v-if="item.instruction">
                                    <h3 class="capitalize text-xs w-fit whitespace-nowrap">
                                        {{ $t('label.instruction') }}:</h3>
                                    <p class="text-xs">{{ item.instruction }}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <div class="rounded-xl border border-[#EFF0F6]">
                        <ul class="flex flex-col gap-2 p-3 border-b border-dashed border-[#EFF0F6]">
                            <li class="flex items-center justify-between text-heading">
                                <span class="text-sm leading-6 capitalize">{{ $t("label.subtotal") }}</span>
                                <span class="text-sm leading-6 capitalize">
                                    {{ order.subtotal_currency_price }}
                                </span>
                            </li>
                            <li class="flex items-center justify-between text-heading">
                                <span class="text-sm leading-6 capitalize">{{ $t("label.discount") }}</span>
                                <span class="text-sm leading-6 capitalize">
                                    {{ order.discount_currency_price }}
                                </span>
                            </li>
                            <li class="flex items-center justify-between text-heading"
                                v-if="order.order_type === enums.orderTypeEnum.DELIVERY">
                                <span class="text-sm leading-6 capitalize">{{ $t("label.delivery_charge") }}</span>
                                <span class="text-sm leading-6 capitalize font-medium text-[#1AB759]">
                                    {{ order.delivery_charge_currency_price }}</span>
                            </li>
                        </ul>
                        <div class="flex items-center justify-between p-3">
                            <h4 class="text-sm leading-6 font-semibold capitalize">{{ $t("label.total") }}</h4>
                            <h5 class="text-sm leading-6 font-semibold capitalize">
                                {{ order.total_currency_price }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>

import LoadingComponent from "./LoadingComponent";
import orderStatusEnum from "../../../enums/modules/orderStatusEnum";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";
import paymentStatusEnum from "../../../enums/modules/paymentStatusEnum";
import paymentTypeEnum from "../../../enums/modules/paymentTypeEnum";
import OrderStatusComponent from "./OrderStatusComponent";

export default {
    name: "OrderDetailsComponent",
    components: { LoadingComponent, OrderStatusComponent },
    props: {
        order: Object,
        orderItems: Object,
        orderBranch: Object,
        orderAddress: Object,
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                orderStatusEnum: orderStatusEnum,
                orderTypeEnum: orderTypeEnum,
                paymentStatusEnum: paymentStatusEnum,
                paymentTypeEnum: paymentTypeEnum,
                orderStatusEnumArray: {
                    [orderStatusEnum.PENDING]: this.$t("label.pending"),
                    [orderStatusEnum.ACCEPT]: this.$t("label.accept"),
                    [orderStatusEnum.PROCESSING]: this.$t("label.processing"),
                    [orderStatusEnum.OUT_FOR_DELIVERY]: this.$t("label.out_for_delivery"),
                    [orderStatusEnum.DELIVERED]: this.$t("label.delivered"),
                    [orderStatusEnum.CANCELED]: this.$t("label.canceled"),
                    [orderStatusEnum.REJECTED]: this.$t("label.rejected"),
                },
                orderTypeEnumArray: {
                    [orderTypeEnum.DELIVERY]: this.$t("label.delivery"),
                    [orderTypeEnum.TAKEAWAY]: this.$t("label.takeaway")
                },
                paymentStatusEnumArray: {
                    [paymentStatusEnum.PAID]: this.$t("label.paid"),
                    [paymentStatusEnum.UNPAID]: this.$t("label.unpaid")
                },
                paymentTypeEnumArray: {
                    [paymentTypeEnum.CASH_ON_DELIVERY]: this.$t("label.cash_on_delivery"),
                    [paymentTypeEnum.E_WALLET]: this.$t("label.e_wallet"),
                    [paymentTypeEnum.PAYPAL]: this.$t("label.paypal")
                },
            },
        };
    }
}
</script>
