<template>
    <LoadingComponent :props="loading" />
    <OrderDetailsComponent :order="order" :orderItems="orderItems" :orderBranch="orderBranch"
        :orderAddress="orderAddress" />
</template>
<script>

import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";
import OrderDetailsComponent from "../components/OrderDetailsComponent";
export default {
    name: "DeliveryBoyOrderDetailsComponent",
    components: { LoadingComponent, OrderDetailsComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
        };
    },
    computed: {
        order: function () {
            return this.$store.getters['myOrderDetails/orderDetails'];
        },
        orderItems: function () {
            return this.$store.getters['myOrderDetails/orderItems'];
        },
        orderBranch: function () {
            return this.$store.getters['myOrderDetails/orderBranch'];
        },
        orderAddress: function () {
            return this.$store.getters['myOrderDetails/orderAddress'];
        },
    },
    mounted() {
        if (this.$route.params.id) {
            this.loading.isActive = true;
            this.$store.dispatch("myOrderDetails/orderDetails", {
                id: this.$route.params.id,
                orderId: this.$route.params.orderId
            }).then((res) => {
                this.loading.isActive = false;
            }).catch((error) => {
                this.loading.isActive = false;

            });
        }
    },
    methods: {
        orderStatusClass: function (status) {
            return appService.orderStatusClass(status);
        },
    }
}
</script>