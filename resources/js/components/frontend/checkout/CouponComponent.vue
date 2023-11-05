<template>
    <div v-if="Object.keys(cartCoupon).length !== 0" class="flex items-center gap-3 mb-4 h-[50px] rounded-lg shadow-coupon">
        <div class="flex items-center gap-2 h-full w-[76px] px-2 rounded-l-lg flex-shrink-0 text-white bg-[#1AB759]">
            <i class="lab lab-tick-circle text-sm"></i>
            <span class="capitalize break-words tracking-wide text-[10px] leading-4">{{
                $t('label.offer_applied')
            }}</span>
        </div>
        <div class="w-full rounded-r-lg relative bg-white">
            <h3 class="text-xs uppercase font-medium mb-1">{{ cartCoupon.code }}</h3>
            <p class="text-[10px] first-letter:capitalize leading-[14px]">{{
                $t('message.you_saved', {
                    amount:
                        cartCoupon.currency_discount
                })
            }}</p>
            <button @click.prevent="destroyCoupon" type="button"
                class="lab lab-delete-bold text-sm absolute top-1/2 right-5 -translate-y-1/2 text-[#FB4E4E]"></button>

        </div>
    </div>

    <button v-else @click.prevent="couponModalShow"
        class="mb-4 w-full flex items-center gap-3.5 py-2 px-4 rounded-lg shadow-coupon text-heading bg-white transition hover:text-primary"
        data-modal="#coupon-modal" type="button">
        <i class="lab-ticket-discount lab-font-size-30 text-3xl text-[#008BBA]"></i>
        <span class="text-xs font-medium text-left flex-auto">{{ $t('label.select_apply') }}<small
                class="block text-[9px]">{{ $t('message.get_discount') }}</small></span>
        <i class="lab-arrow-right lab-font-bold lab-font-size-22 text-lg text-[#008BBA]"></i>
    </button>

    <div id="coupon-modal" class="modal coupon-modal ff-modal">
        <div class="modal-dialog max-w-[360px]">
            <div class="modal-header border-none pb-0">
                <h3 class="capitalize font-medium">{{ $t('label.coupon_code') }}</h3>
                <button @click.prevent="couponModalHide" class="modal-close fa-regular fa-circle-xmark"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="couponChecking" class="flex w-full h-12">
                    <input :class="error ? 'invalid' : ''" v-model="couponProps.form.code" type="text"
                        class="w-full h-full px-3 rounded-l-lg border border-[#D9DBE9]">
                    <button
                        class="py-3 px-5 w-fit flex-shrink-0 rounded-r-lg font-medium capitalize text-white bg-[#1AB759]">
                        {{ $t('button.apply') }}
                    </button>
                </form>
                <small class="db-field-alert" v-if="error">{{ error }}</small>
                <div class="mb-4 mt-4">
                    <h3 class="font-medium">{{ $t('label.offer_for_you') }}</h3>
                    <h4 class="text-xs">{{ $t('label.coupon_for_you') }}</h4>
                </div>

                <div v-if="coupons.length > 0" v-for="coupon in coupons" :key="coupon"
                    class="relative rounded-lg shadow-coupon mb-4 bg-white">
                    <div class="p-2 border-b border-[#EFF0F6]">
                        <h3 class="py-1 px-2 rounded font-medium text-xs w-fit mb-2 bg-[#FFDB1F]">{{
                            $t('label.code')
                        }}: {{ coupon.code }}</h3>
                        <h4 class="text-xs">
                            {{
                                $t('message.discount_off', {
                                    discount: coupon.flat_discount,
                                    type: (coupon.discount_type === taxTypeEnum.PERCENTAGE ? '%' :
                                        setting.site_default_currency_symbol)
                                })
                            }}
                        </h4>
                    </div>
                    <button @click.prevent="appCouponButton(coupon)" type="button"
                        class="absolute top-0 right-0 text-xs py-1 px-2 rounded-tr-lg rounded-bl-lg capitalize text-white bg-primary">
                        {{ $t('button.apply') }}
                    </button>
                    <div v-if="coupon.description">
                        <button @click.prevent="termStatus(coupon.id)" type="button"
                            class="text-[11px] text-left w-full px-2 p-1 text-primary">
                            {{ $t('label.terms_and_conditions') }}
                        </button>
                        <div :id="'terms-' + coupon.id" class="h-0 overflow-hidden transition">
                            <div class="pl-4 pr-4 pb-1 text-[11px] text-heading">
                                {{ coupon.description }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import appService from "../../../services/appService";
import alertService from "../../../services/alertService";
import taxTypeEnum from "../../../enums/modules/taxTypeEnum";


export default {
    name: "CouponComponent",
    props: {
        props: Object,
        coupon: Function,
    },
    data() {
        return {
            taxTypeEnum: taxTypeEnum,
            couponProps: {
                form: {
                    code: null
                }
            },
            error: ""
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        coupons: function () {
            return this.$store.getters['frontendCoupon/lists'];
        },
        cartCoupon: function () {
            return this.$store.getters['frontendCart/coupon'];
        }
    },
    mounted() {
        this.$store.dispatch("frontendCoupon/lists", {}).then().catch();
        this.coupon(this.cartCoupon);
    },
    methods: {
        couponModalShow: function () {
            this.error = "";
            this.couponProps.form.code = null;
            appService.modalShow('.coupon-modal');
        },
        couponModalHide: function () {
            appService.modalHide('.coupon-modal');
        },
        termStatus: function (id) {
            const element = document.getElementById('terms-' + id);
            if (element.classList.contains('h-0')) {
                element.classList.remove('h-0');
            } else {
                element.classList.add('h-0');
            }
        },
        appCouponButton(coupon) {
            this.couponProps.form.code = coupon.code;
        },
        couponChecking() {
            this.$store.dispatch('frontendCoupon/checking', {
                total: this.props.total,
                code: this.couponProps.form.code
            }).then(res => {
                this.error = "";
                this.coupon(res.data.data);
                this.$store.dispatch("frontendCart/coupon", res.data.data);
                this.couponModalHide();
                alertService.success(this.$t('message.coupon_add'));
            }).catch((err) => {
                this.error = err.response.data.message;
            });
        },
        destroyCoupon() {
            this.$store.dispatch("frontendCart/destroyCoupon").then(res => {
                this.coupon({});
                alertService.success(this.$t('message.coupon_delete'));
            }).catch((err) => {
                alertService.error(err);
            });
        }
    }
}
</script>
