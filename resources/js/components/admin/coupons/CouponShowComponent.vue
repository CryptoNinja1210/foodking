<template>
    <LoadingComponent :props="loading" />
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ $t('label.coupon') }}</h3>
            </div>
            <div class="db-card-body">
                <div class="row">
                    <div class="col-12 sm:col-5">
                        <img class="db-image" alt="coupon" :src="coupon.image">
                    </div>
                    <div class="col-12 sm:col-7 md:pl-8">
                        <ul class="db-list single">
                            <li class="db-list-item">
                                <span class="db-list-item-title">{{ $t('label.name') }}</span>
                                <span class="db-list-item-text">{{ coupon.name }}</span>
                            </li>
                            <li class="db-list-item">
                                <span class="db-list-item-title">{{ $t('label.code') }}</span>
                                <span class="db-list-item-text">{{ coupon.code }}</span>
                            </li>
                            <li class="db-list-item">
                                <span class="db-list-item-title">{{ $t('label.discount') }}</span>
                                <span class="db-list-item-text">{{ coupon.flat_discount }}</span>
                            </li>
                            <li class="db-list-item">
                                <span class="db-list-item-title">{{ $t('label.discount_type') }}</span>
                                <span class="db-list-item-text">
                                    {{ enums.taxTypeEnumArray[coupon.discount_type] }}
                                </span>
                            </li>
                            <li class="db-list-item">
                                <span class="db-list-item-title">{{ $t('label.start_date') }}</span>
                                <span class="db-list-item-text">{{ coupon.convert_start_date }}</span>
                            </li>
                            <li class="db-list-item">
                                <span class="db-list-item-title">{{ $t('label.end_date') }}</span>
                                <span class="db-list-item-text">{{ coupon.convert_end_date }}</span>
                            </li>
                            <li class="db-list-item">
                                <span class="db-list-item-title">{{ $t('label.minimum_order') }}</span>
                                <span class="db-list-item-text">{{ coupon.minimum_order_flat_amount }}</span>
                            </li>
                            <li class="db-list-item">
                                <span class="db-list-item-title">{{ $t('label.maximum_discount') }}</span>
                                <span class="db-list-item-text">{{ coupon.maximum_flat_discount }}</span>
                            </li>
                            <li class="db-list-item">
                                <span class="db-list-item-title">{{ $t('label.limit_per_user') }}</span>
                                <span class="db-list-item-text" v-if="coupon.limit_per_user == 0">{{ $t('label.unlimited')
                                }}</span>
                                <span class="db-list-item-text" v-else>{{ coupon.limit_per_user }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";
import taxTypeEnum from "../../../enums/modules/taxTypeEnum";

export default {
    name: "CouponShowComponent",
    components: {
        LoadingComponent
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            enums: {
                taxTypeEnum: taxTypeEnum,
                taxTypeEnumArray: {
                    [taxTypeEnum.FIXED]: this.$t("label.fixed"),
                    [taxTypeEnum.PERCENTAGE]: this.$t("label.percentage"),
                },
            }
        }
    },
    computed: {
        coupon: function () {
            return this.$store.getters['coupon/show'];
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch('coupon/show', this.$route.params.id).then(res => {
            this.loading.isActive = false;
        }).catch((error) => {
            this.loading.isActive = false;
        });
    },
    methods: {
        taxTypeClass: function (status) {
            return appService.taxTypeClass(status);
        },
    }
}

</script>
