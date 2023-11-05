<template>
    <router-link class="db-table-action view" :to="{ name: this.$props.link, params: { id: this.$props.id } }">
        <i class="lab lab-view"></i>
        <span class="db-tooltip">{{ $t('button.view') }}</span>
    </router-link>
</template>

<script>
export default {
    name: "SmIconViewComponent",
    props: {
        link: String,
        id: Number
    }
}
</script>
