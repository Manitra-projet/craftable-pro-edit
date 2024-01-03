<template>
  <div
    class="w-full"
    :class="{
      'sticky top-0 z-30': sticky,
    }"
  >
    <Head v-if="title" :title="title" />

    <header
      class="flex w-full border-b border-gray-200 bg-white px-4 sm:px-6"
      :class="{
        'py-3 sm:py-4': subtitle,
        'py-3 sm:py-5': !subtitle,
      }"
    >
      <!-- Spacer -->
      <div class="h-10" />
      <div
        class="flex w-full flex-wrap items-center justify-between gap-2 sm:flex-nowrap"
      >
        <div class="mr-auto">
          <h3 v-if="title" class="text-xl font-medium leading-6 text-gray-900">
            {{ title }}
          </h3>
          <p v-if="subtitle" class="mt-1 text-sm text-gray-500">
            {{ subtitle }}
          </p>
        </div>
        <div class="flex-shrink-0">
          <slot />
        </div>
      </div>
    </header>
  </div>
</template>

<script setup lang="ts">
import { Head } from "@inertiajs/vue3";

interface Props {
  title: string;
  subtitle?: string;
  sticky?: boolean;
}

withDefaults(defineProps<Props>(), {
  sticky: false,
});
</script>
