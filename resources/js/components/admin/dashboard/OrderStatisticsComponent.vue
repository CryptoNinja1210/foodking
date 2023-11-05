<template>
    <LoadingComponent :props="loading" />
    <div class="flex items-center justify-between mb-4">
        <h4 class="font-semibold text-[22px] leading-[34px] mb-3 capitalize">{{ $t('menu.order_statistics') }}</h4>
        <div class="relative cursor-pointer">
            <Datepicker hideInputIcon autoApply :enableTimePicker="false" utc="false" @update:modelValue="handleDate"
                v-model="date" range :preset-ranges="presetRanges">
                <template #yearly="{ label, range, presetDateRange }">
                    <span @click="presetDateRange(range)">{{ label }}</span>
                </template>
            </Datepicker>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
            <div class="flex items-center gap-4 p-4 rounded-lg shadow-xs bg-white">
                <div class="w-12 h-12 rounded-full flex items-center justify-center bg-[#FFE6F0]">
                    <i class="lab lab-total-orders lab-font-size-24 text-primary"></i>
                </div>
                <div>
                    <h3 class="font-normal text-sm leading-6 capitalize text-paragraph">
                        {{ $t('label.total_orders') }}
                    </h3>
                    <h4 class="font-bold text-lg leading-[34px]">{{ total_order }}</h4>
                </div>
            </div>
        </div>
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
            <div class="flex items-center gap-4 p-4 rounded-lg shadow-xs bg-white">
                <div class="w-12 h-12 rounded-full flex items-center justify-center bg-[#FFF6E6]">
                    <i class="lab lab-pending lab-font-size-24 lab-color-yellow"></i>
                </div>
                <div>
                    <h3 class="font-normal text-sm leading-6 capitalize text-paragraph">{{ $t('label.pending') }}</h3>
                    <h4 class="font-bold text-lg leading-[34px]">{{ pending_order }}</h4>
                </div>
            </div>
        </div>
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
            <div class="flex items-center gap-4 p-4 rounded-lg shadow-xs bg-white">
                <div class="w-12 h-12 rounded-full flex items-center justify-center bg-[#E7FFF0]">
                    <i class="lab lab-processing lab-font-size-24 lab-color-green"></i>
                </div>
                <div>
                    <h3 class="font-normal text-sm leading-6 capitalize text-paragraph">{{ $t('label.processing') }}
                    </h3>
                    <h4 class="font-bold text-lg leading-[34px]">{{ processing_order }}</h4>
                </div>
            </div>
        </div>
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
            <div class="flex items-center gap-4 p-4 rounded-lg shadow-xs bg-white">
                <div class="w-12 h-12 rounded-full flex items-center justify-center bg-[#E9F9FF]">
                    <i class="lab lab-out-for-delivery lab-font-size-24 lab-color-blue"></i>
                </div>
                <div>
                    <h3 class="font-normal text-sm leading-6 capitalize text-paragraph">
                        {{ $t('label.out_for_delivery') }}
                    </h3>
                    <h4 class="font-bold text-lg leading-[34px]">{{ out_for_delivery_order }}</h4>
                </div>
            </div>
        </div>
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
            <div class="flex items-center gap-4 p-4 rounded-lg shadow-xs bg-white">
                <div class="w-12 h-12 rounded-full flex items-center justify-center bg-[#EBE7FF]">
                    <i class="lab lab-delivered lab-font-size-24 lab-color-delivered"></i>
                </div>
                <div>
                    <h3 class="font-normal text-sm leading-6 capitalize text-paragraph">{{ $t('label.delivered') }}</h3>
                    <h4 class="font-bold text-lg leading-[34px]">{{ delivered_order }}</h4>
                </div>
            </div>
        </div>
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
            <div class="flex items-center gap-4 p-4 rounded-lg shadow-xs bg-white">
                <div class="w-12 h-12 rounded-full flex items-center justify-center bg-[#FFEAEA]">
                    <i class="lab lab-cancel-n-reject lab-font-size-24 lab-color-red"></i>
                </div>
                <div>
                    <h3 class="font-normal text-sm leading-6 capitalize text-paragraph">{{ $t('label.canceled') }}</h3>
                    <h4 class="font-bold text-lg leading-[34px]">{{ canceled_order }}</h4>
                </div>
            </div>
        </div>
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
            <div class="flex items-center gap-4 p-4 rounded-lg shadow-xs bg-white">
                <div class="w-12 h-12 rounded-full flex items-center justify-center bg-[#E9EEFF]">
                    <i class="lab lab-returned lab-font-size-24 lab-color-blue-2"></i>
                </div>
                <div>
                    <h3 class="font-normal text-sm leading-6 capitalize text-paragraph">{{ $t('label.returned') }}</h3>
                    <h4 class="font-bold text-lg leading-[34px]">{{ returned_order }}</h4>
                </div>
            </div>
        </div>
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
            <div class="flex items-center gap-4 p-4 rounded-lg shadow-xs bg-white">
                <div class="w-12 h-12 rounded-full flex items-center justify-center bg-[#FFEAEA]">
                    <i class="lab lab-cancel-n-reject lab-font-size-24 lab-color-red"></i>
                </div>
                <div>
                    <h3 class="font-normal text-sm leading-6 capitalize text-paragraph">{{ $t('label.rejected') }}</h3>
                    <h4 class="font-bold text-lg leading-[34px]">{{ rejected_order }}</h4>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { ref } from 'vue';
import { endOfMonth, endOfYear, startOfMonth, startOfYear, subMonths } from 'date-fns';
export default {
    name: "OrderStatisticsComponent",
    components: { LoadingComponent, Datepicker },
    data() {
        return {
            loading: {
                isActive: false,
            },

            date: null,
            first_date: null,
            last_date: null,
            total_order: null,
            pending_order: null,
            processing_order: null,
            out_for_delivery_order: null,
            delivered_order: null,
            canceled_order: null,
            returned_order: null,
            rejected_order: null,
            presetRanges: [
                { label: 'Today', range: [new Date(), new Date()] },
                { label: 'This month', range: [startOfMonth(new Date()), endOfMonth(new Date())] },
                {
                    label: 'Last month',
                    range: [startOfMonth(subMonths(new Date(), 1)), endOfMonth(subMonths(new Date(), 1))],
                },
                { label: 'This year', range: [startOfYear(new Date()), endOfYear(new Date())] },
                {
                    label: 'This year (slot)',
                    range: [startOfYear(new Date()), endOfYear(new Date())],
                    slot: 'yearly',
                },
            ]
        };
    },
    mounted() {
        const startDate = new Date();
        const endDate = new Date();
        this.date = [startDate, endDate];
        this.orderStatistic();
    },
    methods: {
        handleDate: function (e) {
            if (e) {
                this.first_date = e[0];
                this.last_date = e[1];

                this.loading.isActive = true;
                this.$store.dispatch("dashboard/orderStatistics", {
                    first_date: this.first_date,
                    last_date: this.last_date,
                }).then((res) => {
                    this.total_order = res.data.data.total_order;
                    this.pending_order = res.data.data.pending_order;
                    this.processing_order = res.data.data.processing_order;
                    this.out_for_delivery_order = res.data.data.out_for_delivery_order;
                    this.delivered_order = res.data.data.delivered_order;
                    this.canceled_order = res.data.data.canceled_order;
                    this.returned_order = res.data.data.returned_order;
                    this.rejected_order = res.data.data.rejected_order;
                    this.loading.isActive = false;
                }).catch((err) => {
                    this.loading.isActive = false;
                });
            } else {
                this.date = null;
                this.first_date = null;
                this.last_date = null;
                this.orderStatistic();
            }
        },
        orderStatistic: function () {
            this.loading.isActive = true;
            this.$store.dispatch("dashboard/orderStatistics").then((res) => {
                this.total_order = res.data.data.total_order;
                this.pending_order = res.data.data.pending_order;
                this.processing_order = res.data.data.processing_order;
                this.out_for_delivery_order = res.data.data.out_for_delivery_order;
                this.delivered_order = res.data.data.delivered_order;
                this.canceled_order = res.data.data.canceled_order;
                this.returned_order = res.data.data.returned_order;
                this.rejected_order = res.data.data.rejected_order;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        }
    }
}
</script>
