<template>
    <section class="pt-8 pb-16">
        <div class="container max-w-3xl">
            <div class="mb-6">
                <h2 class="text-[26px] leading-10 font-semibold capitalize mb-2">
                    {{ page.title }}
                </h2>
                <div v-if="page.image" class="w-full mb-6">
                    <img :src="page.image" alt="image">
                </div>
                <p class="text-xs text-heading">
                    {{ page.description }}
                </p>
            </div>
            <TemplateManagerComponent :templateId="page.template_id" />


        </div>
    </section>
</template>

<script>
import TemplateManagerComponent from "../components/TemplateManagerComponent";

export default {
    name: "PageComponent",
    components: { TemplateManagerComponent },
    computed: {
        page: function () {
            return this.$store.getters['frontendPage/show'];
        }
    },
    mounted() {
        this.pageSetup();
    },
    methods: {
        pageSetup: function () {
            if (Object.keys(this.$route.params).length > 0 && typeof this.$route.params.slug === 'string') {
                this.$store.dispatch('frontendPage/show', this.$route.params.slug).then(res => {

                }).catch((err) => { })
            }
        }
    },
    watch: {
        $route() {
            this.pageSetup();
        }
    }
}
</script>
