<template>
  <div
    class="flex items-center [&_.avatar]:ring-2 [&_.avatar]:ring-white"
    :class="styles"
  >
    <slot />

    <component
      v-if="additionalCount"
      :is="additionalHref ? Link : 'span'"
      :href="additionalHref"
      :class="limitStyles"
      class="z-10 whitespace-nowrap tracking-tighter text-gray-500"
    >
      + {{ additionalCount }}
    </component>
  </div>
</template>

<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { computed } from "@vue/reactivity";
import { provide } from "vue";
import { SizesType, ShapesType } from "../types";

interface Props {
  size?: SizesType;
  shape?: ShapesType;
  additionalCount?: number;
  additionalHref?: string;
}

const props = withDefaults(defineProps<Props>(), {
  size: "md",
  shape: "circle",
});

const limitStyles = computed(() => {
  return {
    "text-xs pl-2": props.size === "xs",
    "text-sm pl-3": props.size === "sm",
    "text-base pl-3.5 mb-0.5": props.size === "md",
    "text-lg pl-4 mb-0.5": props.size === "lg",
    "text-xl pl-5 mb-0.5": props.size === "xl",
  };
});

const styles = computed(() => {
  return {
    "-space-x-1": props.size === "xs",
    "-space-x-2":
      props.size === "sm" || props.size === "md" || props.size === "lg",
    "-space-x-3": props.size === "xl",
  };
});

provide("avatarGroupShape", props.shape);
provide("avatarGroupSize", props.size);
</script>
