import { usePage } from "@inertiajs/vue3";
import { PageProps } from "../types/page";
import { ref } from "vue";

export function useFormLocale() {
  const availableLocales = (usePage().props as PageProps).settings
    ?.available_locales;

  const currentLocale = ref((usePage().props as PageProps).auth?.user?.locale);

  const translatableDefaultValue = availableLocales.reduce((obj, locale) => {
    return {
      ...obj,
      [locale]: "",
    };
  }, {});

  const getLabelWithLocale = (label: string) => {
    return `[${currentLocale.value.toUpperCase()}] ${label}`;
  };

  return {
    availableLocales: availableLocales,
    currentLocale: currentLocale,
    translatableDefaultValue: translatableDefaultValue,
    getLabelWithLocale: getLabelWithLocale,
  };
}
