<template>
    <section class="pt-16 pb-12 flex flex-col items-center justify-center text-center">
        <img class="mb-8 w-full max-w-[220px]" :src="setting.image_four_zero_three_page" alt="gif">
        <h3 class="capitalize text-[26px] font-medium leading-[40px] mb-2">{{ $t('message.403_page') }}</h3>
        <p class="text-lg font-normal leading-[34px] mb-8">{{ $t('message.not_authorize') }}</p>
        <router-link v-if="Object.keys(authDefaultPermission).length > 0" :to="type+'/'+authDefaultPermission.url" class="w-full max-w-[250px] py-3 rounded-3xl capitalize text-base font-medium leading-6 text-center bg-primary text-white">
            {{ $t('menu.'+authDefaultPermission.name) }}
        </router-link>
    </section>
</template>
<script>
export default {
    name: "ExceptionComponent",
    data() {
        return {
            type : "admin"
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        authDefaultPermission: function () {
            return this.$store.getters.authDefaultPermission;
        }
    }
}
</script>
