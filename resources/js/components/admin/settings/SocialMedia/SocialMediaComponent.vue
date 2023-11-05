<template>
    <LoadingComponent :props="loading" />

    <div id="company" class="db-card db-tab-div active">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ $t("menu.social_media") }}</h3>
        </div>
        <div class="db-card-body">
            <form @submit.prevent="save">
                <div class="form-row">
                    <div class="form-col-12 sm:form-col-6">
                        <label for="social_media_facebook" class="db-field-title">{{ $t("label.facebook") }}</label>
                        <input v-model="form.social_media_facebook" v-bind:class="
                            errors.social_media_facebook ? 'invalid' : ''
                        " type="text" id="social_media_facebook" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.social_media_facebook">{{
                            errors.social_media_facebook[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="social_media_youtube" class="db-field-title">{{ $t("label.youtube") }}</label>
                        <input v-model="form.social_media_youtube" v-bind:class="
                            errors.social_media_youtube ? 'invalid' : ''
                        " type="text" id="social_media_youtube" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.social_media_youtube">{{
                            errors.social_media_youtube[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="social_media_instagram" class="db-field-title">{{ $t("label.instagram") }}</label>
                        <input v-model="form.social_media_instagram" v-bind:class="
                            errors.social_media_instagram ? 'invalid' : ''
                        " type="text" id="social_media_instagram" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.social_media_instagram">{{
                            errors.social_media_instagram[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="social_media_twitter" class="db-field-title">{{ $t("label.twitter") }}</label>
                        <input v-model="form.social_media_twitter" v-bind:class="
                            errors.social_media_twitter ? 'invalid' : ''
                        " type="text" id="social_media_twitter" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.social_media_twitter">{{
                            errors.social_media_twitter[0]
                        }}</small>
                    </div>
                    <div class="form-col-12">
                        <button type="submit" class="db-btn text-white bg-primary">
                            <i class="lab lab-save"></i>
                            <span>{{ $t("button.save") }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";

export default {
    name: "SocialMediaComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            form: {
                social_media_facebook: "",
                social_media_youtube: "",
                social_media_instagram: "",
                social_media_twitter: "",
            },
            errors: {},
        };
    },
    mounted() {
        try {
            this.loading.isActive = true;
            this.$store
                .dispatch("socialMedia/lists")
                .then((res) => {
                    this.form = {
                        social_media_facebook: res.data.data.social_media_facebook,
                        social_media_youtube: res.data.data.social_media_youtube,
                        social_media_instagram: res.data.data.social_media_instagram,
                        social_media_twitter: res.data.data.social_media_twitter,
                    };
                    this.loading.isActive = false;
                })
                .catch((err) => {
                    this.loading.isActive = false;
                });
        } catch (err) {
            this.loading.isActive = false;
        }
    },
    methods: {
        save: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("socialMedia/save", this.form).then((res) => {
                    this.loading.isActive = false;
                    alertService.successFlip(res.config.method === "put" ?? 0, this.$t("menu.social_media"));
                    this.errors = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
};
</script>
