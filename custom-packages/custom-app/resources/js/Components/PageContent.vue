<template>
  <div class="relative flex flex-1 items-stretch overflow-hidden">
    <div v-if="hasTabs" class="flex flex-1 flex-col">
      <TabGroup>
        <TabList
          class="flex w-full border-b border-gray-200 bg-white px-4 sm:px-6"
        >
          <slot name="tabs" />
        </TabList>

        <div class="flex-1 overflow-y-auto pb-20">
          <div
            class="mx-auto mt-6 w-full max-w-screen-2xl px-4 sm:px-6 md:px-8"
          >
            <TabPanels>
              <slot />
            </TabPanels>
          </div>
        </div>
      </TabGroup>
    </div>

    <div v-else class="flex-1 overflow-y-auto pb-20">
      <div class="mx-auto mt-6 w-full max-w-screen-2xl px-4 sm:px-6 md:px-8">
        <slot />
      </div>
    </div>

    <slot name="aside" />
  </div>
</template>

<script setup lang="ts">
import { computed, useSlots } from "vue";
import { TabGroup, TabList, TabPanels } from "@headlessui/vue";

const slots = useSlots();
const hasTabs = computed(() => !!slots.tabs);
</script>
