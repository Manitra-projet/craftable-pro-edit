<template>
  <div :class="styles" class="rounded-md p-4">
    <div class="flex">
      <div class="flex-shrink-0">
        <ExclamationCircleIcon
          v-if="type === 'warning'"
          class="h-6 w-6 text-warning-400"
          aria-hidden="true"
        />
        <ExclamationCircleIcon
          v-if="type === 'danger'"
          class="h-6 w-6 text-danger-400"
          aria-hidden="true"
        />
        <CheckCircleIcon
          v-if="type === 'success'"
          class="h-6 w-6 text-success-400"
          aria-hidden="true"
        />
        <InformationCircleIcon
          v-if="type === 'info'"
          class="h-6 w-6 text-info-400"
          aria-hidden="true"
        />
      </div>
      <div class="ml-3 mt-0.5">
        <h3
          v-if="hasTitle"
          :class="titleStyles"
          class="mb-2 text-sm font-medium"
        >
          <slot name="title" />
        </h3>
        <div :class="contentStyles" class="text-sm">
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, useSlots } from "vue";
import {
  ExclamationCircleIcon,
  CheckCircleIcon,
  InformationCircleIcon,
} from "@heroicons/vue/24/solid";
import { ComponentVariant } from "../types";

interface Props {
  type: ComponentVariant;
}

const props = defineProps<Props>();

const slots = useSlots();
const hasTitle = computed(() => !!slots.title);

// TODO: maybe alias colors in tailwind config to success, warning, danger, info??
const styles = computed(() => {
  return {
    "bg-warning-50": props.type === "warning",
    "bg-danger-50": props.type === "danger",
    "bg-success-50": props.type === "success",
    "bg-info-50": props.type === "info",
  };
});

const titleStyles = computed(() => {
  return {
    "text-warning-800": props.type === "warning",
    "text-danger-800": props.type === "danger",
    "text-success-800": props.type === "success",
    "text-info-800": props.type === "info",
  };
});

const contentStyles = computed(() => {
  return {
    "text-warning-700": props.type === "warning",
    "text-danger-700": props.type === "danger",
    "text-success-700": props.type === "success",
    "text-info-700": props.type === "info",
  };
});
</script>
