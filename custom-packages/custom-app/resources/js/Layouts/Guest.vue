<template>
  <div class="flex min-h-screen flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <Logo class="mx-auto [&>*>*]:mx-auto [&>*]:mx-auto" />
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
import Logo from "@/custom-app/Components/Logo.vue";
</script>
