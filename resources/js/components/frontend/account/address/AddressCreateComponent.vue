<template>
    <LoadingComponent :props="loading" />
    <button data-modal="#address" @click="add" v-on:click="this.props.isMap = true" type="button"
        class="address-btn flex items-center rounded-3xl px-3 h-8 gap-2 text-primary bg-[#FFEDF4] transition hover:bg-primary hover:text-white">
        <i class="lab lab-add-circle lab-font-size-16"></i>
        <span class="text-sm leading-6 capitalize font-medium transition">{{ addButton.title }}</span>
    </button>

    <div id="address" class="modal address ff-modal">
        <div class="modal-dialog">
            <div class="modal-header border-none pb-0">
                <h3 class="capitalize font-medium">{{ $t('label.your_address') }}</h3>
                <button class="modal-close fa-regular lab lab-close-circle-line lab-font-size-22 lab-font-bold"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <MapComponent :key="mapKey" v-if="props.isMap"
                        :location="{ lat: props.form.latitude, lng: props.form.longitude }" :position="location" />
                    <div class="flex items-center gap-2 mb-3">
                        <i class="lab lab-location text-xl text-primary"></i>
                        <span class="text-sm text-heading">{{ props.form.address }}</span>
                    </div>
                    <div class="mb-3">
                        <label for="apartment" class="text-xs leading-6 capitalize mb-1 text-heading">{{
                            $t('label.apartment_and_flat')
                        }}</label>
                        <textarea id="apartment" v-model="props.form.apartment"
                            class="h-12 w-full rounded-lg border py-1.5 px-2 placeholder:text-[10px] placeholder:text-[#6E7191] border-[#D9DBE9]"></textarea>
                    </div>
                    <div class="mb-6">
                        <h3 class="capitalize font-medium mb-2">{{ $t('label.add_label') }}</h3>
                        <nav class="flex flex-wrap gap-3 active-group">
                            <button @click="changeSwitchLabel(labelEnum.HOME)"
                                :class="props.switchLabel === labelEnum.HOME ? 'active' : ''"
                                v-on:click="this.props.status = false; this.props.form.label = $t('label.home')"
                                :value="labelEnum.HOME" type="button"
                                class="flex items-center gap-2 rounded-lg p-4 border bg-[#F7F7FC] border-[#F7F7FC]">
                                <i class="lab lab-home text-base leading-none"></i>
                                <span class="text-sm capitalize font-medium leading-none text-heading">{{
                                    $t('label.home')
                                }}</span>
                            </button>
                            <button @click="changeSwitchLabel(labelEnum.WORK)"
                                :class="props.switchLabel === labelEnum.WORK ? 'active' : ''"
                                v-on:click="this.props.status = false; this.props.form.label = $t('label.work')"
                                :value="labelEnum.WORK" type="button"
                                class="flex items-center gap-2 rounded-lg p-4 border bg-[#F7F7FC] border-[#F7F7FC]">
                                <i class="lab lab-briefcase text-base leading-none"></i>
                                <span class="text-sm capitalize font-medium leading-none text-heading">
                                    {{ $t('label.work') }}
                                </span>
                            </button>
                            <button @click="changeSwitchLabel(labelEnum.OTHER)"
                                :class="props.switchLabel === labelEnum.OTHER ? 'active' : ''"
                                v-on:click="this.props.status = true; this.props.form.label = ''; this.errors.label = ''"
                                :value="labelEnum.OTHER" type="button"
                                class="flex items-center gap-2 rounded-lg p-4 border bg-[#F7F7FC] border-[#F7F7FC]">
                                <i class="lab lab-more-square text-base leading-none"></i>
                                <span class="text-sm capitalize font-medium leading-none text-heading">{{
                                    $t('label.other')
                                }}</span>
                            </button>
                        </nav>
                        <small class="db-field-alert" v-if="errors.label && props.switchLabel !== labelEnum.OTHER">{{
                            errors.label[0]
                        }}</small>
                        <div v-if="props.status" :class="!props.status ? 'h-0' : ''" class="overflow-hidden transition">
                            <input type="text" :placeholder="$t('label.type_label_name')" v-model="props.form.label"
                                v-bind:class="errors.label ? 'invalid' : ''"
                                class="h-10 w-full rounded-lg border mt-5 py-1.5 px-4 placeholder:text-xs border-[#D9DBE9]">
                            <small class="db-field-alert" v-if="errors.label">{{ errors.label[0] }}</small>
                        </div>
                    </div>
                    <button type="submit" class="rounded-3xl text-base py-3 px-3 font-medium w-full text-white bg-primary">
                        {{ $t('button.confirm_location') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import AddressCreateModalComponent from "../../components/button/AddressCreateModalComponent";
import labelEnum from "../../../../enums/modules/labelEnum";
import MapComponent from "../../components/MapComponent";
import appService from "../../../../services/appService";
import alertService from "../../../../services/alertService";
import LoadingComponent from "../../components/LoadingComponent";

export default {
    name: "AddressComponent",
    components: { AddressCreateModalComponent, MapComponent, LoadingComponent },
    props: {
        props: Object,
        getLocation: Function
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            addButton: {
                title: this.$t("button.add_new"),
            },
            mapKey: "create-update",
            labelEnum: labelEnum,
            switchLabel: "",
            errors: {},
        }
    },
    methods: {
        add: function () {
            appService.modalShow();
        },
        changeSwitchLabel: function (id) {
            this.props.switchLabel = id;
        },
        location: function (e) {
            this.props.form.latitude = e.location.lat;
            this.props.form.longitude = e.location.lng;
            this.props.form.address = e.address;
        },
        reset: function () {
            appService.modalHide();
            this.$store.dispatch("frontendAddress/reset").then().catch();
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
                const tempId = this.$store.getters["frontendAddress/temp"].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch("frontendAddress/save", this.props).then((res) => {
                    this.getLocation(res.data.data);
                    appService.modalHide('#address');
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
}
</script>
