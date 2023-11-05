<template>
  <section class="mb-12" v-if="offers.length > 0">
    <div class="container">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
        <router-link :to="{ name: 'frontend.offers.item', params: { slug: offer.slug } }" v-for="offer in offers"
          :key="offer">
          <img class="w-full rounded-2xl" :src="offer.image" alt="banner" />
        </router-link>
      </div>
    </div>
  </section>
</template>
<script>
import statusEnum from "../../../enums/modules/statusEnum";

export default {
  name: "OfferComponent",
  components: {},
  props: {
    limit: Number,
  },
  data() {
    return {
      loading: {
        isActive: false,
      },
    };
  },
  mounted() {
    try {
      this.loading.isActive = true;
      this.$store.dispatch("frontendOffer/lists", {
        order_column: "id",
        order_type: "desc",
        limit: this.limit,
        status: statusEnum.ACTIVE,
      });
    } catch (err) {
      this.loading.isActive = false;
    }
  },
  computed: {
    offers: function () {
      return this.$store.getters["frontendOffer/lists"];
    },
  },
  methods: {},
};
</script>
