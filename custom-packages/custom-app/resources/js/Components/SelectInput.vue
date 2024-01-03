<template>
  <FormControl
    :name="name"
    :label="label"
    :error="error"
    :label-placement="labelPlacement"
  >
    <div class="relative flex rounded-md text-gray-600 shadow-sm">
      <select
        v-model="value"
        :id="name"
        :name="name"
        :class="styles"
        class="block w-full rounded-md focus:outline-none"
      >
        <option v-if="placeholder" value="" :selected="true" :disabled="true">
          {{ placeholder }}
        </option>
        <option
          v-for="(option, index) in options"
          :key="index"
          :value="
            typeof option === 'object' ? option[optionsValueProp] : option
          "
          :selected="
            typeof option === 'object'
              ? option[optionsValueProp] == value
              : option == value
          "
        >
          {{ typeof option === "object" ? option[optionsLabel] : option }}
        </option>
      </select>

      <div
        v-if="error"
        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-8"
      >
        <ExclamationCircleIcon
          class="h-5 w-5 text-red-500"
          aria-hidden="true"
        />
      </div>
    </div>
  </FormControl>
</template>
<script setup lang="ts">
import { computed } from "vue";
import { ExclamationCircleIcon } from "@heroicons/vue/20/solid";
import { useInput } from "../hooks/useInput";
import { FormControl } from ".";
import type { SizesType } from "../types";

interface Props {
  name: string;
  label?: string;
  disabled?: boolean;
  placeholder?: string;
  inputClass?: string;
  modelValue?: string | number;
  size?: SizesType;
  options: Array<
    | string
    | number
    | {
        [key: string | number]: string | number;
      }
  >;
  labelPlacement?: "top" | "left";
  optionsLabel?: string;
  optionsValueProp?: string;
}

const props = withDefaults(defineProps<Props>(), {
  disabled: false,
  modelValue: "",
  size: "md",
  labelPlacement: "top",
  optionsLabel: "label",
  optionsValueProp: "value",
});

const emit = defineEmits(["update:modelValue"]);

const { value, error } = useInput(props, emit);

const styles = computed(() => {
  return {
    "py-1 pl-1.5 pr-5 text-sm bg-[center_right_0.15rem]": props.size === "xs",
    "py-1.5 pl-2 pr-6 text-sm bg-[center_right_0.25rem]": props.size === "sm",
    "py-2 pl-3 pr-7 text-sm": props.size === "md",
    "border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500":
      !!error.value,
    "border-gray-300 focus:border-primary-500 focus:ring-primary-500":
      !error.value,
  };
});
</script>
