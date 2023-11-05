<template>
    <div class="row mb-7">
        <div class="col-12 sm:col-6 mb-2" v-if="branches.length > 0" v-for="branch in branches">
            <div class="flex items-center gap-2 mb-3">
                <span class="w-6 h-6 rounded-full flex items-center justify-center bg-[#BDEFFF]">
                    <i class="lab lab-shop lab-font-size-12 lab-color-blue"></i>
                </span>
                <h3 class="font-medium leading-6">{{ branch.name }}</h3>
            </div>
            <ul class="flex flex-col gap-2">
                <li class="flex items-center gap-2.5">
                    <i class="lab lab-location-line lab-font-size-16"></i>
                    <span class="text-sm leading-6 text-heading">{{ branch.address }}</span>
                </li>
                <li class="flex items-center gap-2.5">
                    <i class="lab lab-call-calling-linear lab-font-size-16"></i>
                    <span class="text-sm leading-6 text-heading">{{ branch.phone }}</span>
                </li>
            </ul>
        </div>
    </div>
    <div>
        <h2 class="text-[22px] leading-[34px] font-medium capitalize mb-3">support</h2>
        <ul class="flex flex-col gap-2">
            <li class="flex items-center gap-2.5">
                <i class="lab lab-sms-tracking lab-font-size-16"></i>
                <span class="text-sm leading-6 text-heading">{{ setting.company_email }}</span>
            </li>
            <li class="flex items-center gap-2.5">
                <i class="lab lab-call-center lab-font-size-16"></i>
                <span class="text-sm font-medium leading-6 text-heading">{{ setting.company_phone }}</span>
            </li>
        </ul>
    </div>
</template>

<script>


export default {
    name: "ContactUsComponent",
    computed: {
        branches: function () {
            return this.$store.getters['frontendBranch/lists'];
        },
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
    },
    mounted() {
    },
    methods: {
    },
}
</script>
