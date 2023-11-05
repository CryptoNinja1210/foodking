<template>
    <LoadingComponent :props="loading" />
    <div class="db-card">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ $t('menu.push_notifications') }}</h3>
        </div>
        <div class="db-card-body">
            <div class="row">
                <div v-if="pushNotification.image" class="col-12 sm:col-5">
                    <img class="db-image" alt="push_notification" :src="pushNotification.image">
                </div>
                <div class="col-12 sm:col-7" :class="pushNotification.image ? 'md:pl-8' : ''">
                    <h3 class="text-lg font-medium capitalize mb-2 text-paragraph">{{ pushNotification.title }}</h3>
                    <label class="db-badge mb-3 db-table-badge text-green-600 bg-green-100">
                        {{ pushNotification.customer }}
                    </label>
                    <p class="db-light-text">
                        {{ pushNotification.description }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import statusEnum from "../../../enums/modules/statusEnum";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";
import notificationTypeEnum from "../../../enums/modules/notificationTypeEnum";

export default {
    name: "PushNotificationShowComponent",
    components: {
        LoadingComponent
    },
    data() {
        return {
            loading: {
                isActive: false
            },
        }
    },
    computed: {
        pushNotification: function () {
            return this.$store.getters['pushNotification/show'];
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch('pushNotification/show', this.$route.params.id).then(res => {
            this.loading.isActive = false;
        }).catch((error) => {
            this.loading.isActive = false;
        });
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        }
    }
}

</script>
