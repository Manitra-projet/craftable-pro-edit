<template>
  <span class="flex items-center gap-2">
    <template v-if="invalidLocale && hasInvalidLocaleSlot">
      <slot name="invalidlocale" :locale="locale" />
    </template>
    <img
      v-else
      :src="src"
      :alt="locale"
      @error="onError"
      class="h-full max-h-5"
    />
    <template v-if="showLocale">
      {{
        $t("locales", locale) === `locales.${locale}`
          ? locale
          : $t("locales", locale)
      }}
    </template>
  </span>
</template>

<script setup lang="ts">
import { computed, ref, useSlots, watch } from "vue";

const props = withDefaults(
  defineProps<{
    locale: string;
    showLocale?: boolean;
  }>(),
  {
    showLocale: true,
  }
);

const slots = useSlots();

const invalidLocale = ref(false);
const hasInvalidLocaleSlot = computed(() => {
  return !!slots.invalidlocale;
});

const getLocaleUrl = (locale?: string) => {
  if (!locale) {
    invalidLocale.value = true;

    return `https://hatscripts.github.io/circle-flags/flags/xx.svg`;
  }

  invalidLocale.value = false;

  return `https://hatscripts.github.io/circle-flags/flags/${
    locale === "klingon" ? "fictional" : "language"
  }/${locale}.svg`;
};

const src = ref(getLocaleUrl(props.locale));

watch(
  () => props.locale,
  (locale) => {
    src.value = getLocaleUrl(locale);
  }
);

const onError = () => {
  src.value = getLocaleUrl();
};
</script>
