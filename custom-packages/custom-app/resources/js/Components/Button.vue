<template>
  <component
    :is="href && as === 'button' ? Link : as"
    :href="href ?? null"
    :type="as === 'button' ? type : null"
    class="inline-flex cursor-pointer items-center justify-center rounded-md font-medium focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-300 focus-visible:ring-offset-2"
    :class="styles"
    :download="download ?? null"
    :only="only"
    :preserveScroll="preserveScroll"
    :preserveState="preserveState"
  >
    <ArrowPathIcon
      v-if="loading"
      :class="leftIconStyles"
      class="flex-shrink-0 animate-spin stroke-2"
      aria-hidden="true"
    />
    <component
      v-else
      :is="leftIcon"
      :class="leftIconStyles"
      class="flex-shrink-0 stroke-2"
      aria-hidden="true"
    />
    <template v-if="loading && loadingText">{{ loadingText }}</template>
    <template v-else>
      <slot />
    </template>
    <component
      :is="rightIcon"
      :class="rightIconStyles"
      class="flex-shrink-0 stroke-2"
      aria-hidden="true"
    />
  </component>
</template>

<script setup lang="ts">
import { inject } from "vue";
import type { Component } from "vue";
import { computed } from "@vue/reactivity";
import type {
  ButtonColor,
  ButtonType,
  SizesType,
  ButtonVariant,
} from "../types";
import { Link } from "@inertiajs/vue3";
import { ArrowPathIcon } from "@heroicons/vue/24/outline";

interface Props {
  type?: ButtonType;
  color?: ButtonColor;
  variant?: ButtonVariant;
  size?: SizesType;
  leftIcon?: Component;
  rightIcon?: Component;
  loading?: boolean;
  disabled?: boolean;
  loadingText?: string;
  as?: string | Component | object;
  download?: string;
  href?: string;
  only?: Array<string>;
  preserveScroll?: boolean;
  preserveState?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  type: "button",
  loading: false,
  disabled: false,
  loadingText: "",
  as: "button",
});

const mergedProps = computed(() => {
  return {
    ...props,
    color: props.color || inject("buttonGroupColor", "primary"),
    variant: props.variant || inject("buttonGroupVariant", "solid"),
    size: props.size || inject("buttonGroupSize", "md"),
  };
});

const disabled = computed(() => props.loading || props.disabled);

const styles = computed(() => {
  const solid: Record<ButtonColor, string> = {
    primary:
      "border border-transparent text-white bg-primary-600 hover:bg-primary-700 active:bg-primary-800",
    secondary:
      "border border-transparent text-white bg-secondary-600 hover:bg-secondary-700 active:bg-secondary-800",
    gray: "border border-transparent text-white bg-gray-500 hover:bg-gray-600 active:bg-gray-700",
    success:
      "border border-transparent text-white bg-success-500 hover:bg-success-600 active:bg-success-700",
    info: "border border-transparent text-white bg-info-500 hover:bg-info-600 active:bg-info-700",
    warning:
      "border border-transparent text-white bg-warning-500 hover:bg-warning-600 active:bg-warning-700",
    danger:
      "border border-transparent text-white bg-danger-500 hover:bg-danger-600 active:bg-danger-700",
  };

  const outline: Record<ButtonColor, string> = {
    primary:
      "border border-primary-400 bg-white text-primary-600 hover:bg-primary-50 active:bg-primary-100",
    secondary:
      "border border-secondary-400 bg-white text-secondary-600 hover:bg-secondary-50 active:bg-secondary-100",
    gray: "border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 active:bg-gray-100",
    success:
      "border border-success-400 bg-white text-success-600 hover:bg-success-50 active:bg-success-100",
    info: "border border-info-400 bg-white text-info-600 hover:bg-info-50 active:bg-info-100",
    warning:
      "border border-warning-400 bg-white text-warning-600 hover:bg-warning-50 active:bg-warning-100",
    danger:
      "border border-danger-400 bg-white text-danger-600 hover:bg-danger-50 active:bg-danger-100",
  };

  const ghost: Record<ButtonColor, string> = {
    primary:
      "border border-transparent bg-transparent text-primary-600 hover:bg-primary-50 active:bg-primary-100",
    secondary:
      "border border-transparent bg-transparent text-secondary-600 hover:bg-secondary-50 active:bg-secondary-100",
    gray: "border border-transparent bg-transparent text-gray-500 hover:bg-gray-50 active:bg-gray-100",
    success:
      "border border-transparent bg-transparent text-success-500 hover:bg-success-50 active:bg-success-100",
    info: "border border-transparent bg-transparent text-info-500 hover:bg-info-50 active:bg-info-100",
    warning:
      "border border-transparent bg-transparent text-warning-500 hover:bg-warning-50 active:bg-warning-100",
    danger:
      "border border-transparent bg-transparent text-danger-500 hover:bg-danger-50 active:bg-danger-100",
  };

  //text-indigo-600 hover:text-indigo-900
  const link: Record<ButtonColor, string> = {
    primary: "text-primary-600 hover:text-primary-900",
    secondary: "text-secondary-600 hover:text-secondary-900",
    gray: "text-gray-600 hover:text-gray-900",
    success: "text-success-600 hover:text-success-900",
    info: "text-info-600 hover:text-info-900",
    warning: "text-warning-600 hover:text-warning-900",
    danger: "text-danger-600 hover:text-danger-900",
  };

  const variants: Record<ButtonVariant, Record<ButtonColor, string>> = {
    solid,
    outline,
    ghost,
    link,
  };

  return {
    [variants[mergedProps.value.variant][mergedProps.value.color]]: true,

    // Shadow
    "shadow-sm":
      mergedProps.value.variant !== "ghost" &&
      mergedProps.value.variant !== "link",

    // Link
    "border-none underline underline-offset-2":
      mergedProps.value.variant === "link",

    // Disabled
    "opacity-40 cursor-not-allowed": disabled.value,

    // Sizes
    "px-2.5 py-1 text-xs":
      mergedProps.value.size === "xs" && mergedProps.value.variant !== "link",
    "px-3 py-1.5 text-sm":
      mergedProps.value.size === "sm" && mergedProps.value.variant !== "link",
    "px-4 py-2 text-sm":
      mergedProps.value.size === "md" && mergedProps.value.variant !== "link",
    "px-5 py-2.5 text-base":
      mergedProps.value.size === "lg" && mergedProps.value.variant !== "link",
    "px-6 py-3 text-base":
      mergedProps.value.size === "xl" && mergedProps.value.variant !== "link",
  };
});

const leftIconStyles = computed(() => {
  return {
    "-ml-1 mr-1.5 h-4 w-4": mergedProps.value.size === "xs",
    "-ml-1 mr-2 h-4 w-4": mergedProps.value.size === "sm",
    "-ml-1 mr-3 h-5 w-5":
      mergedProps.value.size === "md" || mergedProps.value.size === "lg",
    "-ml-1 mr-4 h-6 w-6": mergedProps.value.size === "xl",
  };
});

const rightIconStyles = computed(() => {
  return {
    "ml-1.5 -mr-1 h-4 w-4": mergedProps.value.size === "xs",
    "ml-2 -mr-1 h-4 w-4": mergedProps.value.size === "sm",
    "ml-3 -mr-1 h-5 w-5":
      mergedProps.value.size === "md" || mergedProps.value.size === "lg",
    "ml-4 -mr-1 h-6 w-6": mergedProps.value.size === "xl",
  };
});
</script>
