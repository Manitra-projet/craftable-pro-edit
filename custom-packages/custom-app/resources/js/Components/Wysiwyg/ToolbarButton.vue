<template>
  <button
    type="button"
    class="inline-flex items-center rounded bg-white p-1 text-sm font-medium leading-4 text-gray-700 hover:bg-gray-100 focus:outline-none"
    :class="{ 'bg-gray-200': active }"
    :title="title"
  >
    <component :is="icon" class="w-5" />
  </button>
</template>
<script setup lang="ts">
import type { Component } from "vue";

interface Props {
  icon: Component;
  active?: boolean;
  title?: string;
}

withDefaults(defineProps<Props>(), {
  active: false,
});
</script>
