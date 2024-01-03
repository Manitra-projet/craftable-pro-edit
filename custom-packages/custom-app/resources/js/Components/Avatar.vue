<template>
  <div
    class="avatar relative flex flex-shrink-0 items-center justify-center overflow-hidden bg-gray-200"
    :class="styles"
  >
    <img v-if="src" :src="src" class="absolute inset-0 h-full w-full" alt="" />
    <span
      v-else-if="name"
      class="absolute inset-0 flex h-full w-full items-center justify-center font-medium uppercase leading-none text-white"
      :style="{ 'background-color': stringToColor(name) }"
    >
      {{ initials }}
    </span>
    <UserIcon v-else class="h-4/5 self-center text-gray-400" />
  </div>
</template>

<script setup lang="ts">
import { computed } from "@vue/reactivity";
import { UserIcon } from "@heroicons/vue/20/solid";
import { SizesType, ShapesType } from "../types";
import { stringToColor } from "../helpers";
import { inject } from "vue";

interface Props {
  src?: string;
  name?: string;
  size?: SizesType;
  shape?: ShapesType;
}

const props = defineProps<Props>();

const mergedProps = computed(() => {
  return {
    ...props,
    shape: props.shape || inject("avatarGroupShape", "circle"),
    size: props.size || inject("avatarGroupSize", "md"),
  };
});

const styles = computed(() => {
  return {
    "h-6 w-6 text-xs": mergedProps.value.size === "xs",
    "h-8 w-8 text-sm": mergedProps.value.size === "sm",
    "h-10 w-10 text-base": mergedProps.value.size === "md",
    "h-14 w-14 text-lg": mergedProps.value.size === "lg",
    "h-20 w-20 text-xl": mergedProps.value.size === "xl",
    "rounded-full": mergedProps.value.shape === "circle",
    rounded: mergedProps.value.shape === "square",
  };
});

const initials = computed(() => {
  return (
    props.name
      ?.split(" ")
      .map((n) => n[0])
      .join("") ?? ""
  );
});
</script>
