<template>
  <section class="mb-16 mt-12">
    <div class="container">
      <h2 class="capitalize text-[26px] leading-[40px] font-semibold mb-6 text-primary">
        {{ $t("label.all_offers") }}
      </h2>
      <OfferComponent :limit="limit" />
    </div>
  </section>
</template>

<script>
import OfferComponent from "../components/OfferComponent";

export default {
    name: "OffersComponent",
    components: {
        OfferComponent,
    },
    data() {
        return {
            limit: null,
        };
    },
};
</script>
