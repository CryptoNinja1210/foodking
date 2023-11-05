import {createStore} from "vuex";

import createPersistedState from "vuex-persistedstate";
import {auth} from "./modules/auth";
import {company} from "./modules/company";
import {itemCategory} from "./modules/itemCategory";
import {itemAttribute} from "./modules/itemAttribute";
import {slider} from "./modules/slider";
import {branch} from "./modules/branch";
import {offer} from "./modules/offer";
import {item} from "./modules/item";
import {itemVariation} from "./modules/itemVariation";
import {onlineOrder} from "./modules/onlineOrder";
import {tax} from "./modules/tax";
import {currency} from "./modules/currency";
import {mail} from "./modules/mail";
import {menuSection} from "./modules/menuSection";
import {page} from "./modules/page";
import {notification} from "./modules/notification";
import {pushNotification} from "./modules/pushNotification";
import {menuTemplate} from "./modules/menuTemplate";
import {coupon} from "./modules/coupon";
import {customer} from "./modules/customer";
import {otp} from "./modules/otp";
import {administrator} from "./modules/administrator";
import {deliveryBoy} from "./modules/deliveryBoy";
import {deliveryBoyAddress} from "./modules/deliveryBoyAddress";
import {defaultAccess} from "./modules/defaultAccess";
import {administratorAddress} from "./modules/administratorAddress";
import {customerAddress} from "./modules/customerAddress";
import {socialMedia} from "./modules/socialMedia";
import {license} from "./modules/license";
import {analytic} from "./modules/analytic";
import {analyticSection} from "./modules/analyticSection";
import {role} from "./modules/role";
import {permission} from "./modules/permission";
import {cookies} from './modules/cookies';
import {theme} from './modules/theme';
import {timeSlot} from './modules/timeSlot';
import {employee} from './modules/employee';
import {employeeAddress} from './modules/employeeAddress';
import {itemExtra} from './modules/itemExtra';
import {itemAddon} from './modules/itemAddon';
import { language } from './modules/language';
import {frontendBranch} from "./modules/frontend/frontendBranch";
import {frontendLanguage} from "./modules/frontend/frontendLanguage";
import {frontendSetting} from "./modules/frontend/frontendSetting";
import {frontendPage} from "./modules/frontend/frontendPage";
import {globalState} from "./modules/frontend/globalState";
import {frontendSlider} from "./modules/frontend/frontendSlider";
import {frontendItemCategory} from "./modules/frontend/frontendItemCategory";
import { timezone } from './modules/timezone';
import { site } from './modules/site';
import { dashboard } from './modules/dashboard';
import { orderSetup } from './modules/orderSetup';
import { offerItem } from './modules/offerItem';
import { paymentGateway } from './modules/paymentGateway';
import { smsGateway } from './modules/smsGateway';
import { salesReport } from './modules/salesReport';
import {frontendCart} from "./modules/frontend/frontendCart";
import { itemsReport } from './modules/itemsReport';
import { frontendEditProfile } from './modules/frontend/frontendEditProfile';
import { frontendCountryCode } from './modules/frontend/frontendCountryCode';
import { frontendAddress } from './modules/frontend/frontendAddress';
import { message } from './modules/message';
import {frontendTimeSlot} from "./modules/frontend/frontendTimeSlot";
import {frontendItem} from "./modules/frontend/frontendItem";
import { frontendOffer } from './modules/frontend/frontendOffer';
import {frontendCoupon} from "./modules/frontend/frontendCoupon";
import { countryCode } from './modules/countryCode';
import {frontendOrder} from "./modules/frontend/frontendOrder";
import {frontendSignup} from "./modules/frontend/frontendSignup";
import {GuestSignup} from "./modules/frontend/GuestSignup";
import {backendGlobalState} from "./modules/backendGlobalState";
import { myOrderDetails } from './modules/myOrderDetails';
import { posCart } from './modules/posCart';
import { posOrder } from './modules/posOrder';
import { transaction } from './modules/transaction';
import { notificationAlert } from './modules/notificationAlert';
import { creditBalanceReport } from './modules/creditBalanceReport';
import { deliveryBoyOrder } from './modules/deliveryBoyOrder';
import { user } from './modules/user';
import {frontendMessage} from "./modules/frontend/frontendMessage";
import { posCategory } from './modules/posCategory';


export default new createStore({
    state: {},
    mutations: {},
    actions: {},
    modules: {
        auth,
        company,
        itemCategory,
        itemAttribute,
        slider,
        branch,
        offer,
        item,
        itemVariation,
        tax,
        currency,
        mail,
        pushNotification,
        notification,
        page,
        onlineOrder,
        menuSection,
        menuTemplate,
        coupon,
        customer,
        customerAddress,
        otp,
        administrator,
        deliveryBoy,
        deliveryBoyAddress,
        defaultAccess,
        administratorAddress,
        socialMedia,
        license,
        analytic,
        analyticSection,
        role,
        permission,
        cookies,
        theme,
        timeSlot,
        employee,
        employeeAddress,
        itemExtra,
        itemAddon,
        language,
        globalState,
        frontendBranch,
        frontendLanguage,
        frontendSetting,
        frontendPage,
        frontendSlider,
        frontendItemCategory,
        frontendCart,
        timezone,
        site,
        dashboard,
        orderSetup,
        offerItem,
        paymentGateway,
        smsGateway,
        salesReport,
        itemsReport,
        frontendEditProfile,
        frontendCountryCode,
        frontendAddress,
        message,
        frontendTimeSlot,
        frontendItem,
        frontendOffer,
        frontendCoupon,
        countryCode,
        frontendOrder,
        frontendSignup,
        GuestSignup,
        backendGlobalState,
        myOrderDetails,
        posCart,
        posOrder,
        transaction,
        notificationAlert,
        creditBalanceReport,
        deliveryBoyOrder,
        user,
        frontendMessage,
        posCategory
    },
    plugins: [
        createPersistedState({
            paths: ["auth", "globalState", "frontendCart", "frontendSignup", "GuestSignup","posCart"],
        }),
    ],
});
