<template>
    <button data-drawer="#sidebar" class="db-btn h-[37px] text-white bg-primary" @click="addButton">
        <i class="lab lab-add-circle-line"></i>
        <span>{{ props.title }}</span>
    </button>
</template>

<script>

import appService from "../../../../services/appService";

export default {
    name: "SmSidebarModalCreateComponent",
    props: ['props'],
    methods: {
        addButton: function () {
            appService.sideDrawerShow();
        }
    }
}
</script>
