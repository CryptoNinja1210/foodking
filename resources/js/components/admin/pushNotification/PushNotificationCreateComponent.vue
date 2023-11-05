<template>
    <LoadingComponent :props="loading" />
    <SmSidebarModalCreateComponent :props="addButton" />

    <div id="sidebar" class="drawer">
        <div class="drawer-header">
            <h3 class="drawer-title">{{ $t('menu.push_notifications') }}</h3>
            <button @click="reset" class="fa-solid fa-xmark close-btn"></button>
        </div>
        <div class="drawer-body">
            <form @submit.prevent="save">
                <div class="row">
                    <div class="col-12 sm:col-6">
                        <label for="role_id" class="db-field-title">{{ $t("label.role") }}</label>
                        <vue-select class="db-field-control f-b-custom-select" @search:change="selectUser($event)"
                            id="role_id" v-bind:class="errors.role_id ? 'invalid' : ''" v-model="form.role_id"
                            :options="roles" label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"
                            :clearOnClose="true" placeholder="--" search-placeholder="--" />
                        <small class="db-field-alert" v-if="errors.role_id">{{ errors.role_id[0] }}</small>
                    </div>

                    <div class="col-12 sm:col-6">
                        <label for="user_id" class="db-field-title">{{ $t("label.user") }}</label>
                        <vue-select class="db-field-control f-b-custom-select" id="user_id"
                            v-bind:class="errors.user_id ? 'invalid' : ''" v-model="form.user_id" :options="users"
                            label-by="name" value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                            placeholder="--" search-placeholder="--" />
                        <small class="db-field-alert" v-if="errors.user_id">{{ errors.user_id[0] }}</small>
                    </div>

                    <div class="col-12">
                        <label for="title" class="db-field-title required">{{ $t("label.title") }}</label>
                        <input v-model="form.title" v-bind:class="errors.title ? 'invalid' : ''" type="text" id="title"
                            class="db-field-control">
                        <small class="db-field-alert" v-if="errors.title">{{ errors.title[0] }}</small>
                    </div>

                    <div class="col-12">
                        <label class="db-field-title">{{ $t("label.image") }}</label>
                        <input @change="changeImage" v-bind:class="errors.image ? 'invalid' : ''" id="image" type="file"
                            class="db-field-control" ref="imageProperty" accept="image/png, image/jpeg, image/jpg">
                        <small class="db-field-alert" v-if="errors.image">{{ errors.image[0] }}</small>
                    </div>

                    <div class="col-12">
                        <label for="description" class="db-field-title required">
                            {{ $t("label.description") }}
                        </label>
                        <textarea v-model="form.description" v-bind:class="errors.description ? 'invalid' : ''"
                            id="description" class="db-field-control"></textarea>
                        <small class="db-field-alert" v-if="errors.description">{{ errors.description[0] }}</small>
                    </div>

                    <div class="col-12">
                        <div class="flex flex-wrap gap-3 mt-4">
                            <button type="submit" class="db-btn py-2 text-white bg-primary">
                                <i class="lab lab-save"></i>
                                <span>{{ $t("label.save") }}</span>
                            </button>
                            <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                <i class="lab lab-close"></i>
                                <span>{{ $t("button.close") }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
import SmSidebarModalCreateComponent from "../components/buttons/SmSidebarModalCreateComponent";
import LoadingComponent from "../components/LoadingComponent";
import statusEnum from "../../../enums/modules/statusEnum";
import roleEnum from "../../../enums/modules/roleEnum";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";

export default {
    name: "PushNotificationCreateComponent",
    components: {
        SmSidebarModalCreateComponent,
        LoadingComponent
    },
    props: ['props'],
    data() {
        return {
            loading: {
                isActive: false
            },
            addButton: {
                title: this.$t("button.add_push_notification")
            },
            form: {
                title: "",
                description: "",
                role_id: null,
                user_id: null,
            },
            enums: {
                statusEnum: statusEnum,
                roleEnum: roleEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive")
                },
            },
            image: "",
            errors: {},
            authBranchId: null
        }
    },
    computed: {
        roles: function () {
            return this.$store.getters['role/lists'];
        },
        users: function () {
            return this.$store.getters['user/lists'];
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("defaultAccess/show").then((res) => {
            this.authBranchId = res.data.data.branch_id;
        }).catch((err) => {
            this.loading.isActive = false;
        });
        this.$store.dispatch('role/lists', {
            order_column: 'id',
            order_type: 'asc',
        });

        this.$store.dispatch('user/lists', {
            order_column: 'id',
            order_type: 'asc',
            status: statusEnum.ACTIVE
        });

        this.loading.isActive = false;
    },
    methods: {
        changeImage: function (e) {
            this.image = e.target.files[0];
        },
        reset: function () {
            appService.sideDrawerHide();
            this.$store.dispatch('pushNotification/reset').then().catch();
            this.errors = {};
            this.form = {
                title: "",
                description: "",
                role_id: null,
                user_id: null,
            };
            if (this.image) {
                this.image = "";
                this.$refs.imageProperty.value = null;
            }
        },
        selectUser: function (e) {
            this.$store.dispatch('user/lists', {
                order_column: 'id',
                order_type: 'asc',
                status: statusEnum.ACTIVE,
                role_id: this.form.role_id,
                branch_id: null,
            });
        },
        save: function () {
            try {
                const fd = new FormData();
                fd.append('title', this.form.title);
                fd.append('role_id', this.form.role_id == null ? 0 : this.form.role_id);
                fd.append('user_id', this.form.user_id == null ? 0 : this.form.user_id);
                fd.append('branch_id', this.authBranchId);
                fd.append('description', this.form.description);

                if (this.image) {
                    fd.append('image', this.image);
                }

                const tempId = this.$store.getters['pushNotification/temp'].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch('pushNotification/save', {
                    form: fd,
                    search: this.props.search
                }).then((res) => {
                    appService.sideDrawerHide();
                    this.loading.isActive = false;
                    alertService.successFlip((tempId === null ? 0 : 1), this.$t(
                        'label.push_notification'));
                    this.form = {
                        title: "",
                        description: "",
                        role_id: null,
                        user_id: null,
                        status: statusEnum.ACTIVE,
                    }
                    this.image = "";
                    this.errors = {};
                    this.$refs.imageProperty.value = null;
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                })
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err)
            }
        }
    },
}

</script>
