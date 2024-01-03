<template>
  <Disclosure
    as="div"
    v-slot="{ open }"
    :defaultOpen="defaultOpen"
    v-auto-animate
  >
    <DisclosureButton
      class="group flex w-full items-center justify-between px-4 py-3 text-left font-medium leading-8 text-gray-900 focus:outline-none focus-visible:ring focus-visible:ring-primary-500 focus-visible:ring-opacity-75"
    >
      <slot name="title" />
      <ChevronUpIcon
        :class="{ 'rotate-180 transform': open }"
        class="h-5 w-5 text-gray-400 group-hover:text-gray-700"
      />
    </DisclosureButton>
    <div v-show="open">
      <DisclosurePanel class="p-4 pt-1 text-gray-700" static>
        <slot name="content" />
      </DisclosurePanel>
    </div>
  </Disclosure>
</template>

<script setup lang="ts">
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronUpIcon } from "@heroicons/vue/20/solid";

interface Props {
  defaultOpen?: boolean;
}

withDefaults(defineProps<Props>(), {
  defaultOpen: false,
});
</script>
