<template>
  <Card class="xl:col-span-2">
    <div class="-my-1 flex flex-wrap items-center justify-between gap-2">
      <h3 class="font-medium leading-6">
        {{ $t("custom-app", "Translation") }}
      </h3>

      <div class="flex items-center divide-x-2">
        <Tooltip v-for="locale in availableLocales" position="top">
          <template #button>
            <button
              @click="currentLocale = locale"
              class="flex min-w-[72px] items-center px-3 text-sm uppercase leading-5 hover:cursor-pointer"
              :class="[
                locale === currentLocale
                  ? 'font-semibold text-indigo-600'
                  : 'font-normal text-slate-500 hover:text-slate-900',
              ]"
            >
              <LocaleFlag :locale="locale" />
            </button>
          </template>
          <template #content> {{ locale }} </template>
        </Tooltip>
      </div>
    </div>
  </Card>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { Card, Tooltip, LocaleFlag } from "custom-app/Components";
import { useFormLocale } from "custom-app/hooks/useFormLocale";

const props = defineProps(["modelValue"]);
const emit = defineEmits(["update:modelValue"]);

const currentLocale = computed({
  get() {
    return props.modelValue;
  },
  set(value) {
    emit("update:modelValue", value);
  },
});

const { availableLocales } = useFormLocale();
</script>
