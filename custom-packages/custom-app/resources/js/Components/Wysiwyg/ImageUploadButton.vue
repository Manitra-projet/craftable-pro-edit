<template>
  <ImageUploadModal>
    <template #button="{ setIsOpen }">
      <ToolbarButton @click="() => setIsOpen(true)" :icon="PhotoIconSolid" />
    </template>
  </ImageUploadModal>
</template>
<script setup lang="ts">
import { PhotoIcon as PhotoIconSolid } from "@heroicons/vue/24/solid";
import ToolbarButton from "./ToolbarButton.vue";
import { ImageUploadModal } from "custom-app/Components";
</script>
