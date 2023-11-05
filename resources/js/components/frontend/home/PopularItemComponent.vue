<template>
    <LoadingComponent :props="loading" />
    <section class="mb-16">
        <div class="container" v-if="popularItems.length > 0">
            <div class="flex items-center justify-between gap-2 mb-6">
                <h2 class="text-2xl font-semibold capitalize">{{ $t('label.most_popular_items') }}</h2>
            </div>
            <ItemComponent :items="popularItems" :type="itemProps.type" :design="itemProps.design" />
        </div>
    </section>
</template>
<script>

import alertService from "../../../services/alertService";
import itemDesignEnum from "../../../enums/modules/itemDesignEnum";
import ItemComponent from "../components/ItemComponent";
import LoadingComponent from "../components/LoadingComponent";

export default {
    name: "PopularItemComponent",
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
                isActive: false
            },
            itemProps: {
                design: itemDesignEnum.LIST,
                type: null,
            },
        };
    },
    mounted() {
        try {
            this.loading.isActive = true;
            this.$store.dispatch("frontendItem/popular", {
                order_column: "id",
                order_type: "desc",
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
        popularItems: function () {
            return this.$store.getters["frontendItem/popular"];
        }
    }
};
</script>
