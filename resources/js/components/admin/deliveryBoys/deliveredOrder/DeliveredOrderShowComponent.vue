<template>
    <LoadingComponent :props="loading" />
    <OrderDetailsComponent :order="order" :orderItems="orderItems" :orderBranch="orderBranch"
        :orderAddress="orderAddress" />
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";
import OrderDetailsComponent from "../../components/OrderDetailsComponent";

export default {
    name: "DeliveredOrderShowComponent",
    components: {
        LoadingComponent,
        OrderDetailsComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
        }
    },
    mounted() {
        if (this.$route.params.id) {
            this.loading.isActive = true;
            this.$store.dispatch("deliveryBoyOrder/deliveredOrderDetails", {
                id: this.$route.params.id,
                orderId: this.$route.params.orderId
            }).then((res) => {
                this.loading.isActive = false;
            }).catch((error) => {
                this.loading.isActive = false;

            });
        }
    },
    computed: {
        order: function () {
            return this.$store.getters['deliveryBoyOrder/deliveredOrderDetails'];
        },
        orderItems: function () {
            return this.$store.getters['deliveryBoyOrder/orderItems'];
        },
        orderBranch: function () {
            return this.$store.getters['deliveryBoyOrder/orderBranch'];
        },
        orderAddress: function () {
            return this.$store.getters['deliveryBoyOrder/orderAddress'];
        },
    },
    methods: {
        orderStatusClass: function (status) {
            return appService.orderStatusClass(status);
        },
    }

}
</script>
