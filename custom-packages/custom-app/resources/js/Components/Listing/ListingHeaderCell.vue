<template>
  <th
    scope="col"
    class="p-3 text-left text-xs font-semibold text-gray-500 first-of-type:pl-4 last-of-type:pr-4 first-of-type:sm:pl-6 last-of-type:sm:pr-6"
  >
    <div v-if="sortBy" class="inline-flex">
      <Link
        :href="baseUrl"
        :data="data"
        replace
        preserveScroll
        :preserveState="!!dataKey"
        :only="dataKey ? [dataKey, 'sort'] : undefined"
        class="group"
      >
        <span class="flex items-center gap-2">
          <slot></slot>

          <span
            class="flex-none rounded"
            :class="{
              'text-gray-400 group-hover:bg-gray-200 group-hover:text-gray-700':
                !sortByIsActive,
              'bg-gray-200 text-gray-900 group-hover:bg-gray-300':
                sortByIsActive,
            }"
          >
            <ChevronDownIcon
              v-if="!currentSortDirection || currentSortDirection === 'asc'"
              class="h-5 w-5"
              aria-hidden="true"
            />

            <ChevronUpIcon v-else class="h-5 w-5" aria-hidden="true" />
          </span>
        </span>
      </Link>
    </div>

    <slot v-else />
  </th>
</template>

<script setup lang="ts">
import { ChevronDownIcon, ChevronUpIcon } from "@heroicons/vue/20/solid";
import { usePage } from "@inertiajs/vue3";
import { PageProps } from "custom-app/types/page";
import { computed, inject } from "vue";

interface Props {
  sortBy?: string;
}

const props = defineProps<Props>();

const baseUrl = inject("listingBaseUrl");

const dataKey = inject("listingDataKey");

if (props.sortBy && !baseUrl) {
  console.error("baseUrl in ListingHeaderCell is not provided");
}

const currentSortBy = computed(() => (usePage().props as PageProps).sort);

const sortByIsActive = computed(() => {
  return (
    props.sortBy === currentSortBy.value ||
    `-${props.sortBy}` === currentSortBy.value
  );
});

const currentSortDirection = computed(() => {
  if (!sortByIsActive.value) {
    return null;
  }

  if (currentSortBy.value?.[0] === "-") {
    return "desc";
  }

  return "asc";
});

const data = computed(() => {
  return {
    ...(currentSortDirection.value === "desc"
      ? { ...route().params, sort: "" }
      : {
          ...route().params,
          sort: `${
            !currentSortDirection.value || currentSortDirection.value === "desc"
              ? ""
              : "-"
          }${props.sortBy}`,
        }),
  };
});
</script>
