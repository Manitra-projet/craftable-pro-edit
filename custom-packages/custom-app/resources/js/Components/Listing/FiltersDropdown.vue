<template>
  <ButtonGroup>
    <Dropdown>
      <template #button>
        <Button
          :leftIcon="FunnelIcon"
          :rightIcon="ChevronDownIcon"
          color="gray"
          variant="outline"
          size="sm"
          :class="{ '!rounded-r-none': resetFilters && activeFiltersCount }"
        >
          {{ $t("custom-app", "Filters") }}
          <div
            v-if="activeFiltersCount"
            class="ml-2 inline-flex h-5 w-5 items-center justify-center rounded-full bg-primary-600 text-xs leading-none text-white"
          >
            {{ activeFiltersCount }}
          </div>
        </Button>
      </template>
      <template #content>
        <div class="flex flex-col gap-4 sm:w-screen sm:max-w-sm">
          <slot />
        </div>
      </template>
    </Dropdown>
    <div v-auto-animate>
      <IconButton
        v-if="resetFilters && activeFiltersCount"
        :icon="XMarkIcon"
        color="gray"
        variant="outline"
        size="sm"
        class="-ml-px rounded-l-none !bg-gray-50 hover:!bg-gray-100 stroke-2"
        @click="resetFilters"
      />
    </div>
  </ButtonGroup>
</template>

<script setup lang="ts">
import {
  ChevronDownIcon,
  FunnelIcon,
  XMarkIcon,
} from "@heroicons/vue/24/outline";
import { useListingFilters } from "custom-app/hooks/useListingFilters";
import { Button, ButtonGroup, Dropdown, IconButton } from "..";
import { inject } from "vue";

interface Props {
  activeFiltersCount?: number;
  resetFilters?: () => void;
}

const props = defineProps<Props>();
</script>
