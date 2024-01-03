<template>
  <span
    :class="styles"
    class="relative inline-flex items-center px-2 py-1 font-medium"
    role="status"
  >
    <component
      v-if="icon"
      :is="icon"
      class="-ml-0.5 mr-1"
      :class="iconStyles"
    />
    <Dot v-else-if="showDot" class="-ml-0.5 mr-1.5" />
    <slot />

    <button
      v-if="dissmisable"
      type="button"
      class="ml-1 -mr-1.5 inline-flex flex-shrink-0 items-center justify-center hover:bg-opacity-30 focus:outline-none"
      :class="buttonStyles"
      @click="emit('dismiss', $event)"
    >
      <span class="sr-only">Dismiss</span>
      <XMarkIcon class="h-full w-full p-0.5 text-current" />
    </button>
  </span>
</template>

<script setup lang="ts">
import { XMarkIcon } from "@heroicons/vue/24/solid";
import { computed } from "vue";
import type { Component } from "vue";
import type { TagColorsType, TagVariantsType } from "custom-app/types";
import Dot from "./Dot.vue";

interface Props {
  showDot?: boolean;
  dissmisable?: boolean;
  color?: TagColorsType;
  variant?: TagVariantsType;
  rounded?: boolean;
  size?: "sm" | "md";
  icon?: Component;
}

const props = withDefaults(defineProps<Props>(), {
  showDot: false,
  color: "primary",
  variant: "solid",
  rounded: false,
  size: "md",
});

const emit = defineEmits(["dismiss"]);

const buttonStyles = computed(() => {
  return {
    "hover:bg-info-700 text-info-800": props.color === "info",
    "hover:bg-warning-700 text-warning-800": props.color === "warning",
    "hover:bg-success-700 text-success-800": props.color === "success",
    "hover:bg-danger-700 text-danger-800": props.color === "danger",
    "hover:bg-purple-700 text-purple-800": props.color === "purple",
    "hover:bg-amber-400 hover:text-amber-900": props.color === "amber",
    "hover:bg-cyan-700 text-cyan-800": props.color === "cyan",
    "hover:bg-gray-700 text-gray-800": props.color === "gray",
    "rounded-full": props.rounded,
    "rounded-sm": !props.rounded,
    "h-4 w-4": props.size === "sm",
    "h-5 w-5": props.size === "md",
  };
});

const iconStyles = computed(() => {
  return {
    "text-info-400": props.color === "info",
    "text-warning-400": props.color === "warning",
    "text-success-400": props.color === "success",
    "text-danger-400": props.color === "danger",
    "text-purple-400": props.color === "purple",
    "text-amber-400": props.color === "amber",
    "text-cyan-400": props.color === "cyan",
    "text-gray-400": props.color === "gray",
    "h-4 w-4": props.size === "sm",
    "h-5 w-5": props.size === "md",
  };
});

const styles = computed(() => {
  const solid: Record<TagColorsType, string> = {
    primary: "bg-primary-100 text-primary-800",
    secondary: "bg-secondary-100 text-secondary-800",
    gray: "bg-gray-100 text-gray-800",
    success: "bg-success-100 text-success-800",
    info: "bg-info-100 text-info-800",
    warning: "bg-warning-100 text-warning-800",
    danger: "bg-danger-100 text-danger-800",
    purple: "bg-purple-100 text-purple-800",
    amber: "bg-amber-100 text-amber-800",
    cyan: "bg-cyan-100 text-cyan-800",
  };

  const outline: Record<TagColorsType, string> = {
    primary: "border-primary-300 text-primary-800",
    secondary: "border-secondary-300 text-secondary-800",
    gray: "border-gray-300 text-gray-800",
    success: "border-success-300 text-success-800",
    info: "border-info-300 text-info-800",
    warning: "border-warning-300 text-warning-800",
    danger: "border-danger-300 text-danger-800",
    purple: "border-purple-300 text-purple-800",
    amber: "border-amber-300 text-amber-800",
    cyan: "border-cyan-300 text-cyan-800",
  };

  const variants: Record<TagVariantsType, Record<TagColorsType, string>> = {
    solid,
    outline,
  };

  return {
    [variants[props.variant][props.color]]: true,

    "border -my-px": props.variant === "outline",

    // Rounded
    "rounded-full": props.rounded,
    "rounded-md": !props.rounded,

    // Sizes
    "text-xs": props.size === "sm",
    "text-sm": props.size === "md",
  };
});
</script>
