<template>
    <LoadingComponent :props="loading" />
    <div class="mb-9">
        <h4 class="font-semibold text-[22px] leading-[34px] mb-3 capitalize">{{ $t("menu.overview") }}</h4>
        <div class="row">
            <div class="col-12 sm:col-6 xl:col-3">
                <div class="p-4 rounded-lg flex items-center gap-4 bg-[#FF4F99]">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-white">
                        <i class="lab lab-total-sale lab-font-size-24 lab-color-pink"></i>
                    </div>
                    <div>
                        <h3 class="font-medium text-white">{{ $t('label.total_sales') }}</h3>
                        <h4 class="font-semibold text-[22px] leading-[34px] text-white">${{ total_sales }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 sm:col-6 xl:col-3">
                <div class="p-4 rounded-lg flex items-center gap-4 bg-[#8262FE]">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-white">
                        <i class="lab lab-total-orders lab-font-size-24 lab-color-portage"></i>
                    </div>
                    <div>
                        <h3 class="font-medium text-white">{{ $t('label.total_orders') }}</h3>
                        <h4 class="font-semibold text-[22px] leading-[34px] text-white">{{ total_orders }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 sm:col-6 xl:col-3">
                <div class="p-4 rounded-lg flex items-center gap-4 bg-[#567DFF]">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-white">
                        <i class="lab lab-total-customers lab-font-size-24 lab-color-cornflower-blue"></i>
                    </div>
                    <div>
                        <h3 class="font-medium text-white">{{ $t('label.total_customers') }}</h3>
                        <h4 class="font-semibold text-[22px] leading-[34px] text-white">{{ total_customers }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 sm:col-6 xl:col-3">
                <div class="p-4 rounded-lg flex items-center gap-4 bg-[#A953FF]">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-white">
                        <i class="lab lab-total-menu-items lab-font-size-24 lab-color-heliotrope"></i>
                    </div>
                    <div>
                        <h3 class="font-medium text-white">{{ $t('label.total_menu_items') }}</h3>
                        <h4 class="font-semibold text-[22px] leading-[34px] text-white">{{ total_menu_items }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
export default {
    name: "OverviewComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },

            total_sales: null,
            total_orders: null,
            total_customers: null,
            total_menu_items: null,
        };
    },
    mounted() {
        this.totalSales();
        this.totalOrders();
        this.totalCustomers();
        this.totalMenuItems();
    },
    methods: {
        totalSales: function () {
            this.loading.isActive = true;
            this.$store.dispatch("dashboard/totalSales").then((res) => {
                this.total_sales = res.data.data.total_sales;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },

        totalOrders: function () {
            this.loading.isActive = true;
            this.$store.dispatch("dashboard/totalOrders").then((res) => {
                this.total_orders = res.data.data.total_orders;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        totalCustomers: function () {
            this.loading.isActive = true;
            this.$store.dispatch("dashboard/totalCustomers").then((res) => {
                this.total_customers = res.data.data.total_customers;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        totalMenuItems: function () {
            this.loading.isActive = true;
            this.$store.dispatch("dashboard/totalMenuItems").then((res) => {
                this.total_menu_items = res.data.data.total_menu_items;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
    },
}
</script>
