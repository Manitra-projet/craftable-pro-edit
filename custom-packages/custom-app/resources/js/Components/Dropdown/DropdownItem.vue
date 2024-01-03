<template>
  <MenuItem v-slot="{ active }">
    <component
      :is="href ? Link : 'div'"
      :href="href"
      :class="[
        active ? 'bg-gray-100 text-gray-900' : 'text-gray-700',
        'flex cursor-pointer items-center gap-2 overflow-hidden text-ellipsis px-4 py-2 text-sm',
      ]"
      @click="emit('click', $event)"
    >
      <component v-if="icon" :is="icon" class="h-4 w-4" />
      <slot />
    </component>
  </MenuItem>
</template>

<script setup lang="ts">
import { MenuItem } from "@headlessui/vue";
import { Link } from "@inertiajs/vue3";
import type { Component } from "vue";

interface Props {
  href?: string;
  icon?: Component;
}

defineProps<Props>();

const emit = defineEmits(["click"]);
</script>
