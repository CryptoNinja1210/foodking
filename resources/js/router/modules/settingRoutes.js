import SettingsComponent from "../../components/admin/settings/SettingsComponent";
import CompanyComponent from "../../components/admin/settings/Company/CompanyComponent";
import SiteComponent from "../../components/admin/settings/Site/SiteComponent";
import ItemCategoryListComponent from "../../components/admin/settings/ItemCategory/ItemCateogryListComponent";
import ItemCategoryComponent from "../../components/admin/settings/ItemCategory/ItemCategoryComponent";
import ItemCategoryShowComponent from "../../components/admin/settings/ItemCategory/ItemCategoryShowComponent";
import ItemAttributeComponent from "../../components/admin/settings/ItemAttribute/ItemAttributeComponent";
import ItemAttributeListComponent from "../../components/admin/settings/ItemAttribute/ItemAttributeListComponent";
import SliderComponent from "../../components/admin/settings/Slider/SliderComponent";
import SliderListComponent from "../../components/admin/settings/Slider/SliderListComponent";
import SliderShowComponent from "../../components/admin/settings/Slider/SliderShowComponent";
import BranchComponent from "../../components/admin/settings/Branch/BranchComponent";
import BranchListComponent from "../../components/admin/settings/Branch/BranchListComponent";
import BranchShowComponent from "../../components/admin/settings/Branch/BranchShowComponent";
import TaxComponent from "../../components/admin/settings/Tax/TaxComponent";
import TaxListComponent from "../../components/admin/settings/Tax/TaxListComponent";
import CurrencyComponent from "../../components/admin/settings/Currency/CurrencyComponent";
import CurrencyListComponent from "../../components/admin/settings/Currency/CurrencyListComponent";
import MailComponent from "../../components/admin/settings/Mail/MailComponent";
import NotificationComponent from "../../components/admin/settings/Notification/NotificationComponent";
import PageComponent from "../../components/admin/settings/Page/PageComponent";
import PageListComponent from "../../components/admin/settings/Page/PageListComponent";
import PageShowComponent from "../../components/admin/settings/Page/PageShowComponent";
import OtpComponent from "../../components/admin/settings/Otp/OtpComponent";
import SocialMediaComponent from "../../components/admin/settings/SocialMedia/SocialMediaComponent";
import LicenseComponent from "../../components/admin/settings/License/LicenseComponent";
import AnalyticComponent from "../../components/admin/settings/analytics/AnalyticComponent";
import AnalyticListComponent from "../../components/admin/settings/analytics/AnalyticListComponent";
import AnalyticShowComponent from "../../components/admin/settings/analytics/AnalyticShowComponent";
import RoleComponent from "../../components/admin/settings/Role/RoleComponent";
import RoleListComponent from "../../components/admin/settings/Role/RoleListComponent";
import RoleShowComponent from "../../components/admin/settings/Role/RoleShowComponent";
import CookiesComponent from "../../components/admin/settings/Cookies/CookiesComponent";
import ThemeComponent from "../../components/admin/settings/Theme/ThemeComponent";
import TimeSlotListComponent from "../../components/admin/settings/TimeSlot/TimeSlotListComponent";
import LanguageComponent from "../../components/admin/settings/Language/LanguageComponent";
import LanguageListComponent from "../../components/admin/settings/Language/LanguageListComponent";
import LanguageShowComponent from "../../components/admin/settings/Language/LanguageShowComponent";
import OrderSetupComponent from "../../components/admin/settings/OrderSetup/OrderSetupComponent";
import PaymentGatewayComponent from "../../components/admin/settings/PaymentGateway/PaymentGatewayComponent";
import SmsGatewayComponent from "../../components/admin/settings/SmsGateway/SmsGatewayComponent";
import NotificationAlertComponent from "../../components/admin/settings/NotificationAlert/NotificationAlertComponent";

export default [
    {
        path: "/admin/settings",
        component: SettingsComponent,
        name: "admin.settings",
        redirect: { name: "admin.settings.company" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "settings",
            breadcrumb: "settings",
        },
        children: [
            {
                path: "company",
                component: CompanyComponent,
                name: "admin.settings.company",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "company",
                },
            },
            {
                path: "site",
                component: SiteComponent,
                name: "admin.settings.site",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "site",
                },
            },
            {
                path: "branches",
                component: BranchComponent,
                name: "admin.settings.branch",
                redirect: { name: "admin.settings.branch.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "branches",
                },
                children: [
                    {
                        path: "list",
                        component: BranchListComponent,
                        name: "admin.settings.branch.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: BranchShowComponent,
                        name: "admin.settings.branch.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "mail",
                component: MailComponent,
                name: "admin.settings.mail",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "mail",
                },
            },
            {
                path: "order-setup",
                component: OrderSetupComponent,
                name: "admin.settings.orderSetup",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "order_setup",
                },
            },
            {
                path: "otp",
                component: OtpComponent,
                name: "admin.settings.otp",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "otp",
                },
            },
            {
                path: "notification",
                component: NotificationComponent,
                name: "admin.settings.notification",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "notification",
                },
            },
            {
                path: "social-media",
                component: SocialMediaComponent,
                name: "admin.settings.socialMedia",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "social_media",
                },
            },
            {
                path: "cookies",
                component: CookiesComponent,
                name: "admin.settings.cookies",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "cookies",
                },
            },
            {
                path: "analytics",
                component: AnalyticComponent,
                name: "admin.settings.analytic",
                redirect: { name: "admin.settings.analytic.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "analytics",
                },
                children: [
                    {

                        path: "list",
                        component: AnalyticListComponent,
                        name: "admin.settings.analytic.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: AnalyticShowComponent,
                        name: "admin.settings.analytic.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ]
            },
            {
                path: "theme",
                component: ThemeComponent,
                name: "admin.settings.theme",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "theme",
                },
            },
            {
                path: "time-slots",
                component: TimeSlotListComponent,
                name: "admin.settings.timeSlot",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "time_slots",
                }
            },
            {
                path: "sliders",
                component: SliderComponent,
                name: "admin.settings.slider",
                redirect: { name: "admin.settings.slider.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "sliders",
                },
                children: [
                    {
                        path: "list",
                        component: SliderListComponent,
                        name: "admin.settings.slider.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: SliderShowComponent,
                        name: "admin.settings.slider.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "currencies",
                component: CurrencyComponent,
                name: "admin.settings.currency",
                redirect: { name: "admin.settings.currency.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "currencies",
                },
                children: [
                    {
                        path: "list",
                        component: CurrencyListComponent,
                        name: "admin.settings.currency.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                ],
            },
            {
                path: "item-categories",
                component: ItemCategoryComponent,
                name: "admin.settings.itemCategory",
                redirect: { name: "admin.settings.itemCategory.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "item_categories",
                },
                children: [
                    {
                        path: "list",
                        component: ItemCategoryListComponent,
                        name: "admin.settings.itemCategory.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: ItemCategoryShowComponent,
                        name: "admin.settings.itemCategory.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "item-attributes",
                component: ItemAttributeComponent,
                name: "admin.settings.itemAttribute",
                redirect: { name: "admin.settings.itemAttribute.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "item_attributes",
                },
                children: [
                    {
                        path: "list",
                        component: ItemAttributeListComponent,
                        name: "admin.settings.itemAttribute.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                ],
            },
            {
                path: "taxes",
                component: TaxComponent,
                name: "admin.settings.tax",
                redirect: { name: "admin.settings.tax.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "taxes",
                },
                children: [
                    {
                        path: "list",
                        component: TaxListComponent,
                        name: "admin.settings.tax.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                ],
            },
            {
                path: "pages",
                component: PageComponent,
                name: "admin.settings.page",
                redirect: { name: "admin.settings.page.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "pages",
                },
                children: [
                    {
                        path: "list",
                        component: PageListComponent,
                        name: "admin.settings.page.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: PageShowComponent,
                        name: "admin.settings.page.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "role",
                component: RoleComponent,
                name: "admin.settings.role",
                redirect: { name: "admin.settings.role.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "role_permissions",
                },
                children: [
                    {
                        path: "list",
                        component: RoleListComponent,
                        name: "admin.settings.role.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: RoleShowComponent,
                        name: "admin.settings.role.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "languages",
                component: LanguageComponent,
                name: "admin.settings.language",
                redirect: { name: "admin.settings.language.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "languages",
                },
                children: [
                    {
                        path: "list",
                        component: LanguageListComponent,
                        name: "admin.settings.language.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: LanguageShowComponent,
                        name: "admin.settings.language.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "sms-gateway",
                component: SmsGatewayComponent,
                name: "admin.settings.smsGateway",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "sms_gateway",
                },
            },
            {
                path: "payment-gateway",
                component: PaymentGatewayComponent,
                name: "admin.settings.paymentGateway",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "payment_gateway",
                },
            },
            {
                path: "license",
                component: LicenseComponent,
                name: "admin.settings.license",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "license",
                }
            },
            {
                path: "notification-alert",
                component: NotificationAlertComponent,
                name: "admin.settings.notificationAlert",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "notification_alert",
                }
            }
        ],
    },
];
