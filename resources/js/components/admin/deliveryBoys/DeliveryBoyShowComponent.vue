<template>
    <LoadingComponent :props="loading" />

    <div class="col-12" v-if="Object.entries(deliveryBoy).length > 0">
        <div class="p-6 rounded-xl mb-8 shadow-xs bg-white">
            <div class="flex flex-wrap gap-4 sm:gap-6">
                <img class="w-[120px] h-[120px] object-cover rounded-lg" :src="previewImage" alt="avatar">
                <div>
                    <h3 class="text-[26px] font-semibold font-rubik leading-[40px] capitalize">
                        {{ textShortener(deliveryBoy.name, 20) }}
                    </h3>
                    <label
                        class="p-0.5 px-2 rounded text-[10px] leading-4 font-medium font-rubik uppercase mb-[22px] text-[#E89806] bg-[#FFF5DE]">
                        {{ $t('label.delivery_boy') }}
                    </label>
                    <form @submit.prevent="saveImage">
                        <div class="flex gap-3 md:gap-4">
                            <label for="photo"
                                class="db-btn relative cursor-pointer h-[38px] shadow-[0px_6px_10px_rgba(255,_0,_107,_0.24)] bg-primary text-white">
                                <i class="lab lab-upload-image"></i>
                                <span class="hidden sm:inline-block">{{ $t('button.upload_new_photo') }}</span>
                                <input v-if="uploadButton" @change="changePreviewImage" ref="imageProperty"
                                    accept="image/png, image/jpeg, image/jpg" type="file" id="photo"
                                    class="absolute top-0 left-0 w-full h-full -z-10 opacity-0">
                            </label>
                            <button v-if="saveButton" type="submit"
                                class="db-btn h-[38px] shadow-[0px_6px_10px_rgba(26,_183,_89,_0.24)] text-white bg-[#1AB759]">
                                <i class="lab lab-tick-circle-2"></i>
                                <span class="hidden sm:inline-block">{{ $t('button.save') }}</span>
                            </button>
                            <button v-if="resetButton" @click="resetPreviewImage" type="button"
                                class="db-btn-outline h-[38px] shadow-[0px_6px_10px_rgba(251,_78,_78,_0.24)] !text-[#FB4E4E] !bg-white !border-[#FB4E4E]">
                                <i class="lab lab-reset"></i>
                                <span class="hidden sm:inline-block">{{ $t('button.reset') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="flex flex-col items-start sm:flex-row sm:items-center gap-1.5 mb-6">
            <button type="button" data-tab="#profile"
                class="profile-tabBtn active w-full justify-start sm:w-fit inline-flex items-center sm:justify-center gap-2 h-[38px] py-2 px-4 rounded-md text-[#6E7191] stroke-[#6E7191]">
                <i class="lab lab-user-line"></i>
                <span class="capitalize text-sm">{{ $t('button.profile') }}</span>
            </button>
            <button type="button" data-tab="#security"
                class="profile-tabBtn w-full justify-start sm:w-fit inline-flex items-center sm:justify-center gap-2 h-[38px] py-2 px-4 rounded-md text-[#6E7191] stroke-[#6E7191]">
                <i class="lab lab-unlock"></i>
                <span class="capitalize text-sm">{{ $t('button.security') }}</span>
            </button>
            <button type="button" data-tab="#address"
                class="profile-tabBtn w-full justify-start sm:w-fit inline-flex items-center sm:justify-center gap-2 h-[38px] py-2 px-4 rounded-md text-[#6E7191] stroke-[#6E7191]">
                <i class="lab lab-location-line"></i>
                <span class="capitalize text-sm">{{ $t('button.address') }}</span>
            </button>
            <button type="button" data-tab="#orders"
                class="profile-tabBtn w-full justify-start sm:w-fit inline-flex items-center sm:justify-center gap-2 h-[38px] py-2 px-4 rounded-md text-[#6E7191] stroke-[#6E7191]">
                <i class="lab lab-reserve-line"></i>
                <span class="capitalize text-sm">{{ $t('button.my_orders') }}</span>
            </button>
            <button type="button" data-tab="#deliveredOrders"
                class="profile-tabBtn w-full justify-start sm:w-fit inline-flex items-center sm:justify-center gap-2 h-[38px] py-2 px-4 rounded-md text-[#6E7191] stroke-[#6E7191]">
                <i class="lab lab-delivered-orders"></i>
                <span class="capitalize text-sm">{{ $t('button.delivered_orders') }}</span>
            </button>
        </div>
        <div id="profile" class="profile-tabDiv active">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ $t('label.basic_info') }}</h3>
                </div>
                <div class="db-card-body">
                    <div class="row py-2">
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t('label.email') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ deliveryBoy.email }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t('label.phone') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{
                                    deliveryBoy.country_code + '' + deliveryBoy.phone
                                }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t('label.status') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">
                                    <span :class="statusClass(deliveryBoy.status)">{{
                                        enums.statusEnumArray[deliveryBoy.status]
                                    }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="security" class="profile-tabDiv">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ $t('label.change_password') }}</h3>
                </div>
                <div class="db-card-body">
                    <form @submit.prevent="changePassword">
                        <div class="form-row">
                            <div class="form-col-12 sm:form-col-6">
                                <label for="password" class="db-field-title required">{{ $t("label.password") }}</label>
                                <input v-model="password.props.form.password"
                                    v-bind:class="password.errors.password ? 'invalid' : ''" type="password" id="password"
                                    class="db-field-control" autocomplete="off" />
                                <small class="db-field-alert" v-if="password.errors.password">{{
                                    password.errors.password[0]
                                }}</small>
                            </div>

                            <div class="form-col-12 sm:form-col-6">
                                <label for="password" class="db-field-title required">
                                    {{ $t("label.confirm_password") }}
                                </label>
                                <input v-model="password.props.form.password_confirmation"
                                    v-bind:class="password.errors.password_confirmation ? 'invalid' : ''" type="password"
                                    id="confirm_password" class="db-field-control" autocomplete="off" />
                                <small class="db-field-alert" v-if="password.errors.password_confirmation">{{
                                    password.errors.password_confirmation[0]
                                }}</small>
                            </div>

                            <div class="form-col-12">
                                <div class="flex flex-wrap gap-3">
                                    <button type="submit" class="db-btn py-2 text-white bg-primary">
                                        <i class="lab lab-save"></i>
                                        <span>{{ $t("label.save") }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="address" class="profile-tabDiv">
            <DeliveryBoyAddressList :props="$route.params.id" />
        </div>
        <div id="orders" class="profile-tabDiv">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ $t('label.orders') }}</h3>
                </div>
                <div class="db-card-body">
                    <div class="row">
                        <div class="col-12 md:col-6 lg:col-6 xl:col-4" v-if="myOrders.length > 0" v-for="order in myOrders"
                            :key="order">
                            <div class="w-full rounded-lg py-2 px-3 flex items-center gap-5 border border-[#EFF0F6]">
                                <i class="lab lab-reserve lab-font-size-24 lab-font-color-2"></i>
                                <div class="w-full">
                                    <div class="flex items-center gap-4 mb-1">
                                        <p class="text-sm leading-6 font-rubik">{{ $t('label.order_id') }}: <span
                                                class="text-heading">#{{
                                                    order.order_serial_no
                                                }}</span>
                                        </p>
                                        <span
                                            class="py-0.5 px-2 rounded-full text-[10px] font-rubik leading-4 capitalize text-heading bg-[#BDEFFF]"
                                            :class="orderStatusClass(order.status)">
                                            {{ order.status_name }}</span>
                                    </div>
                                    <p class="text-xs font-light font-rubik mb-0.5">{{ order.order_items }} {{
                                        $t("label.items")
                                    }}</p>
                                    <p class="text-xs font-light font-rubik mb-1">{{ order.order_datetime }}</p>
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm leading-6 font-rubik capitalize text-heading">{{
                                            $t("label.total")
                                        }}: <span class="font-medium">{{ order.total_currency_price }}</span></p>
                                        <router-link
                                            :to="{ name: 'admin.delivery-boys.order.details', params: { id: $route.params.id, orderId: order.id } }"
                                            class="text-[10px] leading-4 font-medium font-rubik flex items-center gap-1.5 text-primary">
                                            {{ $t("label.see_order_details") }}
                                            <i class="lab lab-arrow-right lab-font-size-13"></i>
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-6">
                    <PaginationSMBox :pagination="orderPagination" :method="orderLists" />
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <PaginationTextComponent :props="{ page: orderPage }" />
                        <PaginationBox :pagination="orderPagination" :method="orderLists" />
                    </div>
                </div>
            </div>
        </div>
        <div id="deliveredOrders" class="profile-tabDiv">
            <DeliveredOrderList :props="$route.params.id" />
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import statusEnum from "../../../enums/modules/statusEnum";
import labelEnum from "../../../enums/modules/labelEnum";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";
import DeliveryBoyAddressList from "./address/DeliveryBoyAddressList";
import DeliveredOrderList from "./deliveredOrder/DeliveredOrderList";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent";
import PaginationBox from "../components/pagination/PaginationBox";
import PaginationSMBox from "../components/pagination/PaginationSMBox";

export default {
    name: "DeliveryBoyShowComponent",
    components: {
        DeliveryBoyAddressList,
        DeliveredOrderList,
        LoadingComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                statusEnum: statusEnum,
                labelEnum: labelEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
            password: {
                props: {
                    form: {
                        password: "",
                        password_confirmation: "",
                    },
                },
                errors: {}
            },
            defaultImage: null,
            previewImage: null,
            uploadButton: true,
            resetButton: false,
            saveButton: false,
            search: {
                paginate: 1,
                page: 1,
                per_page: 9,
                order_column: 'id',
            }
        };
    },
    computed: {
        deliveryBoy: function () {
            return this.$store.getters["deliveryBoy/show"];
        },
        myOrders: function () {
            return this.$store.getters['deliveryBoy/myOrders'];
        },
        orderPagination: function () {
            return this.$store.getters["deliveryBoy/orderPagination"];
        },
        orderPage: function () {
            return this.$store.getters["deliveryBoy/orderPage"];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("deliveryBoy/show", this.$route.params.id).then((res) => {
            this.defaultImage = res.data.data.image;
            this.previewImage = res.data.data.image;
            this.loading.isActive = false;
        }).catch((error) => {
            this.loading.isActive = false;
        });
        this.orderLists();
    },
    methods: {
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        orderStatusClass: function (status) {
            return appService.orderStatusClass(status);
        },
        changePreviewImage: function (e) {
            if (e.target.files[0]) {
                this.previewImage = URL.createObjectURL(e.target.files[0]);
                this.saveButton = true;
                this.resetButton = true;
            }
        },
        resetPreviewImage: function () {
            this.$refs.imageProperty.value = null;
            this.previewImage = this.defaultImage;
            this.saveButton = false;
            this.resetButton = false;
        },
        orderLists: function (page = 1) {
            this.loading.isActive = true;
            this.search.page = page;
            this.$store.dispatch('deliveryBoy/myOrders', {
                id: this.$route.params.id,
                search: this.search
            }).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        saveImage: function () {
            if (this.$refs.imageProperty.files[0]) {
                try {
                    this.loading.isActive = true;
                    const formData = new FormData();
                    formData.append('image', this.$refs.imageProperty.files[0]);
                    this.$store.dispatch('deliveryBoy/changeImage', {
                        id: this.$route.params.id,
                        form: formData
                    }).then((res) => {
                        alertService.success(this.$t('message.photo_update'));
                        this.defaultImage = res.data.data.image;
                        this.previewImage = res.data.data.image;
                        this.$refs.imageProperty.value = null;
                        this.saveButton = false;
                        this.resetButton = false;
                        this.loading.isActive = false;
                    }).catch((err) => {
                        this.loading.isActive = false;
                        this.imageErrors = err.response.data.errors;
                    })
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }
        },
        changePassword: function () {
            try {
                const tempId = this.$store.getters["deliveryBoy/temp"].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch("deliveryBoy/changePassword", {
                    form: this.password.props.form,
                    id: this.$route.params.id
                }).then((res) => {
                    this.loading.isActive = false;
                    alertService.successInfo(tempId === null ? 0 : 1, this.$t("message.password_update"));
                    this.password.props.form = {
                        password: "",
                        password_confirmation: "",
                    };
                    this.password.errors = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.password.errors = err.response.data.errors;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    }
};
</script>
