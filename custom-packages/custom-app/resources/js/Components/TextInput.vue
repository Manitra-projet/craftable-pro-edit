<template>
  <FormControl
    :name="name"
    :label="label"
    :error="error"
    :characters-count="charactersCount"
    :max-characters-count="maxCharactersCount"
    :label-placement="labelPlacement"
  >
    <div class="relative flex rounded-md shadow-sm">
      <span
        v-if="leadingAddon"
        class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-gray-500 sm:text-sm"
      >
        {{ leadingAddon }}
      </span>
      <div class="relative w-full">
        <div
          class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
        >
          <component
            :is="leftIcon"
            class="h-5 w-5 text-gray-400"
            aria-hidden="true"
          />
        </div>

        <input
          v-model="value"
          :type="type"
          :name="name"
          :id="name"
          :autocomplete="autocomplete"
          :class="styles"
          class="block w-full rounded-md text-sm text-gray-800 focus:outline-none"
          :disabled="disabled"
          :placeholder="placeholder"
          :required="required"
          :aria-invalid="!!error"
          :aria-describedby="error ? `${name}-error` : ''"
          @input="clearValidationError"
          @invalid="showValidationErrorMsg"
          @change="emit('change', input?.value)"
          @keyup="emit('keyup', input?.value)"
          ref="input"
        />
        <div
          v-if="error || rightIcon || isPassword || clearable"
          class="absolute inset-y-0 right-0 flex items-center pr-3"
          :class="{ 'pointer-events-none': !isPassword && !clearable }"
        >
          <IconButton
            v-if="clearable && value"
            :icon="XMarkIcon"
            @click="value = ''"
            variant="ghost"
            color="gray"
            size="xs"
            class="-mr-1.5"
            :class="{ '!rounded !px-0 !py-0': size === 'sm' }"
          />
          <IconButton
            v-if="isPassword"
            :icon="type === 'password' ? EyeSlashIcon : EyeIcon"
            @click="togglePasswordType"
            variant="ghost"
            color="gray"
            size="xs"
            class="-mr-1.5"
            :class="{ '!rounded !px-0 !py-0': size === 'sm' }"
          />
          <component
            v-else
            :is="error ? ExclamationCircleIcon : rightIcon"
            :class="error ? 'text-red-500' : 'text-gray-400'"
            class="h-5 w-5"
            aria-hidden="true"
          />
        </div>
      </div>
      <span
        v-if="trailingAddon"
        class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-3 text-gray-500 sm:text-sm"
      >
        {{ trailingAddon }}
      </span>
    </div>
  </FormControl>
</template>

<script setup lang="ts">
import { EyeIcon, EyeSlashIcon } from "@heroicons/vue/24/solid";
import { ExclamationCircleIcon, XMarkIcon } from "@heroicons/vue/20/solid";
import { computed, ref } from "vue";
import type { Component } from "vue";
import { useInput } from "../hooks/useInput";
import type { SizesType } from "../types";
import { IconButton, FormControl } from ".";

interface Props {
  name: string;
  label?: string;
  type?: HTMLInputElement["type"];
  placeholder?: string;
  autocomplete?: string;
  disabled?: boolean;
  required?: boolean;
  requiredMsg?: string;
  leftIcon?: Component;
  rightIcon?: Component;
  leadingAddon?: string;
  trailingAddon?: string;
  inputClass?: string;
  modelValue: string;
  size?: SizesType;
  maxCharactersCount?: number;
  clearable?: boolean;
  labelPlacement?: "top" | "left";
}

const props = withDefaults(defineProps<Props>(), {
  type: "text",
  autocomplete: "",
  disabled: false,
  placeholder: "",
  required: false,
  inputClass: "",
  modelValue: "",
  clearable: false,
  labelPlacement: "top",
});

const emit = defineEmits(["update:modelValue", "input", "change", "keyup"]);
const input = ref<HTMLInputElement>();
const isPassword = computed(() => props.type === "password");
const type = ref(props.type);
const { value, error } = useInput(props, emit);

const togglePasswordType = () => {
  if (type.value === "password") {
    type.value = "text";
  } else {
    type.value = "password";
  }
};

const showValidationErrorMsg = () => {
  if (!input.value || !props.required) return;
  const { validity } = input.value;
  const { required, requiredMsg } = props;

  if (!validity.valid && required && requiredMsg) {
    input.value.setCustomValidity(requiredMsg);
  }
};

const clearValidationError = () => {
  emit("input", input.value);

  if (!input.value || !props.required) return;
  input.value.setCustomValidity("");
};

const styles = computed(() => {
  return {
    "pl-10": props.leftIcon,
    "pr-10": error.value || props.rightIcon,
    "rounded-l-none": props.leadingAddon,
    "rounded-r-none": props.trailingAddon,
    "border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500":
      !!error.value,
    "border-gray-300 focus:border-primary-500 focus:ring-primary-500":
      !error.value,
    [props.inputClass]: !!props.inputClass,
    "cursor-not-allowed": props.disabled,
    "py-1.5 px-3": props.size === "sm",
    "py-2 px-3": props.size === "md",
  };
});

const charactersCount = computed(() => {
  return props.maxCharactersCount ? value.value.length : 0;
});
</script>
