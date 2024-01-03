<template>
  <img
    v-if="isFileImage(file.file_name)"
    :src="src"
    :alt="file.custom_properties?.name || file.file_name"
    class="aspect-square w-full rounded object-cover"
  />
  <div
    v-else
    class="relative flex aspect-square h-full items-center justify-center"
  >
    <DocumentIcon class="w-full max-w-[5rem] stroke-1 text-gray-500" />
    <span
      class="absolute translate-y-1 font-mono text-sm font-semibold uppercase text-gray-500"
    >
      {{ getFileExtension(file.file_name) }}
    </span>
  </div>
</template>

<script setup lang="ts">
import { DocumentIcon } from "@heroicons/vue/24/outline";
import { UploadedFile } from "custom-app/types";
import { isFileImage, getFileExtension } from "custom-app/helpers";
import { Media } from "custom-app/Pages/Media/types";
import { ref, watch } from "vue";

interface Props {
  file: UploadedFile & Media;
}

const props = defineProps<Props>();
const src = ref(props.file.preview_url || props.file.original_url);
const reader = new FileReader();

reader.onloadend = (e) => {
  src.value = reader.result;
};

watch(
  () => props.file.file,
  () => {
    if (props.file.file) {
      reader.readAsDataURL(props.file.file);
    }
  },
  { immediate: true }
);
</script>
