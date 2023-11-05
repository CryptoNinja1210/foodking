<template>
    <div class="swiper-wrapper" :class="design === categoryDesignEnum.SECOND ? 'menu-slides' : ''">
        <Carousel :settings="settings" :breakpoints="breakpoints">
            <slide class="swiper-slide" v-for="category in categories" :key="category">
                <router-link  v-if="design === categoryDesignEnum.FIRST"
                             :to="{name: 'frontend.menu', query:{ s: category.slug}}"
                             class="swiper-slide w-32 flex flex-col items-center text-center gap-4 p-3 c-h-30 rounded-2xl border-b-2 border-transparent transition hover:bg-[#FFEDF4] bg-[#F7F7FC]">
                    <img class="h-12 drop-shadow-category" :src="category.thumb" alt="category">
                    <h3 class="text-xs font-medium">{{ category.name }}</h3>
                </router-link>

                <router-link :class="checkIsQueryAndRouteQuerySame(category.slug) ? 'menu-category-active' : ''" v-else-if="design === categoryDesignEnum.SECOND"
                             :to="{name: 'frontend.menu', query:{ s: category.slug}}"
                             class="swiper-slide w-32 flex flex-col items-center text-center gap-4 p-3 c-h-25 rounded-2xl border-b-2 border-transparent transition hover:bg-[#FFEDF4]">
                    <img class="h-9 drop-shadow-category" :src="category.thumb" alt="category">
                    <h3 class="text-xs font-medium">{{ category.name }}</h3>
                </router-link>
            </slide>
        </Carousel>
    </div>
</template>

<script>

import categoryDesignEnum from "../../../enums/modules/categoryDesignEnum";
import 'vue3-carousel/dist/carousel.css';
import {Carousel, Slide, Pagination, Navigation} from 'vue3-carousel'

export default {
    name: "CategoryComponent",
    props: {
        categories: Object,
        design: Number
    },
    components: {
        Carousel,
        Slide,
        Pagination,
        Navigation,
    },
    data() {
        return {
            currentCategory: "",
            categoryDesignEnum: categoryDesignEnum,
            settings: {
                itemsToShow: 8,
                wrapAround: false,
                snapAlign: "start"
            },
            breakpoints: {
                // 200px and up
                200: {
                    itemsToShow: 1.1,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                // 250px and up
                250: {
                    itemsToShow: 1.5,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                // 300px and up
                300: {
                    itemsToShow: 2,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                // 375px and up
                375: {
                    itemsToShow: 2.5,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                540: {
                    itemsToShow: 3.5,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                // 700px and up
                700: {
                    itemsToShow: 4.5,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                // 1024 and up
                1024: {
                    snapAlign: 'start',
                    itemsToShow: 7,
                    wrapAround: false,
                },
                // 1180 and up
                1180: {
                    snapAlign: 'start',
                    itemsToShow: 8,
                    wrapAround: false,
                }
            },
        }
    },
    mounted() {
        if(this.$route.query.s !== "undefined") {
            this.currentCategory = this.$route.query.s;
        }
    },
    methods: {
        submit: function (msg, e) {
            e.stopPropagation()
        },
        checkIsQueryAndRouteQuerySame(query) {
            if (this.currentCategory === query) {
                return true;
            }
        },
    },
    watch: {
        $route(to, from) {
            if(to.query.s !== "undefined") {
                this.currentCategory = to.query.s;
            }
        }
    }
}
</script>
