<template>
  <div class="group/section" v-auto-animate>
    <div class="-ml-5 mt-4 flex items-center text-gray-400">
      <template v-if="toggable">
        <button
          v-if="toggable"
          type="button"
          @click.prevent="isOpen = !isOpen"
          class="flex w-full items-center gap-0.5"
        >
          <component
            :is="isOpen ? ChevronDownIcon : ChevronRightIcon"
            class="w-5 opacity-0 transition-opacity group-hover/section:opacity-100"
          />
          <p class="text-xs font-medium uppercase">{{ title }}</p>
        </button>
      </template>

      <template v-else>
        <p class="text-xs font-medium uppercase">{{ title }}</p>
      </template>
    </div>
    <div v-if="isOpen" class="mt-2 space-y-1">
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ChevronDownIcon, ChevronRightIcon } from "@heroicons/vue/20/solid";
import { ref } from "vue";

interface Props {
  title: string;
  open?: boolean;
  toggable?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  open: true,
  toggable: true,
});

const isOpen = ref(props.open);
</script>
