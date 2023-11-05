<template>
    <div v-if="logged" id="user-profile-dropdown-box"
        class="profile-paper fixed top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 z-[60] overflow-auto w-screen h-screen sm:w-full sm:h-auto sm:max-w-sm p-4 sm:rounded-xl shadow-paper bg-white">
        <div class="w-fit mx-auto text-center mb-5">
            <figure
                class="relative z-10 w-[98px] h-[98px] border-2 border-dashed rounded-full inline-flex items-center justify-center border-white bg-gradient-to-t from-[#FF7A00] to-[#FF016C]
                                                        before:absolute before:top-1/2 before:left-1/2 before:-translate-x-1/2 before:-translate-y-1/2 before:w-24 before:h-24 before:rounded-full before:-z-10 before:bg-white">
                <img class="w-[90px] h-[90px] rounded-full shadow-avatar" :src="profile.image" alt="avatar">
            </figure>
            <label for="imageProperty"
                class="block w-11 h-11 mx-auto -mt-7 mb-3 relative z-10 rounded-full border-2 cursor-pointer bg-heading border-white">
                <input @change="saveImage" id="imageProperty" ref="imageProperty" type="file"
                    accept="image/png, image/jpeg, image/jpg" class="w-full h-full rounded-full opacity-0 cursor-pointer">
                <i
                    class="lab lab-edit-2 absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 -z-10 lab-font-size-24 lab-font-color-1"></i>
            </label>
            <h3 class="font-medium text-sm leading-6 capitalize mb-0.5">{{ textShortener(profile.name, 20) }}</h3>
            <p class="text-xs mb-0.5">{{ profile.email }}</p>
            <p class="text-xs">{{ profile.phone }}</p>
            <h3 class="font-medium text-sm leading-6 capitalize mb-0.5">{{ profile.currency_balance }}</h3>
            <button class="fa-solid fa-xmark absolute top-4 right-4 text-white bg-[#FB4E4E] xmark-btn"></button>
        </div>

        <nav>
            <router-link :to="{ name: 'admin.dashboard' }" v-on:click="linkClick"
                class="paper-link transition w-full flex items-center gap-3.5 py-3 border-b last:border-none border-[#EFF0F6]">
                <i class="lab lab-dashboard lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{ $t('menu.dashboard') }}</span>
            </router-link>

            <router-link :to="{ name: 'frontend.myOrder' }" v-on:click="linkClick"
                class="paper-link transition w-full flex items-center gap-3.5 py-3 border-b last:border-none border-[#EFF0F6]">
                <i class="lab lab-reserve-line lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{ $t('button.my_orders') }}</span>
            </router-link>

            <router-link :to="{ name: 'frontend.editProfile' }" v-on:click="linkClick"
                class="paper-link transition w-full flex items-center gap-3.5 py-3 border-b last:border-none border-[#EFF0F6]">
                <i class="lab lab-edit lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{ $t('button.edit_profile') }}</span>
            </router-link>

            <router-link :to="{ name: 'frontend.chat' }" v-on:click="linkClick"
                class="paper-link transition w-full flex items-center gap-3.5 py-3 border-b last:border-none border-[#EFF0F6]">
                <i class="lab lab-messages-line lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{ $t('button.chat') }}</span>
            </router-link>

            <router-link :to="{ name: 'frontend.address' }" v-on:click="linkClick"
                class="paper-link transition w-full flex items-center gap-3.5 py-3 border-b last:border-none border-[#EFF0F6]">
                <i class="lab lab-map lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{ $t('button.address') }}</span>
            </router-link>

            <router-link :to="{ name: 'frontend.changePassword' }" v-on:click="linkClick"
                class="paper-link transition w-full flex items-center gap-3.5 py-3 border-b last:border-none border-[#EFF0F6]">
                <i class="lab lab-key lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{ $t('button.change_password') }}</span>
            </router-link>
            <button @click="logout()"
                class="paper-link transition w-full flex items-center gap-3.5 py-3 border-b last:border-none border-[#EFF0F6]">
                <i class="lab lab-logout lab-font-size-17"></i>
                <span class="text-sm leading-6 capitalize">{{ $t('button.logout') }}</span>
            </button>
        </nav>
    </div>
</template>
<script>

import alertService from "../../../services/alertService";
import LoadingComponent from "../../frontend/components/LoadingComponent";
import appService from "../../../services/appService";
export default {
    name: "FrontendMobileAccountComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
        }
    },
    computed: {
        logged: function () {
            return this.$store.getters.authStatus;
        },
        profile: function () {
            return this.$store.getters.authInfo;
        },
    },
    methods: {
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        linkClick: function () {
            const profile = document.getElementById('user-profile-dropdown-box');
            document.body.style.overflowY = "auto";
            profile?.classList?.remove('active');
        },
        logout: function () {
            this.$store.dispatch("logout").then(res => {
                this.$router.push({ name: "frontend.home" });
            }).catch();
        },
        saveImage: function () {
            if (this.$refs.imageProperty.files[0]) {
                try {
                    this.loading.isActive = true;
                    const formData = new FormData();
                    formData.append("image", this.$refs.imageProperty.files[0]);
                    this.$store.dispatch("frontendEditProfile/changeImage", { form: formData }).then((res) => {
                        this.$store.dispatch('updateAuthInfo', res.data.data).then(res => {
                            this.loading.isActive = false;
                            alertService.success(this.$t("message.photo_update"));
                            this.$refs.imageProperty.value = null;
                        }).catch((err) => {
                            this.loading.isActive = false;
                            alertService.error(err);
                        });
                    }).catch((err) => {
                        this.loading.isActive = false;
                        this.imageErrors = err.response.data.errors;
                    });
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }
        },
    }
}
</script>
