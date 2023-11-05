<template>
    <div v-if="status && setting.cookies_summary" :class="activeClass"
         class="cookie-paper  fixed bottom-0 sm:bottom-16 h-80 overflow-auto sm:h-auto lg:bottom-8 left-0 sm:left-8 z-50 w-full sm:max-w-[300px] sm:rounded-xl p-6 shadow-paper bg-white">
        <h3 class="font-medium leading-6 capitalize mb-3">{{ $t('label.about_our_privacy') }}</h3>
        <p class="text-sm leading-6 mb-8 text-heading">{{ setting.cookies_summary }}</p>
        <div class="flex flex-wrap items-center gap-[18px] mb-[18px]">
            <button @click.prevent="change(true)" type="button"
                    class="h-10 py-2 px-6 rounded-3xl flex items-center gap-1.5 bg-[#1AB759]">
                <i class="lab lab-cookie-bite lab-font-size-16 text-white"></i>
                <span class="leading-6 capitalize text-white">{{ $t('button.accept') }}</span>
            </button>
            <button @click.prevent="change(false)" type="button"
                    class="cookie-cancel h-10 py-2 px-6 rounded-3xl flex items-center gap-1.5 bg-[#A0A3BD]">
                <span class="leading-6 capitalize text-white">{{ $t('button.cancel') }}</span>
            </button>
        </div>

        <router-link v-if="slug !== 'not-found'" class="capitalize text-sm leading-6 underline text-primary"
                     :to="{ name : 'frontend.page', params: {slug: slug} }">
            {{ $t('label.cookies_settings') }}
        </router-link>
    </div>
</template>
<script>
import axios from "axios";

export default {
    name: "FrontendCookiesComponent",
    data() {
        return {
            setting: {},
            status: false,
            activeClass: "",
            slug: "not-found"
        }
    },
    mounted() {
        window.setTimeout(() => {
            axios.get('frontend/cookies').then((res) => {
                if (res.data.data.cookies_notification === null) {
                    this.status = true;
                    this.activeClass = 'active';
                    this.setting = this.$store.getters['frontendSetting/lists'];
                    if(this.setting.cookies_details_page_id > 0) {
                        this.$store.dispatch('frontendPage/pageInfo', this.setting.cookies_details_page_id).then(res => {
                            this.slug = res.data.data.slug;
                        }).catch();
                    }
                }
            }).catch((err) => {
            });
        }, 3000);
    },
    methods: {
        change: function (status) {
            axios.post('frontend/cookies', {cookies_notification: status}).then((res) => {
                this.status = false;
                this.activeClass = '';
            }).catch((err) => {
            });
        }
    }
}
</script>
