<template>
    <LoadingComponent :props="loading" />
    <section class="mb-12">
        <div class="container" v-if="featuredItems.length > 0">
            <h2 class="text-2xl font-semibold capitalize mb-6">{{ $t('label.featured_items') }}</h2>
            <ItemComponent :items="featuredItems" :type="itemProps.type" :design="itemProps.design" />
        </div>
    </section>
</template>
<script>

import alertService from "../../../services/alertService";
import itemDesignEnum from "../../../enums/modules/itemDesignEnum";
import ItemComponent from "../components/ItemComponent";
import LoadingComponent from "../components/LoadingComponent";

export default {
    name: "FeaturedItemComponent",
    components: {
        ItemComponent,
        LoadingComponent
    },
    props: {
        items: Object,
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            itemProps: {
                design: itemDesignEnum.GRID,
                type: null,
            },
        };
    },
    mounted() {
        try {
            this.loading.isActive = true;
            this.$store.dispatch("frontendItem/featured", {
                order_column: "id",
                order_type: "desc"
            }).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        } catch (err) {
            this.loading.isActive = false;
        }
    },
    computed: {
        featuredItems: function () {
            return this.$store.getters["frontendItem/featured"];
        },
    },
    methods: {},
};
</script>
