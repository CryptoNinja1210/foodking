<template>
    <LoadingComponent :props="loading" />
    <div class="col-12">
        <form @submit.prevent="save" id="form" enctype="multipart/form-data">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ $t('menu.role') }} &amp; {{ $t('label.permissions') }} <span
                            class="text-primary">({{ role.name }})</span></h3>
                </div>
                <div class="db-table-responsive mb-8">
                    <table class="db-table stripe">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">#</th>
                                <th class="db-table-head-th">{{ $t('label.page') }}</th>
                                <th class="db-table-head-th">{{ $t('label.create') }}</th>
                                <th class="db-table-head-th">{{ $t('label.update') }}</th>
                                <th class="db-table-head-th">{{ $t('label.delete') }}</th>
                                <th class="db-table-head-th">{{ $t('label.view') }}</th>
                            </tr>
                        </thead>
                        <tbody v-if="permissions.length > 0" class="db-table-body">
                            <tr v-for="permission in permissions" :key="permission" class="db-table-body-tr">
                                <td class="db-table-body-td">
                                    <div class="custom-checkbox">
                                        <input type="checkbox" class="custom-checkbox-field"
                                            :name="'feature_' + permission.id" :value="permission.id"
                                            :id="'feature_' + permission.id" :checked="permission.access"
                                            @change="enable(permission, 0, $event)">
                                        <i class="fa-solid fa-check custom-checkbox-icon"></i>
                                    </div>
                                </td>
                                <td :colspan="!permission.children ? 5 : ''" class="db-table-body-td text-base capitalize">
                                    {{ permission.title }}</td>
                                <td v-if="permission.children" v-for="children in permission.children" :key="children"
                                    class="db-table-body-td text-sm">
                                    <div class="custom-checkbox">
                                        <input type="checkbox" class="custom-checkbox-field" :id="'feature_' + children.id"
                                            v-bind:disabled="!disabledStatue[children.id]" :value="children.id"
                                            :checked="checkedStatue[children.id]" @change="enable(children, 1, $event)">
                                        <i class="fa-solid fa-check custom-checkbox-icon"></i>
                                    </div>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="db-card-body">
                    <button class="db-btn text-white bg-primary">
                        <i class="lab lab-save"></i>
                        <span>{{ $t("button.save") }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";

export default {
    name: "RoleShowComponent",
    components: {
        LoadingComponent,
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            form: [],
            disabledStatue: {},
            checkedStatue: {}
        }
    },
    computed: {
        permissions: function () {
            return this.$store.getters['permission/lists'];
        },
        role: function () {
            return this.$store.getters['role/show'];
        },
    },
    mounted() {
        this.list();
        this.$store.dispatch("role/show", this.$route.params.id).then((res) => {
            this.loading.isActive = false;
        }).catch((error) => {
            this.loading.isActive = false;
        });
    },
    methods: {
        list: function () {
            this.loading.isActive = true;
            this.$store.dispatch('permission/lists', this.$route.params.id).then(res => {
                let parent = res.data.data;
                let children, i, j;
                let k = 0;

                for (i = 0; i < parent.length; i++) {
                    if (parent[i].access) {
                        this.form[k] = parent[i].id;
                        k++;

                        this.disabledStatue[parent[i].id] = parent[i].access;
                        this.checkedStatue[parent[i].id] = parent[i].access;
                    }
                    if (typeof parent[i].children === 'object' && parent[i].children != null) {
                        children = parent[i].children;

                        for (j = 0; j < children.length; j++) {
                            if (children[j].access) {
                                this.form[k] = children[j].id
                                k++;
                            }

                            if (this.disabledStatue[parent[i].id]) {
                                this.disabledStatue[children[j].id] = parent[i].access;
                            } else {
                                this.disabledStatue[children[j].id] = children[j].access;
                            }
                            this.checkedStatue[children[j].id] = children[j].access;
                        }
                    }
                }
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },

        enable: function (permission, type, event) {
            if (event.target.checked === true) {
                let length = this.form.length;
                this.form[length] = permission.id;
            } else {
                let j = 0;
                for (j = 0; j < this.form.length; j++) {
                    if (this.form[j] === permission.id) {
                        this.form.splice(j, 1);
                    }
                }
            }

            if (typeof permission.children === 'object' && permission.children != null) {
                let i, children = permission.children;
                for (i = 0; i < children.length; i++) {
                    if (type === 0) {
                        this.disabledStatue[children[i].id] = !this.disabledStatue[children[i].id];
                        this.checkedStatue[children[i].id] = event.target.checked;
                    }

                    if (event.target.checked === true) {
                        let length = this.form.length;
                        this.form[length] = children[i].id;
                    } else {
                        let j;
                        for (j = 0; j < this.form.length; j++) {
                            if (this.form[j] === children[i].id) {
                                this.form.splice(j, 1);
                            }
                        }
                    }
                }
            }
        },

        save: function () {
            this.loading.isActive = true;
            this.$store.dispatch('permission/save', {
                form: this.form,
                id: this.$route.params.id
            }).then(res => {
                this.list();
                this.loading.isActive = false;
                alertService.successFlip(1, this.$t("label.permissions"));
            }).catch((err) => {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);

            })
        }
    }
}

</script>
