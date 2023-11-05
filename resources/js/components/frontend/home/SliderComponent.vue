<template>
    <LoadingComponent :props="loading" />
    <div v-if="sliders.length > 0" class="container mb-5 mt-5 sm:mt-8">
        <div class="swiper banner-swiper">
            <div class="swiper-wrapper">
                <Carousel :settings="settings" :items-to-show="1">
                    <slide class="swiper-slide" v-for="slider in sliders" :key="slider">
                        <img class="w-full rounded-2xl" :src="slider.image" alt="banner">
                    </slide>
                    <template #addons>
                        <navigation />
                        <pagination />
                    </template>
                </Carousel>
            </div>
        </div>
    </div>
</template>

<script>
import alertService from "../../../services/alertService";
import statusEnum from "../../../enums/modules/statusEnum";
import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'
import LoadingComponent from "../components/LoadingComponent";

export default {
    name: "SliderComponent",
    components: {
        Carousel,
        Slide,
        Pagination,
        Navigation,
        LoadingComponent
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            sliderProps: {
                search: {
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'desc',
                    status: statusEnum.ACTIVE
                }
            },
            settings: {
                pauseAutoplayOnHover: true,
                wrapAround: true,
                transition: 1000,
                autoplay: 5000
            },
        }
    },
    computed: {
        sliders: function () {
            return this.$store.getters['frontendSlider/lists'];
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendSlider/lists", this.sliderProps.search).then((res) => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    }
}
</script>
