<template>
    <LoadingComponent :props="loading" />
    <SmModalCreateComponent :props="addButton" />

    <div id="modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t('menu.analytic_section') }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="name" class="db-field-title required">{{ $t("label.name") }}</label>
                            <input v-model="props.form.name" v-bind:class="errors.name ? 'invalid' : ''" type="text"
                                id="name" class="db-field-control">
                            <small class="db-field-alert" v-if="errors.name">{{ errors.name[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="section">{{ $t('label.section') }}</label>
                            <vue-select class="db-field-control f-b-custom-select" id="section"
                                v-bind:class="errors.section ? 'invalid' : ''" v-model="props.form.section"
                                :options="enums.analyticSectionObject" label-by="name" value-by="value"
                                :closeOnSelect="true" :searchable="true" :clearOnClose="true" placeholder="--"
                                search-placeholder="--" />
                            <small class="db-field-alert" v-if="errors.section">{{ errors.section[0] }}</small>
                        </div>
                        <div class="form-col-12">
                            <label for="data" class="db-field-title required">{{ $t("label.data") }}</label>
                            <textarea v-model="props.form.data" v-bind:class="errors.data ? 'invalid' : ''" id="data"
                                class="db-field-control"></textarea>
                            <small class="db-field-alert" v-if="errors.data">{{ errors.data[0] }}</small>
                        </div>
                        <div class="form-col-12">
                            <div class="modal-btns">
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                    <i class="lab lab-close"></i>
                                    <span>{{ $t('button.close') }}</span>
                                </button>

                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-save"></i>
                                    <span>{{ $t('button.save') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import SmModalCreateComponent from "../././../../components/buttons/SmModalCreateComponent.vue";
import LoadingComponent from "../../../components/LoadingComponent";
import analyticSectionEnum from "../../../../../enums/modules/analyticSectionEnum";
import alertService from "../../../../../services/alertService";
import appService from "../../../../../services/appService";

export default {
    name: "AnalyticSectionCreateComponent",
    components: { SmModalCreateComponent, LoadingComponent },
    props: ['props'],
    data() {
        return {
            loading: {
                isActive: false
            },
            addButton: {
                title: this.$t("button.add_analytic_section")
            },
            enums: {
                analyticSectionEnum: analyticSectionEnum,
                analyticSectionObject: [
                    {
                        name: this.$t("label.header"),
                        value: analyticSectionEnum.HEADER
                    },
                    {
                        name: this.$t("label.body"),
                        value: analyticSectionEnum.BODY,
                    },
                    {
                        name: this.$t("label.footer"),
                        value: analyticSectionEnum.FOOTER,
                    },
                ],
            },
            errors: {},
        }
    },
    methods: {
        reset: function () {
            appService.modalHide();
            this.$store.dispatch('analyticSection/reset').then().catch();
            this.errors = {};
            this.$props.props.form = {
                name: "",
                data: "",
                section: analyticSectionEnum.HEADER
            }
        },

        save: function () {
            try {
                const tempId = this.$store.getters['analyticSection/temp'].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch('analyticSection/save', this.props).then((res) => {
                    appService.modalHide();
                    this.loading.isActive = false;
                    alertService.successFlip((tempId === null ? 0 : 1), this.$t('menu.analytic_section'));
                    this.props.form = {
                        name: "",
                        data: '',
                        section: analyticSectionEnum.HEADER,
                    }
                    this.errors = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    if (err.response.data.status !== "undefined" && err.response.data.status === false) {
                        appService.modalHide();
                        alertService.error(err.response.data.message)
                    } else {
                        this.errors = err.response.data.errors;
                    }
                })
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err)
            }
        }
    }
}
</script>
