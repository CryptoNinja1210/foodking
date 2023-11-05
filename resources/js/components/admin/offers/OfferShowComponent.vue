<template>
    <LoadingComponent :props="loading" />


    <div class="col-12">
        <div class="grid grid-cols-1 sm:grid-cols-3 mb-4 sm:mb-0">
            <button type="button" class="db-tabBtn active" data-tab="#information"><i
                    class="lab lab-information lab-font-size-16"></i>{{
                        $t('label.information') }}
            </button>
            <button type="button" class="db-tabBtn" data-tab="#image"><i class="lab lab-image lab-font-size-16"></i>
                {{ $t('label.images') }}
            </button>
            <button type="button" class="db-tabBtn" data-tab="#item"><i class="lab lab-bag-2 lab-font-size-16"></i>
                {{ $t('label.items') }}
            </button>
        </div>
        <div class="db-tabDiv active" id="information">
            <div class="row py-2">
                <div class="col-12 sm:col-6 !py-1.5">
                    <div class="db-list-item p-0">
                        <span class="db-list-item-title w-full sm:w-1/2">{{ $t('label.name') }}</span>
                        <span class="db-list-item-text w-full sm:w-1/2">{{ offer.name }}</span>
                    </div>
                </div>
                <div class="col-12 sm:col-6 !py-1.5">
                    <div class="db-list-item p-0">
                        <span class="db-list-item-title w-full sm:w-1/2">{{ $t('label.discount_percentage') }}</span>
                        <span class="db-list-item-text w-full sm:w-1/2">{{ offer.flat_amount }} %</span>
                    </div>
                </div>

                <div class="col-12 sm:col-6 !py-1.5">
                    <div class="db-list-item p-0">
                        <span class="db-list-item-title w-full sm:w-1/2">{{ $t('label.start_date') }}</span>
                        <span class="db-list-item-text w-full sm:w-1/2">{{ offer.convert_start_date }}</span>
                    </div>
                </div>

                <div class="col-12 sm:col-6 !py-1.5">
                    <div class="db-list-item p-0">
                        <span class="db-list-item-title w-full sm:w-1/2">{{ $t('label.end_date') }}</span>
                        <span class="db-list-item-text w-full sm:w-1/2">{{ offer.convert_end_date }}</span>
                    </div>
                </div>

                <div class="col-12 sm:col-6 !py-1.5">
                    <div class="db-list-item p-0">
                        <span class="db-list-item-title w-full sm:w-1/2">{{ $t('label.status') }}</span>
                        <span class="db-list-item-text">
                            <span :class="statusClass(offer.status)">{{
                                enums.statusEnumArray[offer.status]
                            }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="db-tabDiv" id="image">
            <div class="row py-2">
                <div class="col-12 sm:col-5">
                    <img class="db-image" alt="slider" :src="previewImage" />
                </div>
                <form @submit.prevent="saveImage">
                    <p class="mt-2">{{ $t('label.size') }}: (548px,140px)</p>
                    <div class="flex gap-3 md:gap-4 py-4">
                        <label for="photo"
                            class="db-btn relative cursor-pointer h-[38px] shadow-[0px_6px_10px_rgba(255,_0,_107,_0.24)] bg-primary text-white">
                            <i class="lab lab-upload-image"></i>
                            <span class="hidden sm:inline-block">{{
                                $t("button.upload_new_image")
                            }}</span>
                            <input v-if="uploadButton" @change="changePreviewImage" ref="imageProperty"
                                accept="image/png, image/jpeg, image/jpg" type="file" id="photo"
                                class="absolute top-0 left-0 w-full h-full -z-10 opacity-0" />
                        </label>
                        <button v-if="saveButton" type="submit"
                            class="db-btn h-[38px] shadow-[0px_6px_10px_rgba(26,_183,_89,_0.24)] text-white bg-[#1AB759]">
                            <i class="lab lab-tick-circle-2"></i>
                            <span class="hidden sm:inline-block">{{ $t("button.save") }}</span>
                        </button>
                        <button v-if="resetButton" @click="resetPreviewImage" type="button"
                            class="db-btn-outline h-[38px] shadow-[0px_6px_10px_rgba(251,_78,_78,_0.24)] !text-[#FB4E4E] !bg-white !border-[#FB4E4E]">
                            <i class="lab lab-reset"></i>
                            <span class="hidden sm:inline-block">{{ $t("button.reset") }}</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
        <div class="db-tabDiv" id="item">
            <OfferItemListComponent :offer="parseInt($route.params.id)" />
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";
import statusEnum from "../../../enums/modules/statusEnum";
import OfferItemListComponent from "./item/OfferItemListComponent";

export default {
    name: "OfferShowComponent",
    components: {
        LoadingComponent,
        OfferItemListComponent
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive")
                }
            },
            defaultImage: null,
            previewImage: null,
            uploadButton: true,
            resetButton: false,
            saveButton: false,
        }
    },
    computed: {
        offer: function () {
            return this.$store.getters['offer/show'];
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch('offer/show', this.$route.params.id).then(res => {
            this.defaultImage = res.data.data.image;
            this.previewImage = res.data.data.image;
            this.loading.isActive = false;
        }).catch((error) => {
            this.loading.isActive = false;
        });
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
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
        saveImage: function () {
            if (this.$refs.imageProperty.files[0]) {
                try {
                    this.loading.isActive = true;
                    const formData = new FormData();
                    formData.append("image", this.$refs.imageProperty.files[0]);
                    this.$store
                        .dispatch("offer/changeImage", {
                            id: this.$route.params.id,
                            form: formData,
                        })
                        .then((res) => {
                            alertService.success(this.$t("message.image_update"));
                            this.defaultImage = res.data.data.image;
                            this.previewImage = res.data.data.image;
                            this.$refs.imageProperty.value = null;
                            this.saveButton = false;
                            this.resetButton = false;
                            this.loading.isActive = false;
                        })
                        .catch((err) => {
                            this.loading.isActive = false;
                            this.imageErrors = err.response.data.errors;
                        });
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }
        },
    }
}

</script>
