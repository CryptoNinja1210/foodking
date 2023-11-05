<template>
    <LoadingComponent :props="loading" />

    <div class="md:w-[calc(100%-340px)] lg:w-[calc(100%-320px)] xl:w-[calc(100%-377px)]">
        <form @submit.prevent="search" class="flex items-center w-full h-[38px] leading-[38px] mb-4 rounded-lg bg-white">
            <input type="text" v-model="props.search.name" :placeholder="$t('label.search_by_menu_item')"
                class="w-full px-5 rounded-tl-lg rounded-bl-lg border placeholder:text-xs placeholder:font-rubik placeholder:text-[#A0A3BD] border-[#EFF0F6]">
            <button type="submit" class="flex-shrink-0 w-[38px] h-full text-center rounded-tr-lg rounded-br-lg bg-primary">
                <i class="lab lab-search-normal text-white"></i>
            </button>
        </form>

        <div class="swiper pos-menu-swiper mb-6" v-if="categories.length > 1">
            <div class="swiper-wrapper">
                <Carousel :settings="settings" :breakpoints="breakpoints">
                    <Slide class="swiper-slide" v-for="(category, index) in categories" :key="category"
                        :class="category.id === props.search.item_category_id || (category.id === 0 && props.search.item_category_id ==='') ? 'pos-group' : ''">
                        <router-link v-if="index === 0" to="#" @click.prevent="allCategory"
                            class="swiper-slide w-28 flex flex-col items-center text-center gap-4 py-4 px-3 rounded-lg border-b-2 border-transparent transition hover:bg-[#FFEDF4] hover:border-primary bg-white">
                            <img class="h-7 drop-shadow-category" :src="category.thumb" alt="category">
                            <h3 class="text-xs leading-[16px] font-medium font-rubik">{{ category.name }}</h3>
                        </router-link>
                        <router-link v-else to="#" @click.prevent="setCategory(category.id)"
                            class="swiper-slide w-28 flex flex-col items-center text-center gap-4 py-4 px-3 rounded-lg border-b-2 border-transparent transition hover:bg-[#FFEDF4] hover:border-primary bg-white">
                            <img class="h-7 drop-shadow-category" :src="category.thumb" alt="category">
                            <h3 class="text-xs leading-[16px] font-medium font-rubik">{{ category.name }}</h3>
                        </router-link>
                    </Slide>
                </Carousel>
            </div>
        </div>
        <ItemComponent :items="items" />
    </div>

    <div
        class="db-pos-cartDiv fixed top-0 right-0 w-full h-screen rounded-none z-50 md:z-10 md:top-[85px] md:right-5 md:w-[322px] lg:w-[305px] xl:w-[360px] md:h-[calc(100vh-85px)] md:rounded-lg overflow-y-auto thin-scrolling bg-white">
        <div class="p-4">
            <div class="md:hidden text-right mb-3">
                <button class="db-pos-cartCls">
                    <i class="lab-close-circle-line font-fill-danger lab-font-size-24"></i>
                </button>
            </div>
            <div class="db-field mb-3">
                <vue-select class="db-field-control text-sm rounded-lg appearance-none text-heading border-[#D9DBE9]"
                    id="customer" v-model="checkoutProps.form.customer_id" :options="customers" label-by="name"
                    value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                    :placeholder="$t('label.select_customer')" :search-placeholder="$t('label.search_customer')" />
            </div>
            <div class="db-field">
                <input class="db-field-control text-sm rounded-lg appearance-none text-heading border-[#D9DBE9]" id="token"
                    v-model="checkoutProps.form.token" :placeholder="$t('label.token_no')" />
            </div>
        </div>
        <table class="w-full">
            <thead class="bg-[#FFEDF4]">
                <tr class="h-9">
                    <th class="capitalize text-xs font-normal font-rubik text-left pl-3 text-heading"></th>
                    <th class="capitalize text-xs font-normal font-rubik text-left px-3 text-heading">
                        {{ $t('label.item') }}
                    </th>
                    <th class="capitalize text-xs font-normal font-rubik text-left px-3 text-heading">
                        {{ $t('label.qty') }}
                    </th>
                    <th class="capitalize text-xs font-normal font-rubik text-left px-3 text-heading">
                        {{ $t('label.price') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(cart, index) in carts">
                    <td class="pl-3 py-3 last:pr-3 align-top border-b border-[#EFF0F6]">
                        <button @click.prevent="deleteCartItem(index)">
                            <i class="lab lab-trash-line-2 font-fill-danger"></i>
                        </button>
                    </td>
                    <td class="pl-3 py-3 last:pr-3 align-top border-b border-[#EFF0F6]">
                        <h3 class="capitalize text-xs font-rubik text-[#2E2F38]">{{ cart.name }}</h3>
                        <p v-if="Object.keys(cart.item_variations.variations).length !== 0">
                            <span v-for="(variation, variationName) in cart.item_variations.names">
                                <span class="capitalize text-[10px] leading-4 font-rubik text-heading">{{
                                    variationName
                                }}:
                                    &nbsp;</span>
                                <span class="capitalize text-[10px] leading-4 font-rubik">{{ variation }}, &nbsp;</span>
                            </span>
                        </p>
                        <ul v-if="cart.item_extras.extras.length > 0 || cart.instruction !== ''">
                            <li v-if="cart.item_extras.extras.length > 0" class="leading-4">
                                <span class="capitalize text-[10px] leading-4 font-rubik text-heading">
                                    {{ $t('label.extras') }}:
                                </span>
                                <p class="capitalize text-[10px] leading-4 font-rubik">
                                    <span v-for="extra in cart.item_extras.names">
                                        {{ extra }}, &nbsp;
                                    </span>
                                </p>
                            </li>
                            <li v-if="cart.instruction !== ''" class="leading-4">
                                <span class="capitalize text-[10px] leading-4 font-rubik text-heading">
                                    {{ $t('label.instruction') }}:
                                </span>
                                <span class="capitalize text-[10px] leading-4 font-rubik">
                                    {{ cart.instruction }}
                                </span>
                            </li>
                        </ul>
                    </td>
                    <td class="pl-3 py-3 last:pr-3 align-top border-b border-[#EFF0F6]">
                        <div class="flex items-center indec-group">
                            <button @click.prevent="cartQuantityDecrement(index)"
                                :class="cart.quantity === 1 ? 'fa-trash-can' : 'fa-minus'"
                                class="fa-solid text-[10px] w-[18px] h-[18px] leading-4 text-center rounded-full border transition text-primary border-primary hover:bg-primary hover:text-white indec-minus"></button>
                            <input v-on:keypress="onlyNumber($event)" v-on:keyup="cartQuantityUp(index, $event)"
                                type="number" :value="cart.quantity"
                                class="text-center w-7 text-xs font-semibold text-heading indec-value">
                            <button @click.prevent="cartQuantityIncrement(index)"
                                class="fa-solid fa-plus text-[10px] w-[18px] h-[18px] leading4 text-center rounded-full border transition text-primary border-primary hover:bg-primary hover:text-white indec-plus"></button>
                        </div>
                    </td>
                    <td class="pl-3 py-3 last:pr-3 align-top border-b border-[#EFF0F6] text-xs font-rubik text-heading">
                        {{
                            currencyFormat(cart.total, setting.site_digit_after_decimal_point,
                                setting.site_default_currency_symbol, setting.site_currency_position)
                        }}
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="p-4">
            <div class="flex h-[38px]" v-if="carts.length > 0">
                <div class="db-field-down-arrow">
                    <select v-model="discountType"
                        class="w-[120px] h-full text-sm font-rubik rounded-tl rounded-bl appearance-none border pl-3 text-heading border-[#EFF0F6]">
                        <option :value="discountTypeEnum.PERCENTAGE">{{ $t("label.percentage") }}</option>
                        <option :value="discountTypeEnum.FIXED">{{ $t("label.fixed") }}</option>
                    </select>
                </div>
                <input v-model="discount" type="text" :placeholder="$t('label.add_discount')"
                    class="w-full h-full border-t border-b px-3 border-[#EFF0F6]">
                <button @click.prevent="applyDiscount" type="submit"
                    class="flex-shrink-0 w-16 h-full text-sm font-medium font-rubik capitalize rounded-tr rounded-br text-white bg-[#008BBA]">
                    {{ $t('button.apply') }}
                </button>
            </div>
            <small class="db-field-alert" v-if="discountErrorMessage">{{ discountErrorMessage }}</small>
            <ul class="flex flex-col gap-1.5 mb-4 mt-4">
                <li class="flex items-center justify-between">
                    <span class="text-sm font-rubik capitalize leading-6 text-[#2E2F38]">
                        {{ $t("label.sub_total") }}
                    </span>
                    <span class="text-sm font-rubik capitalize leading-6 text-[#2E2F38]">
                        {{
                            currencyFormat(subtotal, setting.site_digit_after_decimal_point,
                                setting.site_default_currency_symbol, setting.site_currency_position)
                        }}
                    </span>
                </li>
                <li class="flex items-center justify-between">
                    <span class="text-sm font-rubik capitalize leading-6">{{ $t("label.discount") }}</span>
                    <span class="text-sm font-rubik capitalize leading-6">{{
                        currencyFormat(posDiscount,
                            setting.site_digit_after_decimal_point, setting.site_default_currency_symbol,
                            setting.site_currency_position)
                    }}</span>
                </li>
                <li class="flex items-center justify-between">
                    <span class="text-sm font-medium font-rubik capitalize leading-6 text-[#2E2F38]">
                        {{ $t("label.total") }}
                    </span>
                    <span class="text-sm font-medium font-rubik capitalize leading-6 text-[#2E2F38]">
                        {{
                            currencyFormat(subtotal - posDiscount,
                                setting.site_digit_after_decimal_point, setting.site_default_currency_symbol,
                                setting.site_currency_position)
                        }}
                    </span>
                </li>
            </ul>
            <div class="flex items-center justify-center gap-6" v-if="carts.length > 0">
                <button @click.prevent="resetCart"
                    class="capitalize text-sm font-medium leading-6 font-rubik w-full text-center rounded-3xl py-2 text-white bg-[#FB4E4E]">
                    {{ $t('button.cancel') }}
                </button>
                <button @click.prevent="orderSubmit"
                    class="capitalize text-sm font-medium leading-6 font-rubik w-full text-center rounded-3xl py-2 text-white bg-[#1AB759]">
                    {{ $t('button.order') }}
                </button>
            </div>
        </div>
    </div>

    <button
        class="db-pos-cartBtn fixed md:hidden bottom-0 z-10 left-0 w-full h-14 py-4 text-center flex items-center justify-center shadow-xl-top gap-3 bg-primary">
        <i class="lab lab-bag-2 lab-font-size-13 text-white"></i>
        <span class="text-base font-medium font-rubik text-white">
            {{ totalItems() }} {{ $t('label.items') }} - {{
                currencyFormat(subtotal - posDiscount,
                    setting.site_digit_after_decimal_point, setting.site_default_currency_symbol,
                    setting.site_currency_position)
            }}
        </span>
    </button>

    <ReceiptComponent :order="order" />
</template>
<script>
import LoadingComponent from "../components/LoadingComponent";
import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';
import ItemComponent from "./ItemComponent";
import sourceEnum from "../../../enums/modules/sourceEnum";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";
import isAdvanceOrderEnum from "../../../enums/modules/isAdvanceOrderEnum";
import statusEnum from "../../../enums/modules/statusEnum";
import roleEnum from "../../../enums/modules/roleEnum";
import appService from "../../../services/appService";
import discountTypeEnum from "../../../enums/modules/discountTypeEnum";
import alertService from "../../../services/alertService";
import ReceiptComponent from "./ReceiptComponent";

export default {
    name: "PosComponent",
    components: {
        ReceiptComponent,
        LoadingComponent,
        ItemComponent,
        Carousel,
        Slide,
        Pagination,
        Navigation,
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            order: {},
            discount: null,
            checkoutProps: {
                form: {
                    branch_id: null,
                    subtotal: 0,
                    token: "",
                    customer_id: null,
                    discount: 0,
                    delivery_charge: 0,
                    delivery_time: null,
                    total: 0,
                    order_type: orderTypeEnum.POS,
                    is_advance_order: isAdvanceOrderEnum.NO,
                    source: sourceEnum.POS,
                    address_id: null,
                    coupon_id: null,
                    items: []
                }
            },
            props: {
                search: {
                    paginate: 0,
                    order_column: "id",
                    order_type: "asc",
                    name: "",
                    item_category_id: "",
                    status: statusEnum.ACTIVE
                },
            },
            categoryProps: {
                paginate: 0,
                order_column: "id",
                order_type: "asc",
                status: statusEnum.ACTIVE
            },
            settings: {
                itemsToShow: 6.2,
                wrapAround: false,
                snapAlign: "start"
            },
            breakpoints: {
                200: {
                    itemsToShow: 1.4,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                250: {
                    itemsToShow: 1.9,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                300: {
                    itemsToShow: 2.3,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                375: {
                    itemsToShow: 3,
                    wrapAround: true,
                    snapAlign: 'start',
                },
                540: {
                    itemsToShow: 4.3,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                700: {
                    itemsToShow: 5.2,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                768: {
                    itemsToShow: 3.2,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                830: {
                    itemsToShow: 3.6,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                900: {
                    itemsToShow: 4.3,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                960: {
                    itemsToShow: 5.3,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                1024: {
                    snapAlign: 'start',
                    itemsToShow: 3.5,
                    wrapAround: false,
                },
                1100: {
                    snapAlign: 'start',
                    itemsToShow: 4.1,
                    wrapAround: false,
                },
                1180: {
                    snapAlign: 'start',
                    itemsToShow: 4.8,
                    wrapAround: false,
                },
                1280: {
                    snapAlign: 'start',
                    itemsToShow: 5.2,
                    wrapAround: false,
                },
                1400: {
                    snapAlign: 'start',
                    itemsToShow: 5.8,
                    wrapAround: false,
                },
                1600: {
                    snapAlign: 'start',
                    itemsToShow: 6.8,
                    wrapAround: false,
                },
                1700: {
                    snapAlign: 'start',
                    itemsToShow: 7.8,
                    wrapAround: false,
                },
                1800: {
                    snapAlign: 'start',
                    itemsToShow: 8.8,
                    wrapAround: false,
                },
                1920: {
                    snapAlign: 'start',
                    itemsToShow: 9.8,
                    wrapAround: false,
                },
                2000: {
                    snapAlign: 'start',
                    itemsToShow: 10.8,
                    wrapAround: false,
                },
                2100: {
                    snapAlign: 'start',
                    itemsToShow: 11.8,
                    wrapAround: false,
                }
            },
            statusEnum: statusEnum,
            discountTypeEnum: discountTypeEnum,
            discountType: discountTypeEnum.PERCENTAGE,
            discountErrorMessage: "",
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        categories: function () {
            return this.$store.getters["posCategory/lists"];
        },
        items: function () {
            return this.$store.getters["item/lists"];
        },
        customers: function () {
            return this.$store.getters['user/lists'];
        },
        carts: function () {
            return this.$store.getters['posCart/lists'];
        },
        subtotal: function () {
            return this.$store.getters['posCart/subtotal'];
        },
        posDiscount: function () {
            return this.$store.getters['posCart/discount'];
        },
    },
    mounted() {
        this.itemCategories();
        this.itemList();
        try {
            this.loading.isActive = true;
            this.$store.dispatch("defaultAccess/show").then((res) => {
                this.checkoutProps.form.branch_id = res.data.data.branch_id;
            }).catch((err) => {
                this.loading.isActive = false;
            });

            this.loading.isActive = true;
            this.$store.dispatch('user/lists', {
                order_column: 'id',
                order_type: 'asc',
                status: statusEnum.ACTIVE,
                role_id: roleEnum.CUSTOMER
            }).then((res) => {
                this.checkoutProps.form.customer_id = res.data.data[0].id;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });

            this.loading.isActive = true;
            this.$store.dispatch("company/lists").then((res) => {
                this.company.name = res.data.data.company_name;
                this.company.email = res.data.data.company_email;
                this.company.phone = res.data.data.company_phone;
                this.company.address = res.data.data.company_address;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        } catch (err) {
            this.loading.isActive = false;
        }
    },
    methods: {
        onlyNumber: function (e) {
            return appService.onlyNumber(e);
        },
        currencyFormat: function (amount, decimal, currency, position) {
            return appService.currencyFormat(amount, decimal, currency, position);
        },
        search: function () {
            this.itemList();
        },
        allCategory: function () {
            this.props.search.name = "";
            this.props.search.item_category_id = "";
            this.itemList();
        },
        itemCategories: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch("posCategory/lists", this.categoryProps).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        itemList: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch("item/lists", this.props.search).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        setCategory: function (id) {
            this.props.search.item_category_id = id;
            this.itemList();
        },
        cartQuantityUp: function (id, e) {
            if (e.target.value > 0) {
                this.$store.dispatch('posCart/quantity', { id: id, status: e.target.value }).then().catch();
            }
        },
        cartQuantityIncrement: function (id) {
            this.$store.dispatch('posCart/quantity', { id: id, status: "increment" }).then().catch();
        },
        cartQuantityDecrement: function (id) {
            this.$store.dispatch('posCart/quantity', { id: id, status: "decrement" }).then().catch();
        },
        deleteCartItem: function (id) {
            this.$store.dispatch('posCart/deleteCartItem', { id: id, status: "decrement" }).then().catch();
        },
        applyDiscount: function () {
            this.discountErrorMessage = "";
            if (this.discountType == discountTypeEnum.FIXED) {
                if (this.subtotal < this.discount) {
                    this.discountErrorMessage = this.$t('message.discount_fixed_error_message');
                } else {
                    this.checkoutProps.form.discount = parseFloat(this.discount).toFixed(this.setting.site_digit_after_decimal_point);
                    this.$store.dispatch('posCart/discount', this.checkoutProps.form.discount).then().catch();
                }

            } else {
                if (this.discount > 100) {
                    this.discountErrorMessage = this.$t('message.discount_error_message');
                } else {

                    this.checkoutProps.form.discount = parseFloat((this.subtotal * this.discount) / 100).toFixed(this.setting.site_digit_after_decimal_point);
                    this.$store.dispatch('posCart/discount', this.checkoutProps.form.discount).then().catch();

                }
            }
        },
        resetCart: function () {
            this.$store.dispatch('posCart/resetCart').then(res => {
            }).catch();
        },
        orderSubmit: function () {
            this.loading.isActive = true;
            this.checkoutProps.form.subtotal = this.subtotal;
            this.checkoutProps.form.total = parseFloat(this.subtotal - this.checkoutProps.form.discount).toFixed(this.setting.site_digit_after_decimal_point);
            this.checkoutProps.form.items = [];
            _.forEach(this.carts, (item, index) => {
                let item_variations = [];
                if (Object.keys(item.item_variations.variations).length > 0) {
                    _.forEach(item.item_variations.variations, (value, index) => {
                        item_variations.push({
                            "id": value,
                            "item_id": item.item_id,
                            "item_attribute_id": index,
                        });
                    });
                }

                if (Object.keys(item.item_variations.names).length > 0) {
                    let i = 0;
                    _.forEach(item.item_variations.names, (value, index) => {
                        item_variations[i].variation_name = index;
                        item_variations[i].name = value;
                        i++;
                    });
                }

                let item_extras = [];
                if (item.item_extras.extras.length) {
                    _.forEach(item.item_extras.extras, (value) => {
                        item_extras.push({
                            id: value,
                            item_id: item.item_id,
                        });
                    });
                }

                if (item.item_extras.names.length) {
                    let i = 0;
                    _.forEach(item.item_extras.names, (value) => {
                        item_extras[i].name = value;
                        i++;
                    });
                }

                this.checkoutProps.form.items.push({
                    item_id: item.item_id,
                    item_price: item.convert_price,
                    branch_id: this.checkoutProps.form.branch_id,
                    instruction: item.instruction,
                    quantity: item.quantity,
                    discount: item.discount,
                    total_price: item.total,
                    item_variation_total: item.item_variation_total,
                    item_extra_total: item.item_extra_total,
                    item_variations: item_variations,
                    item_extras: item_extras
                });
            });
            this.checkoutProps.form.items = JSON.stringify(this.checkoutProps.form.items);

            this.$store.dispatch("defaultAccess/show").then((res) => {
                this.checkoutProps.form.branch_id = res.data.data.branch_id;
                this.$store.dispatch('posOrder/save', this.checkoutProps.form).then(orderResponse => {
                    this.checkoutProps.form.token = "";
                    this.checkoutProps.form.subtotal = null;
                    this.checkoutProps.form.discount = 0;
                    this.checkoutProps.form.delivery_time = null;
                    this.checkoutProps.form.total = 0;
                    this.checkoutProps.form.order_type = orderTypeEnum.POS;
                    this.checkoutProps.form.is_advance_order = isAdvanceOrderEnum.NO;
                    this.checkoutProps.form.source = sourceEnum.POS;
                    this.checkoutProps.form.address_id = null;
                    this.checkoutProps.form.coupon_id = null;
                    this.checkoutProps.form.items = [];
                    this.discount = null;
                    this.discountType = discountTypeEnum.PERCENTAGE;

                    this.$store.dispatch('posCart/resetCart').then(res => {
                        this.loading.isActive = false;
                    }).catch();
                    this.$store.dispatch('posOrder/show', orderResponse.data.data.id).then(res => {
                        this.order = res.data.data;
                        this.loading.isActive = false;
                    }).catch((error) => {
                        this.loading.isActive = false;
                        alertService.error(error.response.data.message);
                    });
                    appService.modalShow('#receiptModal');
                }).catch((err) => {
                    this.loading.isActive = false;
                    if (typeof err.response.data.errors === 'object') {
                        _.forEach(err.response.data.errors, (error) => {
                            alertService.error(error[0]);
                        });
                    }
                });
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        totalItems: function () {
            if (this.carts.length > 0) {
                let totalItem = 0;
                this.carts.forEach(cart => {
                    totalItem += cart.quantity;
                });
                return totalItem;
            }
        }
    }
}
</script>
