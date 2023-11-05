<template>
    <LoadingComponent :props="loading" />
    <div class="col-12 xl:col-6">
        <div class="db-card">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ $t('label.top_customers') }}</h3>
            </div>
            <div class="db-card-body">
                <ul class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <li class="w-full rounded-xl pt-3 border border-[#D9DBE9]" v-if="top_customers.length > 0"
                        v-for="top_customer in top_customers" :key="top_customer">
                        <img class="w-12 mx-auto rounded-full mb-2" :src="top_customer.image" alt="avatar">
                        <h4
                            class="text-sm px-3 text-center font-medium capitalize mb-4 whitespace-nowrap overflow-hidden text-ellipsis">
                            {{ top_customer.name }}</h4>
                        <p
                            class="text-xs w-full tracking-wide text-center py-1 rounded-t rounded-b-[11px] text-white bg-[#008BBA]">
                            {{ top_customer.order }} {{ $t('label.orders') }}</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
export default {
    name: "TopCustomersComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },

            top_customers: {},
        };
    },
    mounted() {
        this.topCustomers();
    },
    methods: {
        topCustomers: function () {
            this.loading.isActive = true;
            this.$store.dispatch('dashboard/topCustomers').then(res => {
                this.top_customers = res.data.data;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
    },
}
</script>
