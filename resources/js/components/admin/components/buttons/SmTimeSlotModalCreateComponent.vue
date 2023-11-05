<template>
  <button type="button" @click="add" data-modal="#modal"
    class="flex items-center gap-1.5 time-slot-gap h-7 px-3 rounded-2xl bg-[#FFEDF4] text-primary">
    <i class="lab lab-plus"></i>
    <span class="text-xs font-semibold capitalize text-primary">{{ $t("button.add") }}</span>
  </button>
</template>

<script>
import appService from "../../../../services/appService";

export default {
  name: "SmTimeSlotModalCreateComponent",
  props: ["props"],
  methods: {
    add: function () {
      appService.modalShow();
    },
  },
};
</script>
