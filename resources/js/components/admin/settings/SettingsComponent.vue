<template>
    <div class="row">
        <div class="col-12">
            <BreadcrumbComponent />
        </div>

        <div class="col-12 md:col-4 xl:col-3">
            <MenuComponent />
        </div>

        <div class="col-12 md:col-8 xl:col-9">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import MenuComponent from "./MenuComponent";
import BreadcrumbComponent from "../components/BreadcrumbComponent";

export default {
    name: "SettingsComponent",
    components: {MenuComponent, BreadcrumbComponent},

}
</script>
