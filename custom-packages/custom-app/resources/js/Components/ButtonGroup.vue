<template>
  <div
    class="inline-flex rounded-md [&>*:not(:first-child)]:!rounded-l-none [&>*:not(:last-child)]:!rounded-r-none [&>*]:-ml-px [&>*]:!shadow-none"
    :class="{ 'shadow-sm': props.variant !== 'ghost' }"
  >
    <slot />
  </div>
</template>

<script setup lang="ts">
import { provide } from "vue";
import type { ButtonColor, SizesType, ButtonVariant } from "../types";

interface Props {
  color?: ButtonColor;
  variant?: ButtonVariant;
  size?: SizesType;
}

const props = withDefaults(defineProps<Props>(), {
  color: "primary",
  variant: "solid",
  size: "md",
});

provide("buttonGroupColor", props.color);
provide("buttonGroupVariant", props.variant);
provide("buttonGroupSize", props.size);
</script>
