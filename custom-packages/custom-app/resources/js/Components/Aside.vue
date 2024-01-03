<template>
  <aside
    class="pointer-events-auto w-screen max-w-xs pl-10 md:max-w-sm xl:relative xl:pl-0 2xl:max-w-md"
  >
    <div
      class="flex h-full flex-col overflow-y-auto border-l border-gray-200 bg-white"
      :class="{ 'px-4 py-5 sm:p-6': !noPadding }"
    >
      <slot />
    </div>
  </aside>
</template>

<script lang="ts" setup>
interface Props {
  noPadding?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  noPadding: false,
});
</script>
