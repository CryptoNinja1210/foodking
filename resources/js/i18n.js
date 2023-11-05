import en from "./languages/en.json";
import bn from "./languages/bn.json";
import de from "./languages/de.json";
import { createI18n } from "vue-i18n";

const i18n = createI18n({
    legacy: false,
    locale: "en",
    fallbackLocale: "en",
    messages: {
        en: en,
        bn: bn,
        de: de
    }
});

export default i18n;


