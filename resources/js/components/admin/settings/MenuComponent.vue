<template>
    <button class="settings-btn w-full md:hidden flex items-center justify-center gap-2 p-2 rounded bg-primary text-white">
        <span class="capitalize">{{ $t('menu.settings_menu') }}</span>
        <i class="fa-solid fa-chevron-down text-sm"></i>
    </button>
    <div class="h-0 overflow-hidden md:h-auto md:overflow-auto transition-all duration-300">
        <nav class="db-card p-3">
            <router-link :to="{ name: 'admin.settings.company' }" class="db-tab-btn">
                <i class="lab lab-company text-sm"></i>
                {{ $t("menu.company") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.site' }" class="db-tab-btn">
                <i class="lab lab-site  text-sm"></i>
                {{ $t("menu.site") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.branch' }" class="db-tab-btn">
                <i class="lab lab-branches text-sm"></i>
                {{ $t("menu.branches") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.mail' }" class="db-tab-btn">
                <i class="lab lab-mail text-sm"></i>
                {{ $t("menu.mail") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.orderSetup' }" class="db-tab-btn">
                <i class="lab lab-order-setup text-sm"></i>
                {{ $t("menu.order_setup") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.otp' }" class="db-tab-btn">
                <i class="lab lab-otp text-sm"></i>
                {{ $t("menu.otp") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.notification' }" class="db-tab-btn">
                <i class="lab lab-notification text-sm"></i>
                {{ $t("menu.notification") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.notificationAlert' }" class="db-tab-btn">
                <i class="lab lab-license text-sm"></i>
                {{ $t("menu.notification_alert") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.socialMedia' }" class="db-tab-btn">
                <i class="lab lab-social-media text-sm"></i>
                {{ $t("menu.social_media") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.cookies' }" class="db-tab-btn">
                <i class="lab lab-cookies text-sm"></i>
                {{ $t("menu.cookies") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.analytic' }" class="db-tab-btn">
                <i class="lab lab-analytics text-sm"></i>
                {{ $t("menu.analytics") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.theme' }" class="db-tab-btn">
                <i class="lab lab-theme text-sm"></i>
                {{ $t("menu.theme") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.timeSlot' }" class="db-tab-btn">
                <i class="lab lab-time-slots text-sm"></i>
                {{ $t("menu.time_slots") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.slider' }" class="db-tab-btn">
                <i class="lab lab-sliders text-sm"></i>
                {{ $t("menu.sliders") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.currency' }" class="db-tab-btn">
                <i class="lab lab-currencies text-sm"></i>
                {{ $t("menu.currencies") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.itemCategory' }" class="db-tab-btn">
                <i class="lab lab-item-categories text-sm"></i>
                {{ $t("menu.item_categories") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.itemAttribute' }" class="db-tab-btn">
                <i class="lab lab-item-attributes text-sm"></i>
                {{ $t("menu.item_attributes") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.tax' }" class="db-tab-btn">
                <i class="lab lab-taxes text-sm"></i>
                {{ $t("menu.taxes") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.page' }" class="db-tab-btn">
                <i class="lab lab-pages text-sm"></i>
                {{ $t("menu.pages") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.role' }" class="db-tab-btn">
                <i class="lab lab-role-permissions text-sm"></i>
                {{ $t("menu.role_permissions") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.language' }" class="db-tab-btn">
                <i class="lab lab-languages text-sm"></i>
                {{ $t("menu.languages") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.smsGateway' }" class="db-tab-btn">
                <i class="lab lab-sms text-sm"></i>
                {{ $t("menu.sms_gateway") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.paymentGateway' }" class="db-tab-btn">
                <i class="lab lab-payment-gateway text-sm"></i>
                {{ $t("menu.payment_gateway") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.license' }" class="db-tab-btn">
                <i class="lab lab-license text-sm"></i>
                {{ $t("menu.license") }}
            </router-link>
        </nav>
    </div>
</template>

<script>
export default {
    name: "MenuComponent",
};
</script>
