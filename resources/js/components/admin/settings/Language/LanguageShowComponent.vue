<template>
    <LoadingComponent :props="loading"/>

    <div class="db-card">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ $t("label.language") }}</h3>
        </div>
        <div class="db-card-body">
            <div class="row">
                <div class="col-12 sm:col-5">
                    <img class="db-image" alt="language" :src="language.image"/>
                </div>
                <div class="col-12 sm:col-7 md:pl-8">
                    <h3 class="text-lg font-medium capitalize mb-2 text-paragraph">
                        {{ language.name }}
                    </h3>
                    <p class="db-light-text mb-3">
                        {{ language.code }}
                    </p>
                    <label class="db-badge" :class="statusClass(language.status)">
                        {{ enums.statusEnumArray[language.status] }}
                    </label>
                </div>
            </div>
        </div>
    </div>


    <br>
    <div class="db-card">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ $t("label.files") }}</h3>
        </div>
        <div class="db-card-body">
            <div class="row">
                <div class="col-12 sm:col-12">
                    <form @submit.prevent="getFileText">
                        <div class="form-row">
                            <div class="form-col-12">
                                <label for="name" class="db-field-title required">
                                    {{ $t("label.name") }}
                                </label>
                                <select class="db-field-control" v-model="form.name">
                                    <option v-for="file in fileList">{{ file.name }}</option>
                                </select>
                                <small class="db-field-alert" v-if="errors.name">
                                    {{ errors.name[0] }}
                                </small>
                            </div>

                            <div class="form-col-12">
                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-languages"></i>
                                    <span>{{ $t("button.get_file_content") }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="db-card" v-if="Object.keys(fileText).length > 0">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ editFileInfo.name }}</h3>
        </div>
        <div class="db-card-body">
            <div class="row">
                <div class="col-12 sm:col-12">
                    <code class="font-color-danger language-note-font-size mb-3">
                        * You do not change any word under the curly bracket text {}. example {name}.
                        <br>
                        * When all language is changed then run some command in your terminal or ssh panel.
                        <br>
                        <strong class="strong-left-tab">
                            * npm install (run just one time)
                            <br>
                        </strong>
                        <strong class="strong-left-tab">
                            * npm run prod
                        </strong>
                    </code>
                </div>

                <div class="col-12 sm:col-12">
                    <form @submit.prevent="saveFileText">
                        <div class="form-row" v-for="text in fileText">
                            <div class="form-row" v-if="typeof text === 'object'">
                                <MultiInputLanguageComponent :props="{text : text, formPost : formPost }"/>
                            </div>
                            <div v-else class="form-col-12 md:form-col-12">
                                <label for="name" class="db-field-title required">
                                    {{ text }}
                                </label>
                                <input v-model="formPost[text.replaceAll(' ', '_')]" type="text" class="db-field-control">
                            </div>
                        </div>

                        <div class="form-col-12">
                            <div class="flex flex-wrap gap-3 mt-4">
                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-save"></i>
                                    <span>{{ $t("label.save") }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent";
import statusEnum from "../../../../enums/modules/statusEnum";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";
import _ from "lodash";
import MultiInputLanguageComponent from "../../components/MultiInputLanguageComponent.vue";

export default {
    name: "LanguageShowComponent",
    components: {
        MultiInputLanguageComponent,
        LoadingComponent,
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
            form: {
                name: "",
                path: ""
            },
            editFileInfo: {},
            formPost: {},
            errors: {}
        };
    },
    computed: {
        language: function () {
            return this.$store.getters["language/show"];
        },
        fileList: function () {
            return this.$store.getters["language/fileList"];
        },
        fileText: function () {
            return this.$store.getters["language/fileText"];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("language/show", this.$route.params.id).then((res) => {
            this.$store.dispatch("language/fileList", res.data.data.code).then((res) => {
                this.loading.isActive = false;
            }).catch((error) => {
                this.loading.isActive = false;
            });
        }).catch((error) => {
            this.loading.isActive = false;
        });
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        getFileText: function () {
            _.forEach(this.fileList, (value) => {
                if (value.name === this.form.name) {
                    this.form.path = value.path;
                }
            });

            this.editFileInfo = {
                name: this.form.name,
                path: this.form.path
            }

            this.$store.dispatch("language/fileText", this.form).then((res) => {
                if (res.data) {
                    this.formPost = {};
                    _.forEach(res.data, (value) => {
                        if (typeof value === 'object') {
                            this.fileTextToPostDataCreate(value);
                        } else {
                            const str = value;
                            const key = value.replaceAll(' ', '_');
                            this.formPost[key] = str;
                        }
                    });
                }
                this.loading.isActive = false;
            }).catch((error) => {
                this.loading.isActive = false;
            });
        },
        fileTextToPostDataCreate: function (data) {
            _.forEach(data, (value) => {
                if (typeof value === 'object') {
                    this.fileTextToPostDataCreate(value);
                } else {
                    const str = value;
                    const key = value.replaceAll(' ', '_');
                    this.formPost[key] = str;
                }
            });
        },
        saveFileText: function () {
            this.formPost['x_language_file_name'] = this.editFileInfo.name;
            this.formPost['x_language_file_path'] = this.editFileInfo.path;
            this.$store.dispatch("language/fileStore", this.formPost).then((res) => {
                alertService.success(this.$t("message.file_update_success"));
                this.loading.isActive = false;
            }).catch((error) => {
                alertService.error(error.response.data.errors);
                this.loading.isActive = false;
            });
        }
    },
};
</script>
