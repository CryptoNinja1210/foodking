<template>
  <LoadingComponent :props="loading" />
  <div class="col-12 xl:col-6">
    <div class="db-card">
      <div class="db-card-header">
        <h3 class="db-card-title">{{ $t('label.orders_summary') }}</h3>
        <div id="order-range" class="cursor-pointer flex items-center gap-3">
          <Datepicker hideInputIcon autoApply :enableTimePicker="false" utc="false" @update:modelValue="orderSummary"
            v-model="date" range :preset-ranges="presetRanges">
            <template #yearly="{ label, range, presetDateRange }">
              <span @click="presetDateRange(range)">{{ label }}</span>
            </template>
          </Datepicker>
          <i class="lab lab-calendar lab-font-size-24 lab-color-pink"></i>
        </div>
      </div>
      <div class="db-card-body">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
          <div id="radial-chart"></div>
          <ul class="flex flex-col gap-8 w-full sm:w-36">
            <li class="w-full">
              <span class="block capitalize mb-1 text-heading">
                {{ $t("label.delivered") }} ({{ delivered }}%)
              </span>
              <span class="block w-full h-2 rounded bg-[#FF4F99]"></span>
            </li>
            <li class="w-full">
              <span class="block capitalize mb-1 text-heading">
                {{ $t("label.returned") }} ({{ returned }}%)
              </span>
              <span class="block w-full h-2 rounded bg-[#567DFF]"></span>
            </li>
            <li class="w-full">
              <span class="block capitalize mb-1 text-heading">
                {{ $t("label.canceled") }} ({{ canceled }}%)
              </span>
              <span class="block w-full h-2 rounded bg-[#A953FF]"></span>
            </li>
            <li class="w-full">
              <span class="block capitalize mb-1 text-heading">
                {{ $t("label.rejected") }} ({{ rejected }}%)
              </span>
              <span class="block w-full h-2 rounded bg-[#FB4E4E]"></span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { ref, onMounted } from 'vue';
import { endOfMonth, endOfYear, startOfMonth, startOfYear, subMonths } from 'date-fns';

export default {
  name: "OrderSummaryComponent",
  components: { LoadingComponent, Datepicker },
  data() {
    return {
      loading: {
        isActive: false,
      },
      date: null,
      first_date: null,
      last_date: null,
      delivered: null,
      canceled: null,
      returned: null,
      rejected: null,
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
    const date = new Date();
    const startDate = new Date(date.getFullYear(), date.getMonth(), 1);
    const endDate = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    this.date = [startDate, endDate];
    this.orderSummary();
  },
  methods: {
    orderSummary: function (e) {
      let date = {
        first_date: '',
        last_date: '',
      };
      if (e) {
        this.first_date = e[0];
        this.last_date = e[1];
        date.first_date = e[0];
        date.last_date = e[1];
      }
      this.loading.isActive = true;
      this.$store.dispatch("dashboard/orderSummary", date).then((res) => {
        this.delivered = res.data.data.delivered;
        this.returned = res.data.data.returned;
        this.canceled = res.data.data.canceled;
        this.rejected = res.data.data.rejected;

        let options = {
          series: [parseInt(this.delivered), parseInt(this.returned), parseInt(this.canceled), parseInt(this.rejected)],
          chart: {
            height: 320,
            type: 'radialBar',
          },
          plotOptions: {
            radialBar: {
              hollow: { size: '25%' },
              track: { margin: 10 },
              dataLabels: {
                name: {
                  fontSize: '14px',
                  fontFamily: 'inherit',
                },
                value: {
                  fontSize: '14px',
                  fontFamily: 'inherit',
                  fontWeight: 'bold',
                  color: '#1F1F39',
                  offsetY: 5,
                },
                total: {
                  show: true,
                  label: this.$t('label.total'),
                  formatter: function (w) {
                    return w.config.series.reduce((a, b) => a + b, 0);
                  }
                }
              }
            },
          },
          stroke: { lineCap: 'round' },
          colors: ['#FF4F99', '#567DFF', '#A953FF', '#FB4E4E'],
          labels: [this.$t('label.delivered'), this.$t('label.returned'), this.$t('label.canceled'), this.$t('label.rejected')],
        };

        let chart = new ApexCharts(document.querySelector("#radial-chart"), options);
        chart.render();
        if (date.first_date !== '' && date.last_date !== '') {
          chart.updateSeries([parseInt(this.delivered), parseInt(this.returned), parseInt(this.canceled), parseInt(this.rejected)]);
        }
        this.loading.isActive = false;
      }).catch((err) => {
        this.loading.isActive = false;
      });
    },
  },
}
</script>
