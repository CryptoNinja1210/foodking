<template>
    <button @click.prevent="mapModal" data-modal="#mapModal" type="button"
        class="w-[38px] h-[38px] flex items-center justify-center ml-auto flex-shrink-0 rounded bg-[#00749B]">
        <i class="lab lab-map-navigate lab-font-size-24 text-white"></i>
    </button>

    <div id="mapModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("label.address") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-col-12">
                        <MapComponent :key="mapKey" v-if="mapShow"
                            :location="{ lat: orderAddress.latitude, lng: orderAddress.longitude }" :position="mapPosition"
                            :setting="{ autocomplete: false, mouseEvent: false, currentLocation: false }" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import MapComponent from "../components/MapComponent";
import appService from "../../../services/appService";

export default {
    name: "OnlineOrderMapComponent",
    props: {
        orderAddress: Object,
    },
    components: {
        MapComponent
    },
    data() {
        return {
            mapShow: false,
            mapKey: "branch",
        }
    },
    methods: {
        mapModal: function () {
            this.mapShow = true;
            appService.modalShow('#mapModal');
        },
        reset: function () {
            appService.modalHide();
            this.mapShow = false;
        },
        mapPosition: function (e) {
        },
    }
}
</script>
