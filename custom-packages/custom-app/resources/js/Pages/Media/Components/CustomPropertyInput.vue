<template>
  <div
    class="flex w-full items-center justify-start gap-x-1 text-sm font-medium"
    :class="{
      'py-1.5': true,
    }"
  >
    <dt class="flex items-center gap-x-2">
      <button
        @click="localeChanged"
        v-if="typeof value === 'object' && value !== null"
      >
        <LocaleFlag :locale="locale" :showLocale="false" class="w-4" />
      </button>
      <span class="text-gray-500">
        {{ $t("custom-app", propertyName) }}
      </span>
    </dt>
    <dd class="ml-auto flex items-center whitespace-nowrap text-gray-900">
      <span v-if="!isEditing">
        {{
          typeof value === "object" && value !== null ? value[locale] : value
        }}
      </span>
      <template v-else>
        <TextInput
          v-if="typeof value === 'object' && value !== null"
          v-model="value[locale]"
          size="sm"
          label-placement="left"
          :name="propertyName"
          class="w-full"
        />
        <TextInput
          v-else
          v-model="value"
          size="sm"
          label-placement="left"
          :name="propertyName"
          class="w-full"
        />
      </template>

      <IconButton
        class="ml-2"
        variant="ghost"
        color="gray"
        size="sm"
        :icon="isEditing ? ArrowDownTrayIcon : PencilSquareIcon"
        @click="save"
      />
    </dd>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { useFormLocale } from "custom-app/hooks/useFormLocale";
import { PencilSquareIcon, ArrowDownTrayIcon } from "@heroicons/vue/20/solid";
import { IconButton, TextInput } from "custom-app/Components";
import LocaleFlag from "../../../Components/LocaleFlag.vue";

interface Props {
  modelValue: Record<string, string> | string;
  propertyName: string;
}

const props = defineProps<Props>();
const isEditing = ref(false);

const { availableLocales, currentLocale } = useFormLocale();

const locale = ref(currentLocale);

const value = computed({
  get: () => props.modelValue,
  set: (newValue) => {
    emit("update:modelValue", newValue);
  },
});

const localeChanged = () => {
  const currentLocaleIndex = availableLocales?.findIndex(
    (item) => item === locale.value
  );
  locale.value =
    availableLocales[currentLocaleIndex + 1] ?? availableLocales[0];
};

const save = () => {

  if (isEditing.value) {
    emit("saved");
  }

  isEditing.value = !isEditing.value;
};

const emit = defineEmits(["localeChanged", "update:modelValue", "saved"]);
</script>
