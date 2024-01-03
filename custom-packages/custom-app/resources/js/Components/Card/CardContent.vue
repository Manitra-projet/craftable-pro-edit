<template>
  <div
    class="bg-white"
    :class="{
      'px-4 py-5 sm:p-6': !noPadding,
    }"
  >
    <slot />
  </div>
</template>

<script setup lang="ts">
interface Props {
  noPadding?: boolean;
}

const props = defineProps<Props>();
</script>
