<template>
    <LoadingComponent :props="loading" />
    <section class="mb-16 mt-8">
        <div class="container">
            <div class="flex gap-4 flex-col sm:flex-row items-center justify-between mb-6">
                <h2 class="capitalize text-[26px] leading-[40px] font-semibold text-center sm:text-left text-primary">
                    {{ props.search.name }}
                </h2>
                <div class="flex items-center gap-3" v-if="props.search.name">
                    <button type="button" class="lab lab-row-vertical lab-font-size-20 text-xl"
                        v-on:click="itemProps.design = itemDesignEnum.LIST"
                        :class="itemProps.design === itemDesignEnum.LIST ? 'text-heading' : 'text-[#A0A3BD]'"></button>
                    <button type="button" class="lab lab-element-3 lab-font-size-20 text-xl"
                        v-on:click="itemProps.design = itemDesignEnum.GRID"
                        :class="itemProps.design === itemDesignEnum.GRID ? 'text-heading' : 'text-[#A0A3BD]'"></button>
                </div>
            </div>
            <ItemComponent :items="items" :type="itemProps.type" :design="itemProps.design" />
        </div>
    </section>
</template>

<script>
import ItemComponent from "../components/ItemComponent";
import itemDesignEnum from "../../../enums/modules/itemDesignEnum";
import statusEnum from "../../../enums/modules/statusEnum";
import alertService from "../../../services/alertService";
import LoadingComponent from "../components/LoadingComponent";

export default {
    name: "SearchItemComponent",
    components: {
        ItemComponent, LoadingComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            itemDesignEnum: itemDesignEnum,
            items: {},
            itemProps: {
                design: itemDesignEnum.LIST,
                type: null,
            },
            props: {
                search: {
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'asc',
                    name: "",
                    status: statusEnum.ACTIVE,
                }
            },
        };
    },
    mounted() {
        if (typeof this.$route.query.s !== "undefined" && this.$route.query.s !== "") {
            this.props.search.name = this.$route.query.s;
            this.loading.isActive = true;
            this.$store.dispatch("frontendItem/lists", this.props.search).then((res) => {
                this.items = res.data.data;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        }
    },
    methods: {
        itemTypeSet: function (e) {
            this.itemProps.type = e;
        },
        itemTypeReset: function () {
            this.itemProps.type = null;
        },
        searItems: function () {
            if (typeof this.$route.query.s !== "undefined" && this.$route.query.s !== "") {
                this.props.search.name = this.$route.query.s;
                this.loading.isActive = true;
                this.$store.dispatch("frontendItem/lists", this.props.search).then((res) => {
                    this.items = res.data.data;
                    this.loading.isActive = false;
                }).catch((err) => {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                });
            }
        }

    },
    watch: {
        $route() {
            this.searItems();
        }
    }
};
</script>
