<template>
    <div class="col-12">
        <BreadcrumbComponent />
    </div>
    <router-view></router-view>
</template>

<script>
    import BreadcrumbComponent from "../components/BreadcrumbComponent";

    export default {
        name: "PushNotificationComponent",
        components: {
            BreadcrumbComponent,
        },
    }

</script>

<style scoped>

</style>
