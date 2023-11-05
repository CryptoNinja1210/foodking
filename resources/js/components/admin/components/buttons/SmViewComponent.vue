<template>
    <router-link class="db-btn-outline sm primary modal-btn m-0.5"
        :to="{ name: this.$props.link, params: { id: this.$props.id } }">
        <i class="lab lab-view"></i> <span>{{ $t("button.view") }}</span>
    </router-link>
</template>

<script>
export default {
    name: "SmViewComponent",
    props: {
        link: String,
        id: Number
    }
}
</script>

<style scoped>

</style>
