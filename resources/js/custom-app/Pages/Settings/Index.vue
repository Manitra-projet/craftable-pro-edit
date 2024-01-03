<template>
  <IndexPage v-bind="$props" />
</template>

<script lang="ts" setup>

import IndexPage from "custom-app/Pages/Settings/Index.vue";
import {GeneralSettings} from "custom-app/Pages/Settings/types";

interface Props {
  generalSettings: GeneralSettings;
  availableRoutes: string[];
}

defineProps<Props>();

</script>
