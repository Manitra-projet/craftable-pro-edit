<template>
  <div class="flex items-center justify-between">
    <div class="flex flex-1 justify-between sm:hidden">
      <Button
        color="gray"
        variant="outline"
        :href="pagination?.prev_page_url ?? undefined"
        :disabled="!pagination?.prev_page_url"
        :only="dataKey ? [dataKey] : undefined"
        :preserveState="!!dataKey"
      >
        {{ $t("custom-app", "Previous") }}
      </Button>
      <Button
        color="gray"
        variant="outline"
        :href="pagination?.next_page_url ?? undefined"
        :disabled="!pagination?.next_page_url"
        :only="dataKey ? [dataKey] : undefined"
        :preserveState="!!dataKey"
      >
        {{ $t("custom-app", "Next") }}
      </Button>
    </div>
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <div class="flex items-center gap-x-4">
        <SelectInput
          v-model="form.per_page"
          name="per_page"
          size="md"
          :options="[10, 15, 25, 50, 100]"
          class="rounded-md shadow-sm"
        />
        <p class="text-sm text-gray-700">
          {{ $t("custom-app", "Showing") }}
          {{ " " }}
          <span class="font-medium">{{ pagination?.from }}</span>
          {{ " " }}
          {{ $t("custom-app", "to") }}
          {{ " " }}
          <span class="font-medium">{{ pagination?.to }}</span>
          {{ " " }}
          {{ $t("custom-app", "of") }}
          {{ " " }}
          <span class="font-medium">{{ pagination?.total }}</span>
          {{ " " }}
          {{ $t("custom-app", "results") }}
        </p>
      </div>
      <div>
        <nav
          class="relative z-0 inline-flex -space-x-px rounded-md shadow-sm"
          aria-label="Pagination"
        >
          <ButtonGroup variant="outline" color="gray">
            <IconButton
              :href="pagination?.prev_page_url ?? undefined"
              :disabled="!pagination?.prev_page_url"
              :icon="ChevronLeftIcon"
              :only="dataKey ? [dataKey] : undefined"
              :preserveScroll="true"
              :preserveState="!!dataKey"
            />

            <template v-for="(link, index) in links">
              <Button
                v-if="link.active"
                color="primary"
                class="relative z-10 w-10 bg-primary-50 !px-2"
              >
                {{ link.label }}
              </Button>
              <Button
                v-else
                :as="Link"
                :href="link.url"
                :only="dataKey ? [dataKey] : undefined"
                :preserveScroll="true"
                :preserveState="!!dataKey"
                class="w-10 !px-2"
              >
                {{ link.label }}
              </Button>
            </template>

            <IconButton
              :href="pagination?.next_page_url ?? undefined"
              :disabled="!pagination?.next_page_url"
              :icon="ChevronRightIcon"
              :only="dataKey ? [dataKey] : undefined"
              :preserveScroll="true"
              :preserveState="!!dataKey"
            />
          </ButtonGroup>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { inject, watch } from "vue";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/24/solid";
import { Link, useForm } from "@inertiajs/vue3";
import {
  Button,
  IconButton,
  ButtonGroup,
  SelectInput,
} from "custom-app/Components";
import { Pagination } from "custom-app/types/pagination";
import { usePaginationLinks } from "custom-app/hooks/usePaginationLinks";

interface Props {
  pagination: Pagination;
  onEachSide?: number;
  dataKey?: string;
}

const props = withDefaults(defineProps<Props>(), {
  onEachSide: 2,
  dataKey: undefined,
});

const dataKey = inject("listingDataKey", props.dataKey);

const links = usePaginationLinks(props);

const form = useForm({
  per_page: props.pagination?.per_page ?? 10,
});

watch(
  () => form.per_page,
  () => {
    form.get(
      route(route().current(), { ...route().params, page: null, per_page: form.per_page })
    );
  }
);
</script>
