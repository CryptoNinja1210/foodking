<template>
    <section class="pt-16 pb-12 flex flex-col items-center justify-center text-center">
        <img class="mb-8 w-full max-w-[220px]" :src="setting.image_four_zero_four_page" alt="404">
        <h3 class="capitalize text-[26px] font-medium leading-[40px] mb-2">{{ $t('message.404_page') }}</h3>
        <p class="text-lg font-normal leading-[34px] mb-8">{{ $t('message.find_looking_for') }}</p>
        <router-link :to="{name : 'frontend.home'}"
                     class="w-full max-w-[250px] py-3 rounded-3xl capitalize text-base font-medium leading-6 text-center bg-primary text-white">
            {{ $t('button.go_home') }}
        </router-link>
    </section>
</template>
<script>
export default {
    name: "NotFoundComponent",
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        }
    }
}
</script>
