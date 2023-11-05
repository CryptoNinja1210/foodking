<template>
    <LoadingComponent :props="loading" />
    <SmModalCreateComponent v-on:click="this.props.isMap = true" :props="addButton" />

    <div id="modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("label.address") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 map-height">
                            <MapComponent v-if="props.isMap"
                                :location="{ lat: props.form.latitude, lng: props.form.longitude }" :position="location" />
                        </div>

                        <div class="form-col-12">
                            <label for="apartment" class="db-field-title font-medium text-sm my-0">
                                {{ props.form.address }}
                            </label>
                        </div>

                        <div class="form-col-12">
                            <label for="apartment" class="db-field-title">{{ $t("label.apartment") }}</label>
                            <input v-model="props.form.apartment" v-bind:class="errors.apartment ? 'invalid' : ''"
                                type="text" id="apartment" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.apartment">{{ errors.apartment[0] }}</small>
                        </div>

                        <div class="form-col-12">
                            <label for="home" class="db-field-title required">{{ $t("label.label") }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio"
                                            v-on:click="this.props.status = false; this.props.form.label = $t('label.home')"
                                            v-model="props.switchLabel" id="home" :value="labelEnum.HOME"
                                            class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="home" class="db-field-label">{{ $t("label.home") }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio"
                                            v-on:click="this.props.status = false; this.props.form.label = $t('label.work')"
                                            class="custom-radio-field" v-model="props.switchLabel" id="work"
                                            :value="labelEnum.WORK" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="work" class="db-field-label">{{ $t("label.work") }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio"
                                            v-on:click="this.props.status = true; this.props.form.label = ''; this.errors.label = ''"
                                            class="custom-radio-field" v-model="props.switchLabel" id="other"
                                            :value="labelEnum.OTHER" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="other" class="db-field-label">{{ $t("label.other") }}</label>
                                </div>
                            </div>
                            <small class="db-field-alert" v-if="errors.label && props.switchLabel !== labelEnum.OTHER">{{
                                errors.label[0]
                            }}</small>
                        </div>

                        <div class="form-col-12" v-if="props.status">
                            <label for="new_label" class="db-field-title required">{{ $t("label.new_label") }}</label>
                            <input v-model="props.form.label" v-bind:class="errors.label ? 'invalid' : ''" type="text"
                                id="new_label" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.label">{{ errors.label[0] }}</small>
                        </div>

                        <div class="form-col-12">
                            <div class="modal-btns">
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                    <i class="lab lab-close"></i>
                                    <span>{{ $t("button.close") }}</span>
                                </button>

                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-save"></i>
                                    <span>{{ $t("button.save") }}</span>
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
import labelEnum from "../../../../enums/modules/labelEnum";
import SmModalCreateComponent from "../../components/buttons/SmModalCreateComponent";
import LoadingComponent from "../../components/LoadingComponent";
import MapComponent from "../../components/MapComponent";
import appService from "../../../../services/appService";
import alertService from "../../../../services/alertService";

export default {
    name: "DeliveryBoyAddressCreateComponent",
    components: { SmModalCreateComponent, LoadingComponent, MapComponent },
    props: {
        props: Object
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            addButton: {
                title: this.$t("button.add_address"),
            },
            labelEnum: labelEnum,
            switchLabel: "",
            errors: {},
        };
    },
    methods: {
        location: function (e) {
            this.props.form.address = e.address;
            this.props.form.latitude = e.location.lat;
            this.props.form.longitude = e.location.lng;
        },
        reset: function () {
            appService.modalHide();
            this.$store.dispatch("deliveryBoyAddress/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                address: "",
                apartment: "",
                latitude: "",
                longitude: "",
                label: "",
            };
            this.$props.props.status = false;
            this.$props.props.switchLabel = "";
            this.$props.props.isMap = false;
        },
        save: function () {
            try {
                const tempId = this.$store.getters["deliveryBoyAddress/temp"].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch("deliveryBoyAddress/save", {
                    search: this.props.search,
                    form: this.props.form,
                    id: this.$route.params.id
                }).then((res) => {
                    appService.modalHide();
                    this.loading.isActive = false;
                    alertService.successFlip(tempId === null ? 0 : 1, this.$t("label.address"));
                    this.props.form = {
                        address: "",
                        apartment: "",
                        latitude: "",
                        longitude: "",
                        label: "",
                    };
                    this.props.isMap = false;
                    this.props.status = false;
                    this.props.switchLabel = "";
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
    }
};
</script>
