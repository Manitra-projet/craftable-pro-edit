<template>
  <div
    v-auto-animate
    class="flex gap-1"
    :class="{
      'flex-col': labelPlacement === 'top',
    }"
  >
    <div
      v-if="label || maxCharactersCount"
      class="flex"
      :class="{
        'justify-between': label,
        'justify-end': !label,
        'w-1/3': labelPlacement === 'left',
      }"
    >
      <label
        v-if="label"
        :for="name"
        class="flex items-center justify-between gap-2 text-sm font-medium text-gray-700"
      >
        <LocaleFlag
          v-if="parsedLocale"
          :locale="parsedLocale"
          :show-locale="false"
          class="h-4"
        >
          <template #invalidlocale="{ locale }">
            <span class="text-gray-500">[{{ locale }}]</span>
          </template>
        </LocaleFlag>
        {{ parsedLabel ?? label }}
      </label>

      <div
        v-if="maxCharactersCount && charactersCount !== undefined"
        class="text-sm font-medium leading-5"
        :class="[
          maxCharactersCount > charactersCount
            ? 'text-gray-500'
            : 'text-red-500',
        ]"
      >
        {{ charactersCount }} / {{ maxCharactersCount }}
      </div>
    </div>

    <div
      class="flex flex-col gap-2"
      :class="{ 'w-2/3': label && labelPlacement === 'left' }"
    >
      <slot />

      <p v-if="error" :id="`${name}-error`" class="text-xs text-red-600">
        {{ error }}
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import LocaleFlag from "./LocaleFlag.vue";

interface Props {
  name: string;
  label?: string;
  labelPlacement?: "top" | "left";
  error?: string | boolean;
  maxCharactersCount?: number;
  charactersCount?: number;
}

const props = withDefaults(defineProps<Props>(), {
  label: undefined,
  labelPlacement: "top",
  error: false,
  maxCharactersCount: undefined,
  charactersCount: undefined,
});

const parsedLocale = computed(() => {
  return props.label?.match(/\[([A-Z]+)\](.+)/i)?.[1]?.toLowerCase();
});

const parsedLabel = computed(() => {
  return props.label?.match(/\[([A-Z]+)\](.+)/i)?.[2];
});
</script>
