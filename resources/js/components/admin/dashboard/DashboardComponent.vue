<template>
    <LoadingComponent :props="loading"/>
    <div v-if="demo === 'true' || demo === 'TRUE' || demo === 'True' || demo === '1' || demo === 1" class="mb-4 bg-red-100 p-2 pl-4  rounded">
        <h2 class="mb-1">{{ $t('label.reminder') }}</h2>
        <p>{{ $t('label.data_reset') }}</p>
    </div>

    <div class="mb-8">
        <h3 class="font-semibold text-[26px] leading-10 capitalize text-primary">{{ visitorMessage() }}</h3>
        <h4 class="font-medium text-[22px] leading-[34px] capitalize">{{ authInfo.name }}</h4>
    </div>
    <!--========OVERVIEW START=============-->
    <OverviewComponent/>
    <!--========OVERVIEW END=============-->

    <!--========ORDER STATISTIC START=============-->
    <OrderStatisticsComponent/>
    <!--========ORDER STATISTIC END=============-->
    <div class="row">
        <!--========SALES SUMMARY START=============-->
        <SalesSummaryComponent/>
        <!--========SALES SUMMARY END=============-->

        <!--========ORDERS SUMMARY START=============-->
        <OrderSummaryComponent/>
        <!--========ORDERS SUMMARY END=============-->

        <!--========CUSTOMER STATS START=============-->
        <CustomerStatsComponent/>
        <!--========CUSTOMER STATS END=============-->

        <!--========TOP CUSTOMERS START=============-->
        <TopCustomersComponent/>
        <!--========TOP CUSTOMERS END=============-->

        <!--========FEATURED ITEMS START=============-->
        <FeaturedItemsComponent/>
        <!--========FEATURED ITEMS END=============-->

        <!--========MOST POPULAR ITEMS START=============-->
        <MostPopularItemsComponent/>
        <!--========MOST POPULAR ITEMS END=============-->
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import OverviewComponent from "./OverviewComponent";
import OrderStatisticsComponent from "./OrderStatisticsComponent";
import TopCustomersComponent from "./TopCustomersComponent";
import FeaturedItemsComponent from "./FeaturedItemsComponent";
import MostPopularItemsComponent from "./MostPopularItemsComponent";
import SalesSummaryComponent from "./SalesSummaryComponent";
import OrderSummaryComponent from "./OrderSummaryComponent";
import CustomerStatsComponent from "./CustomerStatsComponent";
import ENV from "../../../config/env";

export default {
    name: "DashboardComponent",
    components: {
        LoadingComponent,
        OverviewComponent,
        OrderStatisticsComponent,
        TopCustomersComponent,
        FeaturedItemsComponent,
        MostPopularItemsComponent,
        SalesSummaryComponent,
        OrderSummaryComponent,
        CustomerStatsComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            demo : ENV.DEMO
        };
    },
    computed: {
        authInfo: function () {
            return this.$store.getters.authInfo;
        }
    },
    methods: {
        visitorMessage: function () {
            let greet;
            let myDate = new Date();
            let hrs = myDate.getHours();
            if (hrs < 12) {
                greet = this.$t('message.good_morning');
            } else if (hrs >= 12 && hrs <= 17) {
                greet = this.$t('message.good_afternoon');
            } else if (hrs >= 17 && hrs <= 24) {
                greet = this.$t('message.good_evening');
            }
            return greet;
        }
    }
}
</script>
