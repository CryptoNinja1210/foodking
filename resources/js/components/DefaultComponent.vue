<template>
    <div v-if="theme === 'frontend'">
        <FrontendNavbarComponent/>
        <FrontendCartComponent/>
        <router-view></router-view>
        <FrontendMobileNavBarComponent/>
        <FrontendMobileAccountComponent/>
        <FrontendCookiesComponent/>
        <FrontendFooterComponent/>
    </div>

    <div v-if="theme === 'backend'">
        <main class="db-main" v-if="logged">
            <BackendNavbarComponent/>
            <BackendMenuComponent/>
            <router-view></router-view>
        </main>

        <div v-if="!logged">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import BackendNavbarComponent from "./layouts/backend/BackendNavbarComponent";
import BackendMenuComponent from "./layouts/backend/BackendMenuComponent";
import FrontendNavbarComponent from "./layouts/frontend/FrontendNavBarComponent";
import FrontendFooterComponent from "./layouts/frontend/FrontendFooterComponent";
import FrontendMobileNavBarComponent from "./layouts/frontend/FrontendMobileNavBarComponent";
import FrontendMobileAccountComponent from "./layouts/frontend/FrontendMobileAccountComponent";
import FrontendCartComponent from "./layouts/frontend/FrontendCartComponent";
import FrontendCookiesComponent from "./layouts/frontend/FrontendCookiesComponent";

export default {
    name: "DefaultComponent",
    components: {
        FrontendCartComponent,
        FrontendMobileAccountComponent,
        FrontendMobileNavBarComponent,
        FrontendCookiesComponent,
        FrontendFooterComponent, FrontendNavbarComponent, BackendNavbarComponent, BackendMenuComponent
    },
    data() {
        return {
            theme: "frontend",
        }
    },
    computed: {
        logged: function () {
            return this.$store.getters.authStatus;
        }
    },
    beforeMount() {
        this.$store.dispatch('frontendSetting/lists').then(res => {
            this.$store.dispatch("globalState/init", {
                branch_id: res.data.data.site_default_branch,
                language_id: res.data.data.site_default_language
            });
        }).catch();
    },
    watch: {
        $route(e) {
            if (e.meta.isFrontend === true) {
                this.theme = "frontend";
            } else {
                this.theme = "backend";
            }
        }
    },
}
</script>
