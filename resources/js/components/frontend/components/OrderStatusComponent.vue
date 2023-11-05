<template>
    <h4 v-if="parseInt(props.status) == parseInt(enums.orderStatusEnum.CANCELED)"
        class=" text-xl font-medium text-center mb-4">
        {{ $t("label.your_order", {
            order: enums.orderStatusEnumArray[props.status],
        }) }}
    </h4>
    <h4 v-if="parseInt(props.status) == parseInt(enums.orderStatusEnum.REJECTED)"
        class=" text-xl font-medium text-center mb-4">
        {{ $t("label.your_order", {
            order: enums.orderStatusEnumArray[props.status],
        }) }}
    </h4>
    <h4 v-if="parseInt(props.status) == parseInt(enums.orderStatusEnum.RETURNED)"
        class=" text-xl font-medium text-center mb-4">
        {{ $t("label.your_order", {
            order: enums.orderStatusEnumArray[props.status],
        }) }}
    </h4>

    <img v-if="parseInt(props.status) == parseInt(enums.orderStatusEnum.CANCELED)" class="h-32 mx-auto mb-3"
        :src="setting.image_order_canceled" alt="gif">

    <img v-if="parseInt(props.status) == parseInt(enums.orderStatusEnum.REJECTED)" class="h-32 mx-auto mb-3"
        :src="setting.image_order_rejected" alt="gif">

    <img v-if="parseInt(props.status) == parseInt(enums.orderStatusEnum.RETURNED)" class="h-32 mx-auto mb-3"
        :src="setting.image_order_returned" alt="gif">



    <div
        v-if="parseInt(props.status) !== parseInt(enums.orderStatusEnum.CANCELED) && parseInt(props.status) !== parseInt(enums.orderStatusEnum.RETURNED) && parseInt(props.status) !== parseInt(enums.orderStatusEnum.REJECTED)">
        <div
            v-if="parseInt(props.status) == parseInt(enums.orderStatusEnum.DELIVERED) && parseInt(props.order_type) == parseInt(enums.orderTypeEnum.DELIVERY)">
            <h4 class="text-xl font-medium text-center mb-4">{{ $t('label.your_order_has_been_delivered') }}</h4>
        </div>

        <div
            v-if="parseInt(props.status) !== parseInt(enums.orderStatusEnum.DELIVERED) && parseInt(props.order_type) == parseInt(enums.orderTypeEnum.DELIVERY)">
            <p class="text-xs text-center mb-4">{{ $t('label.estimated_delivery_time') }}</p>
            <h4 class="text-xl font-medium text-center mb-4">{{ props.preparation_time }} min</h4>
        </div>

        <img v-if="parseInt(props.status) == parseInt(enums.orderStatusEnum.DELIVERED) && parseInt(props.order_type) == parseInt(enums.orderTypeEnum.DELIVERY)"
            class="h-32 mx-auto mb-3" :src="setting.image_order_delivered" alt="gif">
        <img v-if="parseInt(props.status) == parseInt(enums.orderStatusEnum.DELIVERED) && parseInt(props.order_type) == parseInt(enums.orderTypeEnum.TAKEAWAY)"
            class="h-32 mx-auto mb-3" :src="setting.image_order_complete" alt="gif">
        <img v-if="parseInt(props.status) == parseInt(enums.orderStatusEnum.PENDING) || parseInt(props.status) == parseInt(enums.orderStatusEnum.ACCEPT)"
            class="h-32 mx-auto mb-3" :src="setting.image_order_placed" alt="gif">
        <img v-if="parseInt(props.status) == parseInt(enums.orderStatusEnum.PROCESSING)" class="h-32 mx-auto mb-3"
            :src="setting.image_order_preparing" alt="gif">
        <img v-if="parseInt(props.status) == parseInt(enums.orderStatusEnum.OUT_FOR_DELIVERY)"
            class="h-32 mx-auto mb-3" :src="setting.image_order_out_for_delivery" alt="gif">

        <h5 v-if="parseInt(props.status) == parseInt(enums.orderStatusEnum.DELIVERED)"
            class="text-xs font-normal text-center mb-8">{{ $t("message.enjoy_your_food") }}</h5>
        <h5 v-if="parseInt(props.status) == parseInt(enums.orderStatusEnum.PENDING)"
            class="text-xs font-normal text-center mb-8">
            {{ $t("label.got_your_order", { name: profile.name, }) }}
        </h5>
        <h5 v-if="parseInt(props.status) == parseInt(enums.orderStatusEnum.ACCEPT)"
            class="text-xs font-normal text-center mb-8">{{ $t("message.Your_order_is_accepted") }}</h5>
        <h5 v-if="parseInt(props.status) == parseInt(enums.orderStatusEnum.PROCESSING)"
            class="text-xs font-normal text-center mb-8">{{ $t("message.the_chef_is_preparing_your_food") }}</h5>

        <h5 v-if="parseInt(props.status) == parseInt(enums.orderStatusEnum.OUT_FOR_DELIVERY)"
            class="text-xs font-normal text-center mb-8">{{ $t("message.delivery_man_is_on_the_way") }}
        </h5>

        <ul
            class="flex items-center justify-between px-2 mx-2 mb-[70px] relative before:absolute before:top-0 before:left-0 before:w-full before:h-1 before:bg-primary">

            <li v-if="parseInt(props.order_type) == parseInt(enums.orderTypeEnum.DELIVERY)"
                v-for="(delivery, index) in enums.deliveryArray" :key="index"
                class="db-order-status relative before:absolute before:-top-2 before:left-1/2 before:-translate-x-1/2 before:w-5 before:h-5 before:rounded-full before:border-[3px] before:border-primary before:bg-white"
                :class="parseInt(props.status) >= parseInt(index) ? 'check' : ''">
                <span class="absolute -bottom-12 left-1/2 -translate-x-1/2 text-[10px] leading-4 text-center text-heading">
                    {{ $t('menu.order') }} {{ delivery }}
                </span>
            </li>
            <li v-if="parseInt(props.order_type) == parseInt(enums.orderTypeEnum.TAKEAWAY)"
                v-for="(takeaway, index) in enums.takeawayArray" :key="index"
                class="db-order-status relative before:absolute before:-top-2 before:left-1/2 before:-translate-x-1/2 before:w-5 before:h-5 before:rounded-full before:border-[3px] before:border-primary before:bg-white"
                :class="parseInt(props.status) >= parseInt(index) ? 'check' : ''">
                <span class="absolute -bottom-12 left-1/2 -translate-x-1/2 text-[10px] leading-4 text-center text-heading">
                    {{ $t('menu.order') }} {{ takeaway }}
                </span>
            </li>
        </ul>
    </div>
</template>

<script>

import orderStatusEnum from "../../../enums/modules/orderStatusEnum";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";

export default {
    name: "OrderStatusComponent",
    components: {},
    props: ['props'],
    data() {
        return {
            statusFlag: true,
            enums: {
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
                deliveryArray: {
                    [orderStatusEnum.PENDING]: this.$t("label.placed"),
                    [orderStatusEnum.ACCEPT]: this.$t("label.accept"),
                    [orderStatusEnum.PROCESSING]: this.$t("label.processing"),
                    [orderStatusEnum.OUT_FOR_DELIVERY]: this.$t("label.out_for_delivery"),
                    [orderStatusEnum.DELIVERED]: this.$t("label.delivered"),
                },
                takeawayArray: {
                    [orderStatusEnum.PENDING]: this.$t("label.placed"),
                    [orderStatusEnum.ACCEPT]: this.$t("label.accept"),
                    [orderStatusEnum.PROCESSING]: this.$t("label.processing"),
                    [orderStatusEnum.DELIVERED]: this.$t("label.completed"),
                }
            },
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        profile: function () {
            return this.$store.getters.authInfo;
        },
    },
}
</script>
