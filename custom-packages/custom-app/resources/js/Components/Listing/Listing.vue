<template>
  <Card noPadding>
    <template #header>
      <CardHeader
        class="border border-transparent"
        :class="{
          '!border-primary-400 bg-primary-50 border-2 border-b-2 border-dashed':
            indeterminate || allItemsSelected,
        }"
      >
        <div
          class="relative flex items-center"
          :class="{ '-m-px': indeterminate || allItemsSelected }"
          v-auto-animate="{ duration: 150 }"
        >
          <div class="h-[34px]" />
          <Checkbox
            v-if="withBulkSelect"
            class="-ml-px"
            :checked="indeterminate || allItemsSelected"
            :indeterminate="indeterminate"
            @change="
              $event.target.checked
                ? (selectedItems = collection.map((p) => p.id))
                : unselectAllItems()
            "
          />
          <div
            v-if="selectedItems?.length > 0"
            class="flex items-center gap-x-4 pl-6 text-sm text-gray-700"
            v-auto-animate
          >
            <div class="flex-shrink-0">
              <span class="leading-8 sm:whitespace-nowrap">
                {{
                  $tChoice(
                    "craftable-pro",
                    "{1} :count item selected.|[2,*] :count items selected.",
                    selectedItems.length
                  )
                }}
              </span>
              &nbsp;
              <span v-if="!allItemsSelected">
                <Button
                  @click="selectAllItems"
                  :disabled="loadingAllItems"
                  size="sm"
                  class="whitespace-nowrap"
                  variant="link"
                >
                  {{ $t("custom-app", "Select all items.") }}
                </Button>
              </span>
            </div>

            <slot
              name="bulkActions"
              :baseUrl="baseUrl"
              :selectedItems="selectedItems"
              :bulkAction="bulkAction"
            >
              <Button
                @click="() => bulkAction('post', `${baseUrl}/bulk-destroy`)"
                color="gray"
                variant="outline"
                size="sm"
                :leftIcon="TrashIcon"
              >
                {{ $t("custom-app", "Delete") }}
              </Button>
            </slot>
          </div>
          <div
            v-else
            class="flex w-full items-center justify-between gap-3"
            :class="{ 'pl-6': withBulkSelect }"
          >
            <slot
              name="header"
              :searchForm="searchForm"
              :resetSearch="resetSearch"
            >
              <div class="w-3/6 md:w-2/6">
                <TextInput
                  v-model="searchForm.search"
                  name="search"
                  size="sm"
                  :leftIcon="MagnifyingGlassIcon"
                  :placeholder="$t('custom-app', 'Search')"
                  :clearable="true"
                  class="w-full"
                />
              </div>
              <slot name="actions" />
            </slot>
          </div>
        </div>
      </CardHeader>
    </template>

    <div class="overflow-x-auto">
      <div class="inline-block min-w-full align-middle">
        <div class="relative overflow-hidden md:overflow-visible">
          <EmptyListing v-if="!collection?.length" />
          <table v-else class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <ListingHeaderCell v-if="withBulkSelect" class="w-8 sm:w-14" />
                <slot name="tableHead">
                  <ListingHeaderCell v-for="column in columns" :sortBy="column">
                    <!-- TODO: get translation for col -->
                    {{ column }}
                  </ListingHeaderCell>
                  <ListingHeaderCell>
                    <span class="sr-only">{{
                      $t("craftable-pro", "Edit")
                    }}</span>
                  </ListingHeaderCell>
                </slot>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 bg-white">
              <tr
                v-for="item in collection"
                :key="item.id"
                :class="{
                  'bg-gray-50': selectedItems.includes(item.id),
                }"
              >
                <ListingDataCell
                  v-if="withBulkSelect"
                  class="relative w-8 sm:w-14"
                  v-auto-animate="{ duration: 150 }"
                >
                  <div
                    v-if="selectedItems.includes(item.id)"
                    class="bg-primary-600 absolute inset-y-0 left-0 w-0.5"
                  />
                  <Checkbox :inputValue="item.id" v-model="selectedItems" />
                </ListingDataCell>
                <slot name="tableRow" :item="item" :action="action">
                  <ListingDataCell v-for="column in columns">
                    {{ item[column] }}
                  </ListingDataCell>
                  <ListingDataCell>
                    <Link
                      :href="item.resource_url"
                      class="text-gray-500 hover:text-gray-700"
                    >
                      <ChevronRightIcon class="ml-auto h-5 w-5" />
                    </Link>
                  </ListingDataCell>
                </slot>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <template #footer>
      <CardFooter v-if="withPagination">
        <Pagination :pagination="pagination" />
      </CardFooter>
    </template>
  </Card>
</template>
<script setup lang="ts">
import { computed, provide, useSlots } from "vue";
import {
  ChevronRightIcon,
  MagnifyingGlassIcon,
  CheckIcon,
} from "@heroicons/vue/24/outline";
import { TrashIcon, PencilIcon } from "@heroicons/vue/24/solid";
import {
  Button,
  Checkbox,
  TextInput,
  Dropdown,
  Pagination,
  Card,
  CardFooter,
  CardHeader,
} from "custom-app/Components";
import { EmptyListing, ListingHeaderCell, ListingDataCell } from "./index";
import { PaginatedCollection } from "custom-app/types/pagination";
import { Model } from "custom-app/types/models";
import { useBulkSelect } from "custom-app/hooks/useBulkSelect";
import { useBulkAction } from "custom-app/hooks/useBulkAction";
import { useAction } from "custom-app/hooks/useAction";
import { useListingSearch } from "custom-app/hooks/useListingSearch";

interface Props {
  data: PaginatedCollection<Model>;
  dataKey?: string;
  baseUrl?: string;
  columns?: string[];
  withBulkSelect?: boolean;
  filters?: object;
  withPagination?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  withBulkSelect: true,
  baseUrl: route(route().current(), route().params),
  dataKey: "data",
  withPagination: true,
});

provide("listingBaseUrl", props.baseUrl);

if (props.dataKey) {
  provide("listingDataKey", props.dataKey);
}

const slots = useSlots();

const collection = computed(() => props.data.data);

const pagination = computed(() => props.data);

const {
  selectedItems,
  indeterminate,
  allItemsSelected,
  selectAllItems,
  loadingAllItems,
  unselectAllItems,
} = useBulkSelect();

const { bulkAction } = useBulkAction(selectedItems);

const { action } = useAction();

const { searchForm, resetSearch } = useListingSearch(props.baseUrl);
</script>
