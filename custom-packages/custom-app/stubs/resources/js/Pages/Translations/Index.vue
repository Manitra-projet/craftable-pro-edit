<template>
  <TranslationsPage v-bind="$props" />
</template>

<script lang="ts" setup>
import type { LanguageLine } from "custom-app/types/models";
import TranslationsPage from "custom-app/Pages/Translations/Index.vue";
import { PaginatedCollection } from "custom-app/types/pagination";

interface Props {
  data: PaginatedCollection<LanguageLine>;
  groups: string[];
  locales: string[];
}

defineProps<Props>();
</script>
