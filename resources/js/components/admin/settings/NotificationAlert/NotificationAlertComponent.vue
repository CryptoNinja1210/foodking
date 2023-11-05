<template>
    <LoadingComponent :props="loading" />


    <div id="notify" class="db-tab-div active">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-5">
            <button @click="selectActive(index)"
                class=" db-tab-sub-btn w-full flex items-center gap-3 h-10 px-4 rounded-lg transition                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     bg-white hover:text-primary hover:bg-primary/5"
                v-for="(notificationAlert, index) in  enums.notificationAlertObject" :key="notificationAlert"
                :data-tab="'#' + notificationAlert.slug" :class="index === selectIndex ? 'active' : ''">
                <i class="lab lab-mail text-sm" v-if="notificationAlert.slug === 'mail'"></i>
                <i class="lab lab-sms text-sm" v-if="notificationAlert.slug === 'sms'"></i>
                <i class="lab lab-notification text-sm" v-if="notificationAlert.slug === 'push_notification'"></i>
                <span class="whitespace-nowrap text-[15px]">{{ notificationAlert.name }}</span>
            </button>
        </div>
        <div :id="notificationAlert.slug" class="db-card db-tab-sub-div"
            v-for="(notificationAlert, index) in  enums.notificationAlertObject" :key="notificationAlert"
            :class="index === selectIndex ? 'active' : ''">
            <div class="db-card-header">
                <h3 class="db-card-title" v-if="notificationAlert.slug === 'push_notification'">{{ notificationAlert.name }}
                    {{ $t("label.messages") }}</h3>
                <h3 class="db-card-title" v-else>{{ notificationAlert.name }} {{ $t("label.notification") }} {{
                    $t("label.messages") }}</h3>
            </div>

            <div class="db-card-body" v-if="notificationAlert.slug === 'mail'">
                <form class="row" @submit.prevent="save(notificationAlert.slug, index)"
                    :id="'formElem_' + notificationAlert.slug + index">
                    <div class="col-12 xl:col-6" v-for="notification in notificationAlerts" :key="notification">
                        <div class="flex items-center justify-between mb-3">
                            <label class="capitalize text-sm text-heading">{{ notification.name }}</label>
                            <div class="custom-switch">
                                <input @click="selectCheckbox($event, notificationAlert.slug, notification.id)"
                                    :id="notificationAlert.slug + notification.id" type="checkbox"
                                    :value="notification.mail" :name="notificationAlert.slug + notification.id"
                                    :checked="notification.mail === enums.switchEnum.ON">
                                <label v-if="notification.mail === enums.switchEnum.ON"
                                    :for="notificationAlert.slug + notification.id">{{ $t('label.on') }}</label>
                                <label v-else :for="notificationAlert.slug + notification.id">{{ $t('label.off') }}</label>
                            </div>
                        </div>
                        <textarea :name="notification.id"
                            class="w-full h-14 rounded-lg py-2 px-3 resize-none border transition border-[#EFF0F6] focus-within:border-primary/20">{{ notification.mail_message }}</textarea>
                    </div>
                    <input type="hidden" name="type" :value="notificationAlert.slug">
                    <div class="col-12">
                        <button type="submit" class="db-btn text-white bg-primary">
                            <i class="lab lab-save"></i>
                            <span>{{ $t("label.save") }}</span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="db-card-body" v-if="notificationAlert.slug === 'sms'">
                <form class="row" @submit.prevent="save(notificationAlert.slug, index)"
                    :id="'formElem_' + notificationAlert.slug + index">
                    <div class="col-12 xl:col-6" v-for="notification in notificationAlerts" :key="notification">
                        <div class="flex items-center justify-between mb-3">
                            <label class="capitalize text-sm text-heading">{{ notification.name }}</label>
                            <div class="custom-switch">
                                <input @click="selectCheckbox($event, notificationAlert.slug, notification.id)"
                                    :id="notificationAlert.slug + notification.id" type="checkbox" :value="notification.sms"
                                    :name="notificationAlert.slug + notification.id"
                                    :checked="notification.sms === enums.switchEnum.ON">
                                <label v-if="notification.sms === enums.switchEnum.ON"
                                    :for="notificationAlert.slug + notification.id">{{ $t('label.on') }}</label>
                                <label v-else :for="notificationAlert.slug + notification.id">{{ $t('label.off') }}</label>
                            </div>
                        </div>
                        <textarea :name="notification.id"
                            class="w-full h-14 rounded-lg py-2 px-3 resize-none border transition border-[#EFF0F6] focus-within:border-primary/20">{{ notification.sms_message }}</textarea>
                    </div>
                    <input type="hidden" name="type" :value="notificationAlert.slug">
                    <div class="col-12">
                        <button type="submit" class="db-btn text-white bg-primary">
                            <i class="lab lab-save"></i>
                            <span>{{ $t("label.save") }}</span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="db-card-body" v-if="notificationAlert.slug === 'push_notification'">
                <form class="row" @submit.prevent="save(notificationAlert.slug, index)"
                    :id="'formElem_' + notificationAlert.slug + index">
                    <div class="col-12 xl:col-6" v-for="notification in notificationAlerts" :key="notification">
                        <div class="flex items-center justify-between mb-3">
                            <label class="capitalize text-sm text-heading">{{ notification.name }}</label>
                            <div class="custom-switch">
                                <input @click="selectCheckbox($event, notificationAlert.slug, notification.id)"
                                    :id="notificationAlert.slug + notification.id" type="checkbox"
                                    :value="notification.push_notification" :name="notificationAlert.slug + notification.id"
                                    :checked="notification.push_notification === enums.switchEnum.ON">
                                <label v-if="notification.push_notification === enums.switchEnum.ON"
                                    :for="notificationAlert.slug + notification.id">{{ $t('label.on') }}</label>
                                <label v-else :for="notificationAlert.slug + notification.id">{{ $t('label.off') }}</label>
                            </div>
                        </div>
                        <textarea :name="notification.id"
                            class="w-full h-14 rounded-lg py-2 px-3 resize-none border transition border-[#EFF0F6] focus-within:border-primary/20">{{ notification.push_notification_message }}</textarea>
                    </div>
                    <input type="hidden" name="type" :value="notificationAlert.slug">
                    <div class="col-12">
                        <button type="submit" class="db-btn text-white bg-primary">
                            <i class="lab lab-save"></i>
                            <span>{{ $t("label.save") }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";
import switchEnum from "../../../../enums/modules/switchEnum";

export default {
    name: "NotificationAlertComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                switchEnum: switchEnum,
                notificationAlertObject: [
                    {
                        name: this.$t("label.mail"),
                        slug: "mail"
                    },
                    {
                        name: this.$t("label.sms"),
                        slug: "sms"
                    },
                    {
                        name: this.$t("label.push_notification"),
                        slug: "push_notification"
                    },
                ],
            },
            selectIndex: 0,
            form: {
                license_key: "",
            },
            errors: {},
        };
    },
    mounted() {
        try {
            this.loading.isActive = true;
            this.$store.dispatch("notificationAlert/lists").then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        } catch (err) {
            this.loading.isActive = false;
            alertService.error(err);
        }
    },
    computed: {
        notificationAlerts: function () {
            return this.$store.getters["notificationAlert/lists"];
        },
    },
    methods: {
        selectActive: function (index) {
            this.selectIndex = index;
        },
        selectCheckbox: function (e, slug, id) {
            document.getElementById(slug + id).value = e.target.checked ? 5 : 10;
        },
        save: function (slug, index) {
            try {
                let form = document.getElementById("formElem_" + slug + index);
                let formDataObj = {};
                [...form.elements].filter((el) => el.tagName !== 'BUTTON').forEach((item) => {
                    formDataObj[item.name] = item.value;
                });

                this.loading.isActive = true;
                this.$store.dispatch("notificationAlert/save", { form: formDataObj }).then((res) => {
                    this.loading.isActive = false;
                    alertService.successFlip(res.config.method === "put" ?? 0, this.$t("menu.notification_alert"));
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
