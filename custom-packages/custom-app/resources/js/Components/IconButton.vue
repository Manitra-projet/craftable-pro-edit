<template>
  <Button
    :as="href && as === 'button' ? Link : as"
    :href="href ?? undefined"
    :download="download ?? undefined"
    :disabled="disabled"
    :type="type"
    :color="color"
    :variant="variant"
    :size="size"
    :class="buttonStyles"
    :only="only"
    :preserveScroll="preserveScroll"
    :preserveState="preserveState"
    @click="emit('click', $event)"
  >
    <component :is="icon" :class="iconStyles"></component>
  </Button>
</template>

<script lang="ts"></script>

<script setup lang="ts">
import { computed, inject } from "vue";
import type { Component } from "vue";
import type {
  ButtonColor,
  ButtonType,
  SizesType,
  ButtonVariant,
} from "../types";
import { Button } from ".";
import { Link } from "@inertiajs/vue3";

interface Props {
  icon: Component;
  rounded?: boolean;
  type?: ButtonType;
  color?: ButtonColor;
  variant?: ButtonVariant;
  size?: SizesType;
  disabled?: boolean;
  as?: string | Component | object;
  download?: string;
  href?: string;
  only?: Array<string>;
  preserveScroll?: boolean;
  preserveState?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  rounded: false,
  type: "button",
  disabled: false,
  as: "button",
});

const emit = defineEmits(["click"]);

const mergedProps = computed(() => {
  return {
    ...props,
    size: props.size || inject("buttonGroupSize", "md"),
  };
});

const buttonStyles = computed(() => {
  return {
    "!rounded-full": mergedProps.value.rounded,

    "!px-1": mergedProps.value.size === "xs",
    "!px-2 !py-2":
      mergedProps.value.size === "sm" || mergedProps.value.size === "md",
    "!px-2.5": mergedProps.value.size === "lg",
    "!px-3": mergedProps.value.size === "xl",
  };
});

const iconStyles = computed(() => {
  return {
    "w-4 h-4": mergedProps.value.size === "sm",
    "w-5 h-5":
      mergedProps.value.size === "md" ||
      mergedProps.value.size === "lg" ||
      mergedProps.value.size === "xs",
    "w-6 h-6": mergedProps.value.size === "lg",
    "w-7 h-7": mergedProps.value.size === "xl",
  };
});
</script>
