<template>
    <div class="row">
        <div class="col-12">
            <BreadcrumbComponent />
        </div>
        <router-view></router-view>
    </div>
</template>

<script>
import BreadcrumbComponent from "../components/BreadcrumbComponent";

export default {
    name: "OnlineOrderComponent",
    components: {
        BreadcrumbComponent
    }
}
</script>

<style scoped>

</style>
