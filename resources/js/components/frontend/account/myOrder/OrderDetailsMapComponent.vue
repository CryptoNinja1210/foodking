<template>
    <button v-if="order.order_type === enums.orderTypeEnum.TAKEAWAY" @click.prevent="mapModal" data-modal="#mapModal"
        type="button" class="w-8 h-8 rounded-full flex items-center justify-center bg-[#FFEDF4]">
        <i class="lab lab-map-navigate lab-font-size-16 text-primary"></i>
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
                            :location="{ lat: branch.latitude, lng: branch.longitude }" :position="mapPosition"
                            :setting="{ autocomplete: false, mouseEvent: false, currentLocation: false }" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import MapComponent from "../../../admin/components/MapComponent";
import appService from "../../../../services/appService";
import orderTypeEnum from "../../../../enums/modules/orderTypeEnum";

export default {
    name: "OrderDetailsMapComponent",
    props: {
        order: Object,
        branch: Object,
    },
    components: {
        MapComponent
    },
    data() {
        return {
            mapShow: false,
            mapKey: "branch",
            enums: {
                orderTypeEnum: orderTypeEnum
            }
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
