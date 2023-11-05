<template>
    <LoadingComponent :props="loading" />

    <div v-if="logged && activeOrders.length > 0"
        class="xst:rounded-t-2xl xst:fixed xst:bottom-12 xst:w-full xst:z-10 bg-[#FFEDF4] sm:bg-[#FF3B8E]">
        <div class="relative w-full lg:w-[930px] mx-auto flex items-center gap-4 py-2.5 sm:px-10 px-4 xst:pb-16">
            <i class="lab lab-routing font-fill-paragraph-toggle lab-font-size-24"></i>
            <div class="flex-auto">
                <h3 class="text-sm mb-1.5 font-medium first-letter:capitalize text-primary sm:text-white">
                    {{ $t('message.order_process_stage') }}</h3>
                <p v-if="Object.keys(currentOrder).length > 0 && parseInt(currentOrder.status) === parseInt(enums.orderStatusEnum.ACCEPT)"
                    class="text-xs text-primary sm:text-white">{{ $t('message.order_confirmed') }}</p>

                <p v-if="Object.keys(currentOrder).length > 0 && parseInt(currentOrder.status) === parseInt(enums.orderStatusEnum.PROCESSING)"
                    class="text-xs text-primary sm:text-white">{{ $t('message.order_processed') }}</p>

                <p v-if="Object.keys(currentOrder).length > 0 && parseInt(currentOrder.status) === parseInt(enums.orderStatusEnum.OUT_FOR_DELIVERY)"
                    class="text-xs text-primary sm:text-white">{{ $t('message.order_out_for_delivery') }}</p>
            </div>

            <img class="xst:absolute xst:bottom-5 xst:left-1/2 xst:-translate-x-1/2 sm:w-48 md:w-auto lg:mx-14"
                :src="settings.image_order_track" alt="bg">
            <router-link v-if="Object.keys(currentOrder).length > 0"
                :to="{ name: 'frontend.myOrder.details', params: { id: currentOrder.id } }"
                class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center bg-primary sm:bg-white">
                <i class="lab lab-arrow-right-long lab-font-size-24 font-fill-primary-toggle"></i>
            </router-link>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../frontend/components/LoadingComponent";
import orderStatusEnum from "../../../enums/modules/orderStatusEnum";

export default {
    name: "TrackOrderComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                orderStatusEnum: orderStatusEnum,
            },
            currentOrder: {},
            activeProps: {
                excepts: orderStatusEnum.DELIVERED + "|" + orderStatusEnum.CANCELED + "|" + orderStatusEnum.REJECTED + "|" + orderStatusEnum.RETURNED + "|" + orderStatusEnum.PENDING,
                order_column: "id",
                order_by: "asc"
            },
        }
    },
    computed: {
        logged: function () {
            return this.$store.getters.authStatus;
        },
        activeOrders: function () {
            return this.$store.getters['frontendOrder/activeOrder'];
        },
        settings: function () {
            return this.$store.getters['frontendSetting/lists'];
        }
    },
    mounted() {
        try {
            if (this.$store.getters.authStatus) {
                this.loading.isActive = true;
                this.$store.dispatch('frontendOrder/activeOrder', this.activeProps).then(res => {
                    if (res.data.data.length > 0) {
                        this.currentOrder = res.data.data[0];
                    }
                    this.loading.isActive = false;
                }).catch((err) => {
                    this.loading.isActive = false;
                });
            }
        } catch (err) {
            this.loading.isActive = false;
        }
    },
    watch: {
        activeOrders: {
            deep: true,
            handler(activeOrders) {
                if (activeOrders.length > 0) {
                    this.currentOrder = activeOrders[0];
                }
            }
        }
    }
}
</script>
