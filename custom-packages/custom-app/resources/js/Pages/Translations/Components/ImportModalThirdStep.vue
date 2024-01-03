<template>
  <p class="text-sm font-medium text-gray-700">
    {{
      $t("custom-app", ":count successfully imported", {
        count: numberOfSuccessfullyImportedTranslations.toString(),
      })
    }}
    {{
      $t("custom-app", ":count successfully updated", {
        count: numberOfSuccessfullyUpdatedTranslations.toString(),
      })
    }}
  </p>
</template>

<script lang="ts" setup>
interface Props {
  numberOfSuccessfullyImportedTranslations: number;
  numberOfSuccessfullyUpdatedTranslations: number;
}

const props = defineProps<Props>();
</script>
