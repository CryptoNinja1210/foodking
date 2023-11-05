<template>
    <div class="db-breadcrumb">
        <ul class="db-breadcrumb-list">
            <li v-if="Object.keys(authDefaultPermission).length > 0" class="db-breadcrumb-item">
                <router-link class="db-breadcrumb-link" :to="'/admin/'+authDefaultPermission.url">
                    {{ $t('menu.'+authDefaultPermission.name) }}
                </router-link>
            </li>
            <li class="db-breadcrumb-item" v-for="(val, key) of breadcrumbs">
                <span v-if="key !== Object.keys(breadcrumbs).length - 1">
                    <router-link class="db-breadcrumb-link" :to="val.path">
                        {{ $t('menu.'+val.meta.breadcrumb) }}
                    </router-link>
                </span>
                <span v-else>
                    {{ $t('menu.'+val.meta.breadcrumb) }}
                </span>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "BreadcrumbComponent",
    data() {
        return {
            breadcrumbs: []
        }
    },
    computed: {
        authDefaultPermission: function () {
            return this.$store.getters.authDefaultPermission;
        }
    },
    watch: {
        $route() {
            this.route();
        }
    },
    created() {
        this.route();
    },
    methods: {
        route: function () {
            let i, routeArray = [], filterBreadCrumbs = this.$route.matched;
            if (filterBreadCrumbs.length > 0) {
                for (i = 0; i < filterBreadCrumbs.length; i++) {
                    if (filterBreadCrumbs[i].meta.breadcrumb) {
                        routeArray[i] = filterBreadCrumbs[i];
                    }
                }
            }
            this.breadcrumbs = routeArray;
        }
    }
}
</script>

<style scoped>

</style>
