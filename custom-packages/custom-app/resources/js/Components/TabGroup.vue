<template>
  <slot></slot>
</template>

<script setup lang="ts">
import { provide } from "vue";

interface Props {
  variant?: "underlined" | "enclosed";
}

const props = withDefaults(defineProps<Props>(), {
  variant: "enclosed",
});

provide("tabGroupVariant", props.variant);
</script>
