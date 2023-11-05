<template>
    <LoadingComponent :props="loading"/>
    <section class="mb-16 mt-8">
        <div class="container">
            <div v-if="categories.length > 0" class="swiper mb-12 menu-swiper">
                <CategoryComponent :categories="categories" :design="categoryProps.design"/>
            </div>

            <ul v-if="categories.length > 0" class="flex flex-wrap gap-3 w-full mb-12 veg-navs">
                <button :disabled="itemProps.type !== null && itemProps.type === itemTypeEnum.VEG"
                        @click.prevent="itemProps.type === itemTypeEnum.NON_VEG ? itemTypeReset() : itemTypeSet(itemTypeEnum.NON_VEG)"
                        :class="itemProps.type === itemTypeEnum.NON_VEG ? 'veg-active' : ''" type="button"
                        class="flex items-center gap-3 w-fit pl-3 pr-4 py-1.5 rounded-3xl transition hover:shadow-filter hover:bg-white bg-[#EFF0F6]">
                    <img :src="setting.image_vag" alt="category" class="h-6">
                    <span class="capitalize text-sm font-medium text-heading">{{ $t('label.frontend_non_veg') }}</span>
                    <i
                        class="lab-close-circle-line text-xl text-red-500 transition opacity-0 -ml-8 clear-item-type-filter font-fill-danger lab-font-size-24"></i>
                </button>
                <button :disabled="itemProps.type !== null && itemProps.type === itemTypeEnum.NON_VEG"
                        @click.prevent="itemProps.type === itemTypeEnum.VEG ? itemTypeReset() : itemTypeSet(itemTypeEnum.VEG)"
                        :class="itemProps.type === itemTypeEnum.VEG ? 'veg-active' : ''" type="button"
                        class="flex items-center gap-3 w-fit pl-3 pr-4 py-1.5 rounded-3xl transition hover:shadow-filter hover:bg-white bg-[#EFF0F6]">
                    <img :src="setting.image_non_vag" alt="category" class="h-6">
                    <span class="capitalize text-sm font-medium text-heading">{{ $t('label.veg') }}</span>
                    <i
                        class="lab-close-circle-line text-xl text-red-500 transition opacity-0 -ml-8 font-fill-danger lab-font-size-24"></i>
                </button>
            </ul>

            <div v-if="Object.keys(category).length > 0"
                 class="flex gap-4 flex-col sm:flex-row items-center justify-between mb-6">
                <h2 class="capitalize text-[26px] leading-[40px] font-semibold text-center sm:text-left text-primary">{{
                        category.name
                    }}</h2>
                <div class="flex items-center gap-3">
                    <button type="button" class="lab lab-row-vertical lab-font-size-20 text-xl"
                            v-on:click="itemProps.design = itemDesignEnum.LIST"
                            :class="itemProps.design === itemDesignEnum.LIST ? 'text-heading' : 'text-[#A0A3BD]'"></button>
                    <button type="button" class="lab lab-element-3 lab-font-size-20 text-xl"
                            v-on:click="itemProps.design = itemDesignEnum.GRID"
                            :class="itemProps.design === itemDesignEnum.GRID ? 'text-heading' : 'text-[#A0A3BD]'"></button>
                </div>
            </div>

            <ItemComponent :items="items.items" :type="itemProps.type" :design="itemProps.design"/>
        </div>
    </section>
</template>

<script>

import alertService from "../../../services/alertService";
import statusEnum from "../../../enums/modules/statusEnum";
import categoryDesignEnum from "../../../enums/modules/categoryDesignEnum";
import CategoryComponent from "../components/CategoryComponent";
import ItemComponent from "../components/ItemComponent";
import itemDesignEnum from "../../../enums/modules/itemDesignEnum";
import itemTypeEnum from "../../../enums/modules/itemTypeEnum";
import LoadingComponent from "../components/LoadingComponent";

export default {
    name: "MenuComponent",
    components: {CategoryComponent, ItemComponent, LoadingComponent},
    data() {
        return {
            loading: {
                isActive: false
            },
            itemTypeEnum: itemTypeEnum,
            itemDesignEnum: itemDesignEnum,
            category: {},
            items: {},
            categoryProps: {
                search: {
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'asc',
                    status: statusEnum.ACTIVE
                },
                design: categoryDesignEnum.SECOND
            },
            itemProps: {
                design: itemDesignEnum.LIST,
                type: null
            }
        }
    },
    computed: {
        categories: function () {
            return this.$store.getters['frontendItemCategory/lists'];
        },
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendItemCategory/lists", this.categoryProps.search).then((res) => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
        this.categoryShow();
    },
    methods: {
        itemTypeSet: function (e) {
            this.itemProps.type = e;
        },
        itemTypeReset: function () {
            this.itemProps.type = null;
        },
        categoryShow: function () {
            if (typeof this.$route.query.s !== "undefined" && this.$route.query.s !== "") {
                this.loading.isActive = true;
                this.$store.dispatch("frontendItemCategory/show", {
                    slug: this.$route.query.s,
                    vuex: false
                }).then((res) => {
                    this.category = res.data.data;
                    this.items = res.data.data;
                    this.loading.isActive = false;
                }).catch((err) => {
                    this.loading.isActive = false;
                });
            }
        }
    },
    watch: {
        $route() {
            this.categoryShow();
        }
    }
}
</script>
