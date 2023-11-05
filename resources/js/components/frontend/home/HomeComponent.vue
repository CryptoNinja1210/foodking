<template>
    <LoadingComponent :props="loading" />

    <!--========TRACK PART START===========-->
    <TrackOrderComponent />
    <!--========TRACK PART END=============-->


    <!--========BANNER PART START===========-->
    <SliderComponent />
    <!--========BANNER PART END=============-->

    <!--========Category PART START=========-->
    <section v-if="categories.length > 0" class="mb-12">
        <div class="container">
            <div class="flex items-center justify-between gap-2 mb-6 mt-4">
                <h2 class="text-2xl font-semibold capitalize">{{ $t("label.our_menu") }}</h2>
                <router-link :to="{ name: 'frontend.menu', query: { s: categoryProps.slug } }"
                    class="rounded-3xl capitalize text-sm leading-6 font-medium py-1 px-3 transition text-primary bg-[#FFEDF4] hover:text-white hover:bg-primary">
                    {{ $t("button.view_all") }}
                </router-link>
            </div>
            <div class="swiper menu-swiper">
                <CategoryComponent :categories="categories" :design="categoryProps.design" />
            </div>
        </div>
    </section>
    <!--========Category PART END===========-->

    <!--========FEATURE PART START=========-->
    <FeaturedItemComponent />
    <!--========FEATURE PART END=========-->

    <!--========OFFER PART START=========-->
    <OfferComponent :limit="limit" />
    <!--========OFFER PART START=========-->

    <!--========POPULAR PART START=========-->
    <PopularItemComponent />
    <!--========POPULAR PART START=========-->
</template>

<script>
import SliderComponent from "../../frontend/home/SliderComponent";
import CategoryComponent from "../components/CategoryComponent";
import FeaturedItemComponent from "../home/FeaturedItemComponent";
import PopularItemComponent from "../home/PopularItemComponent";
import OfferComponent from "../components/OfferComponent";
import categoryDesignEnum from "../../../enums/modules/categoryDesignEnum";
import statusEnum from "../../../enums/modules/statusEnum";
import LoadingComponent from "../components/LoadingComponent";
import TrackOrderComponent from "./TrackOrderComponent";

export default {
    name: "HomeComponent",
    components: {
        TrackOrderComponent,
        CategoryComponent,
        SliderComponent,
        FeaturedItemComponent,
        PopularItemComponent,
        OfferComponent,
        LoadingComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            categoryProps: {
                design: categoryDesignEnum.FIRST,
                slug: '',
            },
            limit: 4,
        };
    },
    computed: {
        categories: function () {
            return this.$store.getters["frontendItemCategory/lists"];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendItemCategory/lists", {
            paginate: 0,
            order_column: "id",
            order_type: "asc",
            status: statusEnum.ACTIVE,
        }).then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },
    watch: {
        categories: {
            deep: true,
            handler(category) {
                if (category.length > 0) {
                    if (category[0].slug !== "undefined") {
                        this.categoryProps.slug = category[0].slug;
                    }
                }
            },
        },
    },
};
</script>
