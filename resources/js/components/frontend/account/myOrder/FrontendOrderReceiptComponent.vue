<template>
    <button type="button" v-print="printObj"
        class="flex w-full items-center justify-center gap-2 px-4 py-3.5 rounded-full shadow-db-card bg-[#1AB759]">
        <i class="lab lab-printer-line text-base font-medium text-white"></i>
        <span class="text-base font-medium capitalize text-white"> {{ $t('button.print_invoice') }}</span>
    </button>

    <div id="receipt" class="modal">
        <div class="modal-dialog max-w-[340px] rounded-none" id="print">
            <div class="modal-body">
                <div class="text-center pb-3.5 border-b border-dashed border-gray-400">
                    <h3 class="text-2xl font-bold mb-1">{{ company.company_name }}</h3>
                    <h4 class="text-sm font-normal">{{ branch.address }}</h4>
                    <h5 class="text-sm font-normal">Tel: {{ branch.phone }}</h5>
                </div>

                <table class="w-full my-1.5">
                    <tr>
                        <td class="text-xs text-left py-0.5 text-heading">{{ $t('button.order') }}
                            #{{ order.order_serial_no }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-xs text-left py-0.5 text-heading">{{ order.order_date }}</td>
                        <td class="text-xs text-right py-0.5 text-heading">{{ order.order_time }}</td>
                    </tr>
                </table>

                <table class="w-full">
                    <thead class="border-t border-b border-dashed border-gray-400">
                        <tr>
                            <th scope="col" class="py-1 font-normal text-xs capitalize text-left text-heading w-8">
                                {{ $t('label.qty') }}
                            </th>
                            <th scope="col"
                                class="py-1 font-normal text-xs capitalize flex items-center justify-between text-heading">
                                <span>{{ $t('label.item_description') }}</span>
                                <span>{{ $t('label.price') }}</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="border-b border-dashed border-gray-400">
                        <tr v-if="orderItems.length > 0" v-for="item in orderItems" :key="item">
                            <td class="text-left font-normal align-top py-1">
                                <p class="text-xs leading-5 text-heading">{{ item.quantity }}</p>
                            </td>
                            <td class="text-left font-normal align-top py-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-normal capitalize">{{ item.item_name }}</h4>
                                    <p class="text-xs leading-5 text-heading">{{ item.total_without_tax_currency_price }}
                                    </p>
                                </div>
                                <p v-if="Object.keys(item.item_variations).length !== 0"
                                    class="text-xs leading-5 font-normal text-heading max-w-[200px]">
                                    <span v-for="(variation, index) in item.item_variations">
                                        {{ variation.variation_name }}: {{ variation.name }}
                                        <span v-if="index + 1 < Object.keys(item.item_variations).length">, </span>
                                    </span>
                                </p>
                                <p v-if="item.item_extras.length > 0"
                                    class="text-xs leading-5 font-normal text-heading max-w-[200px]">
                                    {{ $t('label.extras') }}:
                                    <span v-for="(extra, index) in item.item_extras">
                                        {{ extra.name }}
                                        <span v-if="index + 1 < item.item_extras.length">, </span>
                                    </span>
                                </p>
                                <p v-if="item.instruction" class="text-xs leading-5 font-normal text-heading max-w-[200px]">
                                    {{ $t('label.instruction') }}: {{ item.instruction }}
                                </p>

                                <div class="flex items-center justify-between" v-if="item.tax_rate > 0">
                                    <p class="text-xs leading-5 font-normal text-heading">{{ item.tax_name }}
                                        ({{ item.tax_currency_rate }} {{item.tax_type}})</p>
                                    <p class="text-xs leading-5 font-normal text-heading">
                                        {{ item.tax_currency_amount }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="py-2 pl-7">
                    <table class="w-full">
                        <tr>
                            <td class="text-xs text-left py-0.5 uppercase text-heading">{{ $t('label.subtotal') }}:</td>
                            <td class="text-xs text-right py-0.5 text-heading">
                                {{ order.subtotal_without_tax_currency_price }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-xs text-left py-0.5 uppercase text-heading">
                                {{ $t('label.total_tax') }}:
                            </td>
                            <td class="text-xs text-right py-0.5 text-heading">
                                {{ order.total_tax_currency_price }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-xs text-left py-0.5 uppercase text-heading">{{ $t('label.discount') }}:</td>
                            <td class="text-xs text-right py-0.5 text-heading">{{ order.discount_currency_price }}</td>
                        </tr>

                        <tr v-if="order.order_type === enums.orderTypeEnum.DELIVERY">
                            <td class="text-xs text-left py-0.5 uppercase text-heading">{{ $t('label.delivery_charge') }}:
                            </td>
                            <td class="text-xs text-right py-0.5 text-heading">{{ order.delivery_charge_currency_price }}
                            </td>
                        </tr>

                        <tr>
                            <td class="text-xs text-left py-0.5 font-bold uppercase text-heading">
                                {{ $t('label.total') }}:
                            </td>
                            <td class="text-xs text-right py-0.5 font-bold text-heading">
                                {{ order.total_currency_price }}
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="text-xs py-1 border-t border-b border-dashed border-gray-400 text-heading">
                    <table>
                        <tbody>
                            <tr>
                                <td class="pt-1 pb-1 pr-1"> {{ $t('label.order_type') }}:</td>
                                <td class="pt-1 pb-1">{{ enums.orderTypeEnumArray[order.order_type] }}</td>
                            </tr>
                            <tr>
                                <td class="pt-1 pb-1 pr-1">{{ $t('label.payment_type') }}:</td>
                                <td v-if="order.transaction" class="pt-1 pb-1">{{ order.transaction.payment_method }}</td>
                                <td v-else class="pt-1 pb-1">{{ enums.paymentTypeEnumArray[order.payment_method] }}</td>
                            </tr>
                            <tr>
                                <td class="pt-1 pb-1 pr-1">{{ $t('label.delivery_time') }}:</td>
                                <td class="pt-1 pb-1">{{ order.delivery_date }} {{ order.delivery_time }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-xs py-1 border-b border-dashed border-gray-400 text-heading">
                    <table>
                        <tbody>
                            <tr>
                                <td class="pt-1 pb-1 pr-1">{{ $t('label.customer') }}:</td>
                                <td class="pt-1 pb-1">{{ orderUser.name }}</td>
                            </tr>
                            <tr>
                                <td class="pt-1 pb-1 pr-1">{{ $t('label.phone') }}:</td>
                                <td class="pt-1 pb-1">{{ orderUser.country_code + '' + orderUser.phone }}</td>
                            </tr>
                            <tr v-if="order.order_type === enums.orderTypeEnum.DELIVERY">
                                <td class="pt-1 pb-1 pr-1">{{ $t('label.address') }}:</td>
                                <td class="pt-1 pb-1">
                                    {{ orderAddress.apartment ? orderAddress.apartment + ', ' : '' }}
                                    {{ orderAddress.address }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-center pt-2 pb-4">
                    <p class="text-[11px] leading-[14px] capitalize text-heading">{{ $t('message.thank_you') }}</p>
                </div>

                <div class="flex flex-col items-end">
                    <h5 class="text-[8px] font-normal text-left w-[46px] leading-[10px]">
                        {{ $t('label.powered_by') }}
                    </h5>
                    <h6 class="text-xs font-normal leading-4">{{ company.company_name }}</h6>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import print from "vue3-print-nb";
import orderTypeEnum from "../../../../enums/modules/orderTypeEnum";
import paymentTypeEnum from "../../../../enums/modules/paymentTypeEnum";

export default {
    name: "FrontendOrderReceiptComponent",
    props: {
        order: Object,
        orderItems: Object,
        orderUser: Object,
        orderAddress: Object
    },
    directives: {
        print
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            printObj: {
                id: "print",
                popTitle: this.$t("menu.order_receipt"),
            },
            enums: {
                orderTypeEnum: orderTypeEnum,
                orderTypeEnumArray: {
                    [orderTypeEnum.DELIVERY]: this.$t("label.delivery"),
                    [orderTypeEnum.TAKEAWAY]: this.$t("label.takeaway")
                },
                paymentTypeEnumArray: {
                    [paymentTypeEnum.CASH_ON_DELIVERY]: this.$t("label.cash_on_delivery")
                }
            }
        }
    },
    mounted() {
        this.$store.dispatch("company/lists").then().catch();
    },
    computed: {
        company: function () {
            return this.$store.getters['company/lists'];
        },
        branch: function () {
            return this.$store.getters['backendGlobalState/branchShow'];
        }
    }
}
</script>
