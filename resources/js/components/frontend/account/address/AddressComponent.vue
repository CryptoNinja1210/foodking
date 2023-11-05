<template>
    <LoadingComponent :props="loading" />
    <address class="pt-8 pb-16">
        <div class="container max-w-3xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-[26px] leading-10 font-semibold capitalize">{{ $t('label.address') }}</h3>
                <AddressCreateComponent :getLocation="updateAddress" :props="address" />
            </div>
            <div class="row">
                <div class="col-12 sm:col-6" v-if="addresses.length > 0" v-for="address in addresses" :key="address">
                    <div class="p-3 rounded-lg w-full bg-[#F7F7FC]">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2 text-[#008BBA]">
                                <i class="lab lab-home lab-font-size-16" v-if="address.label == 'Home'"></i>
                                <i class="lab lab-briefcase lab-font-size-16" v-else-if="address.label == 'Work'"></i>
                                <i class="lab lab-more-square lab-font-size-16" v-else></i>
                                <span class="font-medium leading-6 capitalize">{{ address.label }}</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                                <button data-modal="#address" type="button" @click="edit(address)"
                                    class="w-6 h-6 rounded-full bg-primary flex items-center justify-center">
                                    <i class="lab lab-edit-2 text-white lab-font-size-16"></i>
                                </button>
                                <button type="button" @click="destroy(address.id)"
                                    class="w-6 h-6 rounded-full bg-[#FB4E4E] flex items-center justify-center">
                                    <i class="lab lab-delete-bold text-white lab-font-size-16"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <i class="lab lab-location mt-0.5 text-paragraph lab-font-size-16"></i>
                            <span class="text-sm leading-6 text-heading">
                                {{ address.apartment ? address.apartment + ', ' : '' }}
                                {{ address.address }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </address>
</template>

<script>


import alertService from "../../../../services/alertService";
import AddressCreateComponent from "./AddressCreateComponent";
import appService from "../../../../services/appService";
import labelEnum from "../../../../enums/modules/labelEnum";
import LoadingComponent from "../../components/LoadingComponent";

export default {
    name: "AddressComponent",
    components: {
        LoadingComponent,
        AddressCreateComponent,
    },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            localAddress: {},
            address: {
                form: {
                    address: "",
                    apartment: "",
                    latitude: "",
                    longitude: "",
                    label: "",
                },
                search: {
                    paginate: 0,
                    order_column: "id",
                    order_type: "asc",

                },
                status: false,
                switchLabel: "",
                isMap: false,
            },
        }
    },
    mounted() {
        this.list();
    },
    computed: {
        addresses: function () {
            return this.$store.getters["frontendAddress/lists"];
        },
    },
    methods: {
        list: function () {
            this.loading.isActive = true;
            this.$store.dispatch("frontendAddress/lists", {
                search: this.address.search
            }).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        updateAddress: function (address) {
            this.localAddress = address;
        },
        edit: function (address) {
            appService.modalShow();
            this.loading.isActive = true;
            this.$store.dispatch("frontendAddress/edit", address.id).then((res) => {
                this.loading.isActive = false;
                this.address.isMap = true;
                this.address.form = {
                    address: address.address,
                    apartment: address.apartment,
                    latitude: address.latitude,
                    longitude: address.longitude,
                    label: address.label,

                };
                if (this.address.form.label === this.$t("label.home")) {
                    this.address.status = false;
                    this.address.switchLabel = labelEnum.HOME;
                } else if (this.address.form.label === this.$t("label.work")) {
                    this.address.status = false;
                    this.address.switchLabel = labelEnum.WORK;
                } else {
                    this.address.status = true;
                    this.address.switchLabel = labelEnum.OTHER;
                }
            }).catch((err) => {
                alertService.error(err.response.data.message);
            });
        },
        destroy: function (addressId) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch("frontendAddress/destroy", {
                        id: addressId,
                        search: this.address.search,
                    }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, this.$t("label.address"));
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.message);
                    });
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
    }
}
</script>
